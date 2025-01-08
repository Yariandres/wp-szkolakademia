<?php
/**
 * Custom login handling
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom login
 */
function szkolakademia_handle_login() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Login attempt started');
    }

    if (!isset($_POST['action']) || $_POST['action'] !== 'szkolakademia_login') {
        error_log('Invalid login action');
        return;
    }

    // Verify nonce
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'szkolakademia_login_nonce')) {
        error_log('Login nonce verification failed');
        wp_die('Invalid nonce');
    }

    $credentials = array(
        'user_login'    => $_POST['username'],
        'user_password' => $_POST['password'],
        'remember'      => isset($_POST['remember'])
    );

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Login attempt for user: ' . $credentials['user_login']);
    }

    $user = wp_signon($credentials);

    if (is_wp_error($user)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Login failed: ' . $user->get_error_message());
        }
        wp_redirect(home_url('/auth/signin?login=failed'));
        exit;
    }

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('User logged in successfully. User roles: ' . print_r($user->roles, true));
    }

    // Successful login, redirect based on user role
    if (in_array('administrator', $user->roles)) {
        wp_redirect(admin_url());
    } else {
        wp_redirect(home_url('/course/dashboard'));
    }
    exit;
}
add_action('admin_post_nopriv_szkolakademia_login', 'szkolakademia_handle_login');
