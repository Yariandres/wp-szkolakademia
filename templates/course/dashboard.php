<?php
/**
 * Template Name: Course Dashboard
 * 
 * @package SzkolaKademia
 */

// Ensure user is logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/auth/signin'));
    exit;
}

$user_id = get_current_user_id();
$enrollments = get_user_meta($user_id, 'szkolakademia_enrolled_courses', true);

get_header();
?>

<div class="min-h-screen bg-gray-100">
    <!-- Dashboard Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-semibold text-gray-900">
                <?php esc_html_e('My Courses', 'szkolakademia'); ?>
            </h1>
        </div>
    </header>

    <!-- Dashboard Content -->
    <main class="container mx-auto px-4 py-8">
        <?php if (is_array($enrollments) && !empty($enrollments)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($enrollments as $course_id => $enrollment) : 
                    $course = get_post($course_id);
                    if (!$course || $course->post_status !== 'publish') continue;
                    
                    // Get course thumbnail
                    $thumbnail = get_the_post_thumbnail_url($course_id, 'medium');
                    $progress = isset($enrollment['progress']) ? $enrollment['progress'] : 0;
                    ?>
                    
                    <div class="rounded-lg shadow-sm overflow-hidden">
                        <!-- Course Image -->
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                            <?php if ($thumbnail) : ?>
                                <img src="<?php echo esc_url($thumbnail); ?>" 
                                     alt="<?php echo esc_attr($course->post_title); ?>"
                                     class="object-cover w-full h-full">
                            <?php endif; ?>
                        </div>

                        <!-- Course Info -->
                        <div class="p-6 bg-pink-500">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <?php echo esc_html($course->post_title); ?>
                            </h3>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span><?php esc_html_e('Progress', 'szkolakademia'); ?></span>
                                    <span><?php echo esc_html($progress); ?>%</span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: <?php echo esc_attr($progress); ?>%">
                                    </div>
                                </div>
                            </div>

                            <!-- Enrollment Date -->
                            <div class="text-sm text-gray-500 mb-4">
                                <?php 
                                $enrolled_date = isset($enrollment['enrolled_date']) 
                                    ? mysql2date(get_option('date_format'), $enrollment['enrolled_date']) 
                                    : '';
                                if ($enrolled_date) {
                                    printf(
                                        esc_html__('Enrolled on: %s', 'szkolakademia'),
                                        esc_html($enrolled_date)
                                    );
                                }
                                ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="<?php echo esc_url(add_query_arg(['course_id' => $course_id], home_url('/course/player/'))); ?>" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    <?php 
                                    if ($progress > 0) {
                                        esc_html_e('Continue Learning', 'szkolakademia');
                                    } else {
                                        esc_html_e('Start Learning', 'szkolakademia');
                                    }
                                    ?>
                                </a>
                                <a href="<?php echo esc_url(get_permalink($course_id)); ?>" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <?php esc_html_e('Course Details', 'szkolakademia'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <!-- No Courses State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    <?php esc_html_e('No courses yet', 'szkolakademia'); ?>
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    <?php esc_html_e('Get started by enrolling in your first course.', 'szkolakademia'); ?>
                </p>
                <div class="mt-6">
                    <a href="<?php echo esc_url(home_url('/courses')); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <?php esc_html_e('Browse Courses', 'szkolakademia'); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php get_footer(); ?> 