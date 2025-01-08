<?php
/**
 * Single course template
 */

get_header();
?>

<div class="container mx-auto px-4 py-8">
    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-sm p-6'); ?>>
        <header class="entry-header mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                <?php the_title(); ?>
            </h1>
        </header>

        <div class="entry-content prose max-w-none mb-6">
            <?php the_content(); ?>
        </div>

        <?php
        $user_id = get_current_user_id();
        $course_id = get_the_ID();
        
        if (!$user_id) {
            // User is not logged in
            ?>
            <div class="bg-gray-50 p-4 rounded-md">
                <p class="text-gray-700 mb-4"><?php esc_html_e('Please log in to enroll in this course.', 'szkolakademia'); ?></p>
                <a href="<?php echo esc_url(home_url('/auth/signin')); ?>" 
                   class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    <?php esc_html_e('Log In', 'szkolakademia'); ?>
                </a>
            </div>
            <?php
        } elseif (szkolakademia_is_user_enrolled($user_id, $course_id)) {
            // User is enrolled
            ?>
            <div class="bg-green-50 p-4 rounded-md">
                <p class="text-green-700 mb-4"><?php esc_html_e('You are enrolled in this course.', 'szkolakademia'); ?></p>
                <div class="flex gap-4">
                    <a href="<?php echo esc_url(add_query_arg(['course_id' => $course_id], home_url('/course/player/'))); ?>" 
                       class="inline-flex items-center justify-center bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <?php esc_html_e('Start Learning', 'szkolakademia'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/course/dashboard')); ?>" 
                       class="inline-flex items-center justify-center border border-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <?php esc_html_e('View Dashboard', 'szkolakademia'); ?>
                    </a>
                </div>
            </div>
            <?php
        } else {
            // User can enroll
            ?>
            <div class="bg-blue-50 p-4 rounded-md">
                <p class="text-blue-700 mb-4"><?php esc_html_e('Enroll now to start learning!', 'szkolakademia'); ?></p>
                <button type="button" 
                        class="enroll-button inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700"
                        data-course-id="<?php echo esc_attr($course_id); ?>"
                        data-nonce="<?php echo esc_attr(wp_create_nonce('enroll_course')); ?>">
                    <?php esc_html_e('Enroll Now', 'szkolakademia'); ?>
                </button>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const enrollButton = document.querySelector('.enroll-button');
                if (enrollButton) {
                    enrollButton.addEventListener('click', function() {
                        const courseId = this.dataset.courseId;
                        const nonce = this.dataset.nonce;
                        
                        // Disable button
                        this.disabled = true;
                        this.textContent = '<?php esc_html_e('Enrolling...', 'szkolakademia'); ?>';
                        
                        // Send AJAX request
                        fetch(ajaxurl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({
                                action: 'enroll_course',
                                course_id: courseId,
                                nonce: nonce
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = data.data.redirect_url;
                            } else {
                                alert(data.data);
                                this.disabled = false;
                                this.textContent = '<?php esc_html_e('Enroll Now', 'szkolakademia'); ?>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('<?php esc_html_e('An error occurred. Please try again.', 'szkolakademia'); ?>');
                            this.disabled = false;
                            this.textContent = '<?php esc_html_e('Enroll Now', 'szkolakademia'); ?>';
                        });
                    });
                }
            });
            </script>
            <?php
        }
        ?>
    </article>
</div>

<?php
get_footer(); ?> 