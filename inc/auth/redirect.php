<?php
/**
 * Handle user redirects
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle login redirect
 */
function szkolakademia_login_redirect($redirect_to, $requested_redirect_to, $user) {
    // If there's a specific redirect requested and it's not the WooCommerce my-account page
    if (!empty($requested_redirect_to) && strpos($requested_redirect_to, 'my-account') === false) {
        return $requested_redirect_to;
    }

    // If user is not logged in, return default
    if (!is_object($user) || !isset($user->ID)) {
        return $redirect_to;
    }

    // If user is admin, redirect to admin dashboard
    if (in_array('administrator', (array) $user->roles)) {
        return admin_url();
    }

    // If user is course user, redirect to course dashboard
    if (in_array('course_user', (array) $user->roles)) {
        return home_url('/course/dashboard');
    }

    // Default redirect
    return home_url();
}
add_filter('login_redirect', 'szkolakademia_login_redirect', 99, 3);

/**
 * Handle registration redirect
 */
function szkolakademia_registration_redirect($redirect_to) {
    if (is_course_user()) {
        return home_url('/course/dashboard');
    }
    return $redirect_to;
}
add_filter('registration_redirect', 'szkolakademia_registration_redirect', 10, 1);

/**
 * Protect course dashboard access
 */
function szkolakademia_protect_course_dashboard() {
    // Check if we're on the course dashboard page
    if (strpos($_SERVER['REQUEST_URI'], '/course/dashboard') !== false) {
        // If not logged in, redirect to login
        if (!is_user_logged_in()) {
            wp_redirect(home_url('/auth/signin'));
            exit;
        }

        // If admin, redirect to admin dashboard
        if (current_user_can('manage_options')) {
            wp_redirect(admin_url());
            exit;
        }

        // If not a course user, redirect to home
        if (!is_course_user()) {
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action('template_redirect', 'szkolakademia_protect_course_dashboard');

/**
 * Redirect after logout
 */
function szkolakademia_logout_redirect() {
    wp_redirect(home_url('/auth/signin?logged_out=true'));
    exit;
}
add_action('wp_logout', 'szkolakademia_logout_redirect');

/**
 * Redirect on login error
 */
function szkolakademia_login_failed() {
    wp_redirect(home_url('/auth/signin?login=failed'));
    exit;
}
add_action('wp_login_failed', 'szkolakademia_login_failed');
