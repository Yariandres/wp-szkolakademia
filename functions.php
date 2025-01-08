<?php
/**
 * Theme core functionality and setup
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add this with your other requires at the top of functions.php
require_once get_template_directory() . '/inc/users/functions.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function szkolakademia_setup() {
    // Add essential theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ]);
    add_theme_support('woocommerce'); // Required for course purchases
    add_theme_support('custom-header');
    add_theme_support('automatic-feed-links');

    // Register navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'szkolakademia'),
        'footer' => __('Footer Menu', 'szkolakademia'),
        'dashboard' => __('Dashboard Menu', 'szkolakademia'), // For logged-in users
        'courses' => __('Courses Menu', 'szkolakademia'), // For course categories
    ]);

    // Set content width
    if (!isset($GLOBALS['content_width'])) {
        $GLOBALS['content_width'] = 1200; // Pixels
    }
}
add_action('after_setup_theme', 'szkolakademia_setup');

// Register widget areas/sidebars
function szkolakademia_widget_areas_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'szkolakademia'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'szkolakademia'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'szkolakademia_widget_areas_init'); 

function szkolakademia_setup_scripts() {
    wp_enqueue_script('szkolakademia_output', get_template_directory_uri() . '/dist/js/output.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'szkolakademia_setup_scripts');

function szkolakademia_setup_styles() {
    wp_enqueue_style('szkolakademia_output', get_template_directory_uri() . '/dist/css/output.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'szkolakademia_setup_styles');

require_once get_template_directory() . '/inc/courses/cpt.php';
require_once get_template_directory() . '/inc/courses/meta.php';

// Load WooCommerce integration
require_once get_template_directory() . '/inc/courses/woocommerce.php';

// Load authentication handlers
require_once get_template_directory() . '/inc/auth/register.php';
require_once get_template_directory() . '/inc/auth/login.php';

// Add this with your other requires
require_once get_template_directory() . '/inc/auth/redirect.php';

/**
 * Add custom rewrite rules for auth pages
 */
function szkolakademia_add_rewrite_rules() {
    // Add auth endpoints
    add_rewrite_endpoint('auth', EP_ROOT);
    
    // Add specific rules
    add_rewrite_rule(
        '^auth/signin/?$',
        'index.php?auth=signin',
        'top'
    );
    add_rewrite_rule(
        '^auth/register/?$',
        'index.php?auth=register',
        'top'
    );
    add_rewrite_rule(
        '^course/dashboard/?$',
        'index.php?course_page=dashboard',
        'top'
    );
}
add_action('init', 'szkolakademia_add_rewrite_rules');

/**
 * Register custom query vars
 */
function szkolakademia_register_query_vars($vars) {
    $vars[] = 'auth';
    $vars[] = 'course_page';
    return $vars;
}
add_filter('query_vars', 'szkolakademia_register_query_vars');

/**
 * Load custom template for auth pages
 */
function szkolakademia_load_auth_template($template) {
    $auth = get_query_var('auth');
    $course_page = get_query_var('course_page');
    
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Template Loading - Auth: ' . $auth . ', Course Page: ' . $course_page);
    }
    
    if ($auth === 'signin') {
        return get_template_directory() . '/templates/auth/template-signin.php';
    }
    
    if ($auth === 'register') {
        return get_template_directory() . '/templates/auth/template-register.php';
    }

    if ($course_page === 'dashboard') {
        return get_template_directory() . '/templates/course/dashboard.php';
    }
    
    return $template;
}
// First add the filter
add_filter('template_include', 'szkolakademia_load_auth_template', 5);

/**
 * Disable WooCommerce default redirect to my-account
 */
function szkolakademia_disable_wc_redirect() {
    remove_filter('template_redirect', 'wc_redirect_to_checkout');
    remove_action('template_redirect', 'wc_auth_endpoint_redirect');
    
    // Prevent WooCommerce from redirecting to my-account
    if (is_user_logged_in() && !is_admin()) {
        remove_filter('login_redirect', 'wc_login_redirect', 10);
        remove_filter('registration_redirect', 'wc_registration_redirect', 10);
    }
}
add_action('init', 'szkolakademia_disable_wc_redirect', 5);

/**
 * Override WooCommerce customer login redirect
 */
function szkolakademia_override_wc_login_redirect($redirect, $user) {
    if (is_course_user()) {
        return home_url('/course/dashboard');
    }
    return $redirect;
}
add_filter('woocommerce_login_redirect', 'szkolakademia_override_wc_login_redirect', 99, 2);

/**
 * Override WooCommerce registration redirect
 */
function szkolakademia_override_wc_registration_redirect($redirect) {
    if (is_course_user()) {
        return home_url('/course/dashboard');
    }
    return $redirect;
}
add_filter('woocommerce_registration_redirect', 'szkolakademia_override_wc_registration_redirect', 99);

