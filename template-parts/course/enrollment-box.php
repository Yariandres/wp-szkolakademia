<?php
/**
 * Template part for displaying course enrollment options
 *
 * @package SzkolaKademia
 */

$course_id = get_the_ID();
$product_id = szkolakademia_get_course_product_id($course_id);
$has_access = szkolakademia_user_has_course_access($course_id);
?>

<div class="bg-white rounded-lg shadow p-6">
    <?php if ($has_access): ?>
        <!-- User has access -->
        <div class="text-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <?php esc_html_e('Enrolled', 'szkolakademia'); ?>
            </span>
            <a href="<?php echo esc_url(get_permalink() . 'lesson/'); ?>" 
               class="mt-4 w-full inline-block text-center bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                <?php esc_html_e('Start Learning', 'szkolakademia'); ?>
            </a>
        </div>
    <?php elseif ($product_id): ?>
        <!-- Course requires purchase -->
        <?php
        $product = wc_get_product($product_id);
        if ($product): ?>
            <div class="text-center">
                <div class="mb-4">
                    <span class="text-3xl font-bold text-gray-900">
                        <?php echo $product->get_price_html(); ?>
                    </span>
                </div>
                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" 
                   class="w-full inline-block text-center bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    <?php esc_html_e('Enroll Now', 'szkolakademia'); ?>
                </a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- No product linked -->
        <p class="text-gray-500 text-center">
            <?php esc_html_e('Enrollment not available', 'szkolakademia'); ?>
        </p>
    <?php endif; ?>
</div> 