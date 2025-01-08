<?php
/**
 * Course Custom Post Type and Taxonomies
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Course post type
 */
function szkolakademia_register_course_post_type() {
    $labels = [
        'name'               => _x('Courses', 'post type general name', 'szkolakademia'),
        'singular_name'      => _x('Course', 'post type singular name', 'szkolakademia'),
        'menu_name'          => _x('Courses', 'admin menu', 'szkolakademia'),
        'add_new'            => _x('Add New', 'course', 'szkolakademia'),
        'add_new_item'       => __('Add New Course', 'szkolakademia'),
        'edit_item'          => __('Edit Course', 'szkolakademia'),
        'view_item'          => __('View Course', 'szkolakademia'),
        'all_items'          => __('All Courses', 'szkolakademia'),
        'search_items'       => __('Search Courses', 'szkolakademia'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_rest'      => true, // Enable Gutenberg editor
        'menu_icon'         => 'dashicons-welcome-learn-more',
        'supports'          => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions'
        ],
        'has_archive'       => true,
        'rewrite'           => ['slug' => 'courses'],
        'capability_type'   => 'post',
        'map_meta_cap'      => true,
    ];

    register_post_type('course', $args);

    // Register Course Category taxonomy
    register_taxonomy('course_category', ['course'], [
        'hierarchical'      => true,
        'labels'           => [
            'name'              => _x('Course Categories', 'taxonomy general name', 'szkolakademia'),
            'singular_name'     => _x('Course Category', 'taxonomy singular name', 'szkolakademia'),
        ],
        'show_ui'          => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => ['slug' => 'course-category'],
    ]);

    // Register Course Level taxonomy
    register_taxonomy('course_level', ['course'], [
        'hierarchical'      => false,
        'labels'           => [
            'name'              => _x('Course Levels', 'taxonomy general name', 'szkolakademia'),
            'singular_name'     => _x('Course Level', 'taxonomy singular name', 'szkolakademia'),
        ],
        'show_ui'          => true,
        'show_admin_column' => true,
        'show_in_rest'     => true,
        'rewrite'          => ['slug' => 'course-level'],
    ]);
}
add_action('init', 'szkolakademia_register_course_post_type');
