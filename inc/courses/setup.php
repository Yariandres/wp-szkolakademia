<?php
/**
 * Course setup and initialization
 */

function szkolakademia_enqueue_course_scripts() {
    if (is_page_template('templates/course/player.php')) {
        wp_enqueue_script(
            'szkolakademia-course-player',
            get_template_directory_uri() . '/assets/js/course-player.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('szkolakademia-course-player', 'szkolaAcademyData', array(
            'nonce' => wp_create_nonce('szkolakademia_progress_nonce')
        ));
    }

    if (is_admin()) {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'course') {
            wp_enqueue_script(
                'szkolakademia-course-editor',
                get_template_directory_uri() . '/assets/js/course-editor.js',
                array(),
                '1.0.0',
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'szkolakademia_enqueue_course_scripts');
add_action('admin_enqueue_scripts', 'szkolakademia_enqueue_course_scripts');

function szkolakademia_debug_scripts() {
    if (is_admin()) {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'course') {
            error_log('Admin screen: course');
            error_log('Scripts enqueued: ' . print_r(wp_scripts()->queue, true));
        }
    }
}
add_action('wp_enqueue_scripts', 'szkolakademia_debug_scripts');
add_action('admin_enqueue_scripts', 'szkolakademia_debug_scripts'); 