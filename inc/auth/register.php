<?php
/**
 * Custom registration handling
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom registration
 */
function szkolakademia_handle_registration() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Registration attempt started');
    }

    if (!isset($_POST['registration_nonce']) || 
        !wp_verify_nonce($_POST['registration_nonce'], 'registration_nonce')) {
        error_log('Registration nonce verification failed');
        return;
    }

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Registration data received - Username: ' . $username . ', Email: ' . $email);
    }

    $errors = new WP_Error();

    // Validation
    if (empty($username)) {
        $errors->add('username_empty', __('Username is required.', 'szkolakademia'));
    }
    if (empty($email)) {
        $errors->add('email_empty', __('Email is required.', 'szkolakademia'));
    }
    if (empty($password)) {
        $errors->add('password_empty', __('Password is required.', 'szkolakademia'));
    }
    if ($password !== $password_confirm) {
        $errors->add('password_mismatch', __('Passwords do not match.', 'szkolakademia'));
    }

    if ($errors->has_errors()) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Registration validation errors: ' . print_r($errors->get_error_messages(), true));
        }
        return $errors;
    }

    // Create user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('User creation failed: ' . $user_id->get_error_message());
        }
        return $user_id;
    }

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('User created successfully with ID: ' . $user_id);
    }

    // Set role to course_user
    $user = new WP_User($user_id);
    $user->set_role('course_user');

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('User roles after setting: ' . print_r($user->roles, true));
    }

    // Log the user in
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_redirect(home_url('/course/dashboard'));
    exit;
}
add_action('admin_post_nopriv_szkolakademia_register', 'szkolakademia_handle_registration');
