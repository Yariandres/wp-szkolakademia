<?php
/**
 * AJAX handlers for course functionality
 */

function szkolakademia_handle_progress_update() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'szkolakademia_progress_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Verify user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    $course_id = intval($_POST['course_id']);
    $progress = intval($_POST['progress']);

    // Update progress
    $success = szkolakademia_update_course_progress(get_current_user_id(), $course_id, $progress);

    if ($success) {
        wp_send_json_success(['progress' => $progress]);
    } else {
        wp_send_json_error('Failed to update progress');
    }
}
add_action('wp_ajax_update_course_progress', 'szkolakademia_handle_progress_update'); 

/**
 * Course AJAX handlers
 */

function szkolakademia_enroll_course_ajax() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'enroll_course')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('Please log in to enroll');
    }

    // Get course ID
    $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    if (!$course_id) {
        wp_send_json_error('Invalid course ID');
    }

    // Check if course exists
    $course = get_post($course_id);
    if (!$course || $course->post_type !== 'course') {
        wp_send_json_error('Course not found');
    }

    // Enroll user
    $user_id = get_current_user_id();
    $result = szkolakademia_enroll_user($user_id, $course_id);

    if ($result) {
        wp_send_json_success([
            'message' => 'Successfully enrolled',
            'redirect_url' => add_query_arg(['course_id' => $course_id], home_url('/course/player/'))
        ]);
    } else {
        wp_send_json_error('Already enrolled or enrollment failed');
    }
}
add_action('wp_ajax_enroll_course', 'szkolakademia_enroll_course_ajax'); 