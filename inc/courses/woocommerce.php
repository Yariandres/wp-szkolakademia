<?php
/**
 * WooCommerce integration for courses
 *
 * @package SzkolaKademia
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add course product meta box
 */
function szkolakademia_add_course_product_meta_box() {
    add_meta_box(
        'course_product',
        __('Course Product', 'szkolakademia'),
        'szkolakademia_course_product_callback',
        'course',
        'side'
    );
}
add_action('add_meta_boxes', 'szkolakademia_add_course_product_meta_box');

/**
 * Course product meta box callback
 */
function szkolakademia_course_product_callback($post) {
    wp_nonce_field('course_product_nonce', 'course_product_nonce');
    
    $linked_product_id = get_post_meta($post->ID, '_linked_product_id', true);
    
    // Get all products
    $products = wc_get_products([
        'status' => 'publish',
        'limit' => -1,
        'return' => 'ids',
    ]);
    ?>
    <p>
        <label for="linked_product_id"><?php _e('Associated Product:', 'szkolakademia'); ?></label>
        <select name="linked_product_id" id="linked_product_id" class="widefat">
            <option value=""><?php _e('Select a product', 'szkolakademia'); ?></option>
            <?php foreach ($products as $product_id): 
                $product = wc_get_product($product_id);
                if ($product): ?>
                    <option value="<?php echo esc_attr($product_id); ?>" <?php selected($linked_product_id, $product_id); ?>>
                        <?php echo esc_html($product->get_name()) . ' (' . $product->get_price_html() . ')'; ?>
                    </option>
                <?php endif;
            endforeach; ?>
        </select>
    </p>
    <p>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>" target="_blank" class="button">
            <?php _e('Create New Product', 'szkolakademia'); ?>
        </a>
    </p>
    <?php
}

/**
 * Save course product meta
 */
function szkolakademia_save_course_product_meta($post_id) {
    if (!isset($_POST['course_product_nonce']) || 
        !wp_verify_nonce($_POST['course_product_nonce'], 'course_product_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['linked_product_id'])) {
        update_post_meta($post_id, '_linked_product_id', sanitize_text_field($_POST['linked_product_id']));
    }
}
add_action('save_post_course', 'szkolakademia_save_course_product_meta');

/**
 * Add course access after purchase
 */
function szkolakademia_grant_course_access($order_id) {
    $order = wc_get_order($order_id);
    
    if (!$order) {
        return;
    }

    $user_id = $order->get_user_id();
    
    if (!$user_id) {
        return;
    }

    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        
        // Find course linked to this product
        $args = array(
            'post_type' => 'course',
            'meta_key' => '_linked_product_id',
            'meta_value' => $product_id,
            'posts_per_page' => 1
        );
        
        $courses = get_posts($args);
        
        if (!empty($courses)) {
            $course_id = $courses[0]->ID;
            // Add user access to course
            add_user_meta($user_id, '_enrolled_course_' . $course_id, current_time('mysql'));
            
            // Optional: Add order note
            $order->add_order_note(
                sprintf(
                    __('User #%d granted access to course #%d', 'szkolakademia'),
                    $user_id,
                    $course_id
                )
            );
        }
    }
}
add_action('woocommerce_payment_complete', 'szkolakademia_grant_course_access');

/**
 * Check if user has access to course
 */
function szkolakademia_user_has_course_access($course_id, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    return (bool) get_user_meta($user_id, '_enrolled_course_' . $course_id, true);
}

/**
 * Get course product ID
 */
function szkolakademia_get_course_product_id($course_id) {
    return get_post_meta($course_id, '_linked_product_id', true);
}

/**
 * Get course price
 */
function szkolakademia_get_course_price($course_id) {
    $product_id = szkolakademia_get_course_product_id($course_id);
    if ($product_id) {
        $product = wc_get_product($product_id);
        if ($product) {
            return $product->get_price();
        }
    }
    return false;
} 