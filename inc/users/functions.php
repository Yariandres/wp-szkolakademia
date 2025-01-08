<?php
/**
 * User-related functions
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if user is a course user
 *
 * @param int|null $user_id User ID to check. Defaults to current user.
 * @return bool Whether the user is a course user.
 */
function is_course_user($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    $user = get_userdata($user_id);
    if (!$user) {
        return false;
    }
    
    return in_array('course_user', (array) $user->roles);
}