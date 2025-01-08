<?php
/**
 * Template Name: Course Player
 * 
 * @package SzkolaKademia
 */

// Get course ID from URL parameter
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

// Make sure we have a valid course ID
if (!$course_id) {
    wp_die('Please provide a course ID in the URL');
}

// Verify the course exists and is published
$course = get_post($course_id);
if (!$course || $course->post_type !== 'course' || $course->post_status !== 'publish') {
    wp_die('Invalid or unpublished course');
}

// Get current user
$user_id = get_current_user_id();
if (!$user_id) {
    wp_die('Please log in to view this course');
}

// Check enrollment
if (!szkolakademia_is_user_enrolled($user_id, $course_id)) {
    wp_die('You are not enrolled in this course');
}

// Get course structure
$structure = get_post_meta($course_id, 'course_structure', true);
$current_section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$current_chapter = isset($_GET['chapter']) ? intval($_GET['chapter']) : 0;
$current_lesson = isset($_GET['lesson']) ? intval($_GET['lesson']) : 0;

// Get current video URL
$current_video_url = '';
if (is_array($structure) 
    && isset($structure[$current_section]['chapters'][$current_chapter]['lessons'][$current_lesson]['video_url'])) {
    $current_video_url = $structure[$current_section]['chapters'][$current_chapter]['lessons'][$current_lesson]['video_url'];
}

get_header();
?>

<div class="min-h-screen bg-gray-100">
    <!-- Course Header -->
    <header class="shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center py-8 bg-gray-100">
                <h1 class="text-xl font-semibold text-gray-900">
                    <?php echo esc_html($course->post_title); ?>
                </h1>
                <a href="<?php echo esc_url(home_url('/course/dashboard')); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <?php esc_html_e('Back to Dashboard', 'szkolakademia'); ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Course Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="flex md:flex-row justify-between">
            <!-- Main Content Area -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Video Player -->
                    <div class="aspect-w-16 aspect-h-9 bg-gray-900 rounded-t-lg">
                        <?php if ($current_video_url) : ?>
                            <div class="w-full h-full" style="height: 450px">
                                <?php 
                                echo wp_oembed_get($current_video_url, array(
                                    'width' => '100%',
                                    'height' => '100%'
                                ));
                                ?>
                            </div>
                        <?php else : ?>
                            <div class="w-full h-full bg-gray-900 flex items-center justify-center text-white">
                                <?php esc_html_e('Select a lesson to start learning', 'szkolakademia'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Add this after the video player section -->
                    <div class="border-t border-gray-200">
                        <div class="flex justify-between items-center p-4">
                            <?php
                            // Get navigation links
                            $prev_lesson = szkolakademia_get_adjacent_lesson($structure, $current_section, $current_chapter, $current_lesson, 'prev');
                            $next_lesson = szkolakademia_get_adjacent_lesson($structure, $current_section, $current_chapter, $current_lesson, 'next');
                            ?>
                            
                            <!-- Previous Lesson -->
                            <?php if ($prev_lesson) : ?>
                                <a href="?course_id=<?php echo esc_attr($course_id); ?>&section=<?php echo esc_attr($prev_lesson['section']); ?>&chapter=<?php echo esc_attr($prev_lesson['chapter']); ?>&lesson=<?php echo esc_attr($prev_lesson['lesson']); ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <?php esc_html_e('Previous Lesson', 'szkolakademia'); ?>
                                </a>
                            <?php else : ?>
                                <div></div>
                            <?php endif; ?>

                            <!-- Current Position -->
                            <span class="text-sm text-gray-500">
                                <?php
                                if (isset($structure[$current_section]['title'])) {
                                    echo esc_html(sprintf(
                                        '%s / %s / %s',
                                        $structure[$current_section]['title'],
                                        $structure[$current_section]['chapters'][$current_chapter]['title'] ?? '',
                                        $structure[$current_section]['chapters'][$current_chapter]['lessons'][$current_lesson]['title'] ?? ''
                                    ));
                                }
                                ?>
                            </span>

                            <!-- Next Lesson -->
                            <?php if ($next_lesson) : ?>
                                <a href="?course_id=<?php echo esc_attr($course_id); ?>&section=<?php echo esc_attr($next_lesson['section']); ?>&chapter=<?php echo esc_attr($next_lesson['chapter']); ?>&lesson=<?php echo esc_attr($next_lesson['lesson']); ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <?php esc_html_e('Next Lesson', 'szkolakademia'); ?>
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            <?php else : ?>
                                <div></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="prose max-w-none">
                            <?php echo wp_kses_post($course->post_content); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <!-- Progress section -->
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        <?php esc_html_e('Course Progress', 'szkolakademia'); ?>
                    </h2>

                    <?php
                    $enrollments = get_user_meta($user_id, 'szkolakademia_enrolled_courses', true);
                    $progress = isset($enrollments[$course_id]) ? $enrollments[$course_id]['progress'] : 0;
                    ?>

                    <div class="mb-6">
                        <div class="bg-gray-200 rounded-full h-2 mb-1">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo esc_attr($progress); ?>%"></div>
                        </div>
                        <span class="text-sm text-gray-600">
                            <?php printf(esc_html__('%d%% Complete', 'szkolakademia'), $progress); ?>
                        </span>
                    </div>

                    <!-- Course Structure -->
                    <h3 class="text-xl font-medium text-gray-900 mb-4">
                        <?php esc_html_e('Course Content', 'szkolakademia'); ?>
                    </h3>

                    <?php
                    $structure = get_post_meta($course_id, 'course_structure', true);
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log('Course structure in player: ' . print_r($structure, true));
                    }
                    
                    if (is_array($structure) && !empty($structure)) :
                    ?>
                        <div class="space-y-4">
                            <?php foreach ($structure as $section_index => $section) : ?>
                                <div class="border-b border-gray-200 pb-4">
                                    <!-- Section -->
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium text-gray-900">
                                            <?php echo esc_html($section['title']); ?>
                                        </h4>
                                    </div>
                                    
                                    <?php if (!empty($section['chapters'])) : ?>
                                        <div class="mt-2 ml-4 space-y-2">
                                            <?php foreach ($section['chapters'] as $chapter_index => $chapter) : ?>
                                                <!-- Chapter -->
                                                <div class="border-l-2 border-gray-200 pl-4">
                                                    <h5 class="font-medium text-gray-700">
                                                        <?php echo esc_html($chapter['title']); ?>
                                                    </h5>
                                                    
                                                    <?php if (!empty($chapter['lessons'])) : ?>
                                                        <div class="mt-1 ml-4 space-y-1">
                                                            <?php foreach ($chapter['lessons'] as $lesson_index => $lesson) : ?>
                                                                <!-- Lesson -->
                                                                <a href="?course_id=<?php echo esc_attr($course_id); ?>&section=<?php echo esc_attr($section_index); ?>&chapter=<?php echo esc_attr($chapter_index); ?>&lesson=<?php echo esc_attr($lesson_index); ?>" 
                                                                   class="flex items-center py-1 px-2 rounded-md hover:bg-gray-100 <?php echo isset($_GET['lesson']) && $_GET['lesson'] == $lesson_index ? 'bg-blue-50 text-blue-600' : 'text-gray-600'; ?>">
                                                                    <span class="mr-2">
                                                                        <?php if (isset($enrollments[$course_id]['completed_lessons'][$lesson_index])) : ?>
                                                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                        <?php else : ?>
                                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                                                            </svg>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                    <span class="text-sm"><?php echo esc_html($lesson['title']); ?></span>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p class="text-sm text-gray-500">
                            <?php esc_html_e('No course content available.', 'szkolakademia'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    iframe {
        width: 100%;
        height: 100%;
    }
</style>

<?php get_footer(); ?> 