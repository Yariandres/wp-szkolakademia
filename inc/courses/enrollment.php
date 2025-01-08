<?php
/**
 * Course enrollment functions
 */

function szkolakademia_enroll_user($user_id, $course_id) {
    // Get current enrollments
    $enrollments = get_user_meta($user_id, 'szkolakademia_enrolled_courses', true);
    if (!is_array($enrollments)) {
        $enrollments = array();
    }

    // Add the course if not already enrolled
    if (!isset($enrollments[$course_id])) {
        $enrollments[$course_id] = array(
            'enrolled_date' => current_time('mysql'),
            'progress' => 0,
            'completed_lessons' => array()
        );
        
        update_user_meta($user_id, 'szkolakademia_enrolled_courses', $enrollments);
        return true;
    }
    
    return false;
}

function szkolakademia_is_user_enrolled($user_id, $course_id) {
    $enrollments = get_user_meta($user_id, 'szkolakademia_enrolled_courses', true);
    return is_array($enrollments) && isset($enrollments[$course_id]);
}

function szkolakademia_get_user_progress($user_id, $course_id) {
    $enrollments = get_user_meta($user_id, 'szkolakademia_enrolled_courses', true);
    if (is_array($enrollments) && isset($enrollments[$course_id])) {
        return array(
            'progress' => $enrollments[$course_id]['progress'] ?? 0,
            'completed_lessons' => $enrollments[$course_id]['completed_lessons'] ?? array()
        );
    }
    return array('progress' => 0, 'completed_lessons' => array());
}
