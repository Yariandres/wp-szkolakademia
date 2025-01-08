<?php
/**
 * The main template file
 *
 * @package SzkolaKademia
 */

get_header();
?>

<main class="site-main">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php esc_html_e('Transform Your Future with Online Learning', 'szkolakademia'); ?>
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    <?php esc_html_e('Access high-quality courses and start learning today.', 'szkolakademia'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(home_url('/courses')); ?>" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                        <?php esc_html_e('Browse Courses', 'szkolakademia'); ?>
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <?php if (!is_user_logged_in()): ?>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 transition-colors">
                            <?php esc_html_e('Sign Up Free', 'szkolakademia'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Featured Courses', 'szkolakademia'); ?>
                </h2>
                <p class="text-xl text-gray-600">
                    <?php esc_html_e('Start your learning journey with our most popular courses', 'szkolakademia'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $featured_courses = new WP_Query([
                    'post_type' => 'course',
                    'posts_per_page' => 6,
                    'meta_key' => 'featured_course',
                    'meta_value' => '1'
                ]);

                if ($featured_courses->have_posts()) :
                    while ($featured_courses->have_posts()) : $featured_courses->the_post();
                    ?>
                        <article class="bg-white rounded-lg shadow-sm overflow-hidden transition-transform hover:-translate-y-1">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="aspect-w-16 aspect-h-9">
                                    <?php the_post_thumbnail('medium_large', ['class' => 'object-cover w-full h-full']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-blue-600">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <div class="text-gray-600 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700">
                                    <?php esc_html_e('Learn More', 'szkolakademia'); ?>
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('Why Choose Us', 'szkolakademia'); ?>
                </h2>
                <p class="text-xl text-gray-600">
                    <?php esc_html_e('Discover what makes our platform unique', 'szkolakademia'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Expert Instructors', 'szkolakademia'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Learn from industry professionals with real-world experience.', 'szkolakademia'); ?>
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Self-Paced Learning', 'szkolakademia'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Study at your own pace and on your own schedule.', 'szkolakademia'); ?>
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        <?php esc_html_e('Lifetime Access', 'szkolakademia'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php esc_html_e('Get unlimited access to your courses after enrollment.', 'szkolakademia'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Ready to Start Learning?', 'szkolakademia'); ?>
                </h2>
                <p class="text-xl mb-8 text-blue-100">
                    <?php esc_html_e('Join thousands of students already learning on our platform.', 'szkolakademia'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/courses')); ?>" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                    <?php esc_html_e('Get Started Today', 'szkolakademia'); ?>
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
