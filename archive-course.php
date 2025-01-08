<?php
/**
 * The template for displaying course archives
 *
 * @package SzkolaKademia
 */

get_header();
?>

<main class="py-12 bg-gray-50">
    <div class="container mx-auto px-4 max-w-7xl">
        <header class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-6"><?php esc_html_e('Available Courses', 'szkolakademia'); ?></h1>
            
            <!-- Course Filters -->
            <div class="flex flex-wrap gap-4 items-center">
                <?php
                $categories = get_terms([
                    'taxonomy' => 'course_category',
                    'hide_empty' => true,
                ]);
                if (!empty($categories) && !is_wp_error($categories)): ?>
                    <select class="form-select rounded-lg border-gray-300 py-2 px-4 pr-8 focus:border-blue-500 focus:ring-blue-500">
                        <option value=""><?php esc_html_e('All Categories', 'szkolakademia'); ?></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo esc_attr($category->slug); ?>">
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <?php 
                $levels = get_terms([
                    'taxonomy' => 'course_level',
                    'hide_empty' => true,
                ]);
                if (!empty($levels) && !is_wp_error($levels)): ?>
                    <select class="form-select rounded-lg border-gray-300 py-2 px-4 pr-8 focus:border-blue-500 focus:ring-blue-500">
                        <option value=""><?php esc_html_e('All Levels', 'szkolakademia'); ?></option>
                        <?php foreach ($levels as $level): ?>
                            <option value="<?php echo esc_attr($level->slug); ?>">
                                <?php echo esc_html($level->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </header>

        <?php if (have_posts()): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while (have_posts()): the_post(); 
                    $duration = get_post_meta(get_the_ID(), '_course_duration', true);
                    $instructor = get_post_meta(get_the_ID(), '_course_instructor', true);
                    $price = get_post_meta(get_the_ID(), '_course_price', true);
                ?>
                    <article <?php post_class('bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-1 hover:shadow-xl'); ?>>
                        <?php if (has_post_thumbnail()): ?>
                            <div class="aspect-w-16 aspect-h-9">
                                <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <!-- Categories -->
                            <?php
                            $categories = get_the_terms(get_the_ID(), 'course_category');
                            if ($categories && !is_wp_error($categories)): ?>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <?php foreach ($categories as $category): ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                            <?php echo esc_html($category->name); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Title -->
                            <h2 class="text-xl font-bold text-gray-900 mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <!-- Course Meta -->
                            <div class="space-y-2 mb-4">
                                <?php if ($instructor): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <?php echo esc_html($instructor); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($duration): ?>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php printf(esc_html__('%s hours', 'szkolakademia'), esc_html($duration)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($price): ?>
                                    <div class="flex items-center text-sm font-semibold text-gray-900">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php echo esc_html(number_format($price, 2)); ?> zł
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Excerpt -->
                            <div class="text-gray-600 text-sm mb-6">
                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                            </div>

                            <!-- Action Button -->
                            <a href="<?php the_permalink(); ?>" 
                               class="inline-block w-full text-center bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                                <?php esc_html_e('Learn More', 'szkolakademia'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                <?php
                echo paginate_links([
                    'prev_text' => '
                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            ' . esc_html__('Previous', 'szkolakademia') . '
                        </span>
                    ',
                    'next_text' => '
                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            ' . esc_html__('Next', 'szkolakademia') . '
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    ',
                ]);
                ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01M12 12h.01"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900"><?php esc_html_e('No courses found', 'szkolakademia'); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php esc_html_e('Get started by creating a new course.', 'szkolakademia'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?> 