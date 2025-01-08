<?php
/**
 * Course meta boxes and saving functionality
 */

function szkolakademia_course_structure_callback($post) {
    // Add styles
    ?>
    <style>
        #course-structure-editor {
            padding: 15px;
            background: #fff;
        }
        .section-item {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .chapter-item {
            background: #fff;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .lesson-item {
            background: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .button-link-delete {
            color: #dc3545;
            cursor: pointer;
        }
        .add-section {
            margin-top: 15px;
        }
    </style>

    <?php
    // Add nonce
    wp_nonce_field('course_structure_nonce', 'course_structure_nonce');

    // Get existing structure
    $structure = get_post_meta($post->ID, 'course_structure', true);
    if (!is_array($structure)) {
        $structure = array();
    }

    // Course structure editor
    ?>
    <div id="course-structure-editor">
        <div class="course-sections">
            <?php if (!empty($structure)) : ?>
                <?php foreach ($structure as $section_index => $section) : ?>
                    <div class="section-item">
                        <div class="section-header flex justify-between items-center mb-2">
                            <h3 class="font-bold"><?php esc_html_e('Section', 'szkolakademia'); ?></h3>
                            <button type="button" class="remove-section button-link-delete">×</button>
                        </div>
                        <input type="text" 
                               name="course_structure[<?php echo esc_attr($section_index); ?>][title]" 
                               value="<?php echo esc_attr($section['title'] ?? ''); ?>"
                               class="widefat"
                               placeholder="<?php esc_attr_e('Section Title', 'szkolakademia'); ?>">
                        
                        <div class="chapters mt-4 ml-4">
                            <?php if (!empty($section['chapters'])) : ?>
                                <?php foreach ($section['chapters'] as $chapter_index => $chapter) : ?>
                                    <div class="chapter-item">
                                        <div class="chapter-header flex justify-between items-center mb-2">
                                            <h4 class="font-medium"><?php esc_html_e('Chapter', 'szkolakademia'); ?></h4>
                                            <button type="button" class="remove-chapter button-link-delete">×</button>
                                        </div>
                                        <input type="text" 
                                               name="course_structure[<?php echo esc_attr($section_index); ?>][chapters][<?php echo esc_attr($chapter_index); ?>][title]" 
                                               value="<?php echo esc_attr($chapter['title'] ?? ''); ?>"
                                               class="widefat"
                                               placeholder="<?php esc_attr_e('Chapter Title', 'szkolakademia'); ?>">
                                        
                                        <div class="lessons mt-2 ml-4">
                                            <?php if (!empty($chapter['lessons'])) : ?>
                                                <?php foreach ($chapter['lessons'] as $lesson_index => $lesson) : ?>
                                                    <div class="lesson-item">
                                                        <div class="lesson-header flex justify-between items-center mb-2">
                                                            <span class="text-sm font-medium"><?php esc_html_e('Lesson', 'szkolakademia'); ?></span>
                                                            <button type="button" class="remove-lesson button-link-delete">×</button>
                                                        </div>
                                                        <input type="text" 
                                                               name="course_structure[<?php echo esc_attr($section_index); ?>][chapters][<?php echo esc_attr($chapter_index); ?>][lessons][<?php echo esc_attr($lesson_index); ?>][title]" 
                                                               value="<?php echo esc_attr($lesson['title'] ?? ''); ?>"
                                                               class="widefat mb-2"
                                                               placeholder="<?php esc_attr_e('Lesson Title', 'szkolakademia'); ?>">
                                                        <input type="url" 
                                                               name="course_structure[<?php echo esc_attr($section_index); ?>][chapters][<?php echo esc_attr($chapter_index); ?>][lessons][<?php echo esc_attr($lesson_index); ?>][video_url]" 
                                                               value="<?php echo esc_url($lesson['video_url'] ?? ''); ?>"
                                                               class="widefat"
                                                               placeholder="<?php esc_attr_e('Video URL', 'szkolakademia'); ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <button type="button" class="add-lesson button-secondary">
                                                <?php esc_html_e('Add Lesson', 'szkolakademia'); ?>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <button type="button" class="add-chapter button-secondary">
                                <?php esc_html_e('Add Chapter', 'szkolakademia'); ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <button type="button" class="add-section button-primary">
                <?php esc_html_e('Add Section', 'szkolakademia'); ?>
            </button>
        </div>
    </div>
    <?php
}

function szkolakademia_save_course_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['course_structure_nonce'])) {
        return;
    }

    // Verify the nonce
    if (!wp_verify_nonce($_POST['course_structure_nonce'], 'course_structure_nonce')) {
        return;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save course structure
    if (isset($_POST['course_structure']) && is_array($_POST['course_structure'])) {
        $structure = array();
        foreach ($_POST['course_structure'] as $section_index => $section) {
            if (!empty($section['title'])) {
                $structure[$section_index] = array(
                    'title' => sanitize_text_field($section['title']),
                    'chapters' => array()
                );

                if (!empty($section['chapters']) && is_array($section['chapters'])) {
                    foreach ($section['chapters'] as $chapter_index => $chapter) {
                        if (!empty($chapter['title'])) {
                            $structure[$section_index]['chapters'][$chapter_index] = array(
                                'title' => sanitize_text_field($chapter['title']),
                                'lessons' => array()
                            );

                            if (!empty($chapter['lessons']) && is_array($chapter['lessons'])) {
                                foreach ($chapter['lessons'] as $lesson_index => $lesson) {
                                    if (!empty($lesson['title'])) {
                                        $structure[$section_index]['chapters'][$chapter_index]['lessons'][$lesson_index] = array(
                                            'title' => sanitize_text_field($lesson['title']),
                                            'video_url' => esc_url_raw($lesson['video_url'] ?? '')
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        update_post_meta($post_id, 'course_structure', $structure);
    }
}
add_action('save_post_course', 'szkolakademia_save_course_meta'); 