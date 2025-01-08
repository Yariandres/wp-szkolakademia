<?php
/**
 * The footer template
 *
 * @package SzkolaKademia
 */
?>
<footer class="bg-gray-800 text-white py-10 text-center">
        <div class="container">
            <!-- Footer Navigation -->
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'    => 'footer-menu',
                'fallback_cb'   => false,
            ]);
            ?>
            
            <div class="footer-info">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