// Register page templates
function szkolakademia_add_page_templates($templates) {
    $templates['templates/auth/template-register.php'] = __('Registration Page', 'szkolakademia');
    $templates['templates/auth/template-signin.php'] = __('Sign In Page', 'szkolakademia');
    return $templates;
}
add_filter('theme_page_templates', 'szkolakademia_add_page_templates');

// Add this temporarily for debugging
function szkolakademia_debug_template($template) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Loading template: ' . $template);
    }
    return $template;
}
add_filter('template_include', 'szkolakademia_debug_template', 100);

/**
 * Add custom course user role on theme activation
 */
function szkolakademia_theme_activation() {
    // Add the course_user role if it doesn't exist
    if (!get_role('course_user')) {
        add_role(
            'course_user',
            __('Course User', 'szkolakademia'),
            array(
                'read' => true,
                'course_access' => true
            )
        );
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Course user role created');
        }
    }
}
add_action('after_switch_theme', 'szkolakademia_theme_activation');

// Add this with your other includes
require_once get_template_directory() . '/inc/courses/ajax.php';

// Add this with your other includes
require_once get_template_directory() . '/inc/courses/setup.php';

/**
 * Add custom rewrite rules for course pages
 */
function szkolakademia_add_course_rewrite_rules() {
    add_rewrite_rule(
        '^course/player/?$',
        'index.php?course_page=player',
        'top'
    );
}
add_action('init', 'szkolakademia_add_course_rewrite_rules');

/**
 * Register custom query vars
 */
function szkolakademia_register_course_query_vars($vars) {
    $vars[] = 'course_page';
    return $vars;
}
add_filter('query_vars', 'szkolakademia_register_course_query_vars');

/**
 * Load course templates
 */
function szkolakademia_load_course_template($template) {
    $course_page = get_query_var('course_page');
    
    if ($course_page === 'player') {
        return get_template_directory() . '/templates/course/player.php';
    }
    
    return $template;
}
add_filter('template_include', 'szkolakademia_load_course_template');

// Add this at the top with your other requires
require_once get_template_directory() . '/inc/courses/enrollment.php';

// Debug helper function
function szkolakademia_debug_functions() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Available functions: ' . print_r(get_defined_functions()['user'], true));
    }
}
add_action('init', 'szkolakademia_debug_functions');

// TODO: REMOVE AFTER TESTING
// Temporary function to enroll user - REMOVE AFTER TESTING
function szkolakademia_enroll_test_user() {
    $course_id = 7; // Replace with your actual course ID
    $user_id = get_current_user_id();
    
    if (is_user_logged_in() && !szkolakademia_is_user_enrolled($user_id, $course_id)) {
        szkolakademia_enroll_user($user_id, $course_id);
    }
}
add_action('init', 'szkolakademia_enroll_test_user');


// TODO: REMOVE AFTER TESTING
// Temporary function to add video URL - REMOVE AFTER TESTING
function szkolakademia_add_test_video() {
    $course_id = 7; // Your course ID
    $video_url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // Test video URL
    
    update_post_meta($course_id, 'course_video_url', $video_url);
}
add_action('init', 'szkolakademia_add_test_video');

require_once get_template_directory() . '/inc/courses/meta.php';
require_once get_template_directory() . '/inc/courses/setup.php';
require_once get_template_directory() . '/inc/courses/enrollment.php';
require_once get_template_directory() . '/inc/courses/ajax.php';

/**
 * Debug script loading
 */
function szkolakademia_debug_admin_scripts($hook) {
    error_log('Admin page hook: ' . $hook);
    error_log('Current screen: ' . print_r(get_current_screen(), true));
    
    if (get_post_type() === 'course') {
        wp_enqueue_script(
            'szkolakademia-course-editor',
            get_template_directory_uri() . '/assets/js/course-editor.js',
            array(),
            time(), // Force no cache during debug
            true
        );
        error_log('Course editor script enqueued');
    }
}
add_action('admin_enqueue_scripts', 'szkolakademia_debug_admin_scripts');

// Add this line with your other includes
require_once get_template_directory() . '/inc/courses/navigation.php';

// Add this to your functions.php
function szkolakademia_enqueue_scripts() {
    // Add AJAX URL to JavaScript
    wp_localize_script('szkolakademia_output', 'ajaxurl', admin_url('admin-ajax.php'));

    // Add Alpine.js for dropdown functionality
    wp_enqueue_script(
        'alpinejs',
        'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js',
        array(),
        null,
        true
    );
    wp_script_add_data('alpinejs', 'defer', true);
}
add_action('wp_enqueue_scripts', 'szkolakademia_enqueue_scripts');