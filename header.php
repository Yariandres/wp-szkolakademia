<?php
/**
 * The header template
 *
 * @package SzkolaKademia
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="bg-gray-800 text-white py-5">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <?php if (has_custom_logo()): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                <h1><a href="<?php echo esc_url(home_url('/')); ?>" class="text-xl font-bold"><?php bloginfo('name'); ?></a></h1>
            <?php endif; ?>

            <!-- Primary Navigation -->
            <nav class="hidden md:flex space-x-6">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'    => 'flex space-x-6',
                    'fallback_cb'   => false,
                ]);
                ?>
            </nav>

            <!-- User Navigation -->
            <div class="flex items-center space-x-4">
                <?php if (is_user_logged_in()): ?>
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-white hover:text-gray-200">
                            <span><?php echo esc_html(wp_get_current_user()->display_name); ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            
                            <a href="<?php echo esc_url(home_url('course/dashboard')); ?>" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <?php esc_html_e('Dashboard', 'szkolakademia'); ?>
                                </div>
                            </a>
                            
                            <a href="<?php echo esc_url(home_url('courses')); ?>" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <?php esc_html_e('Browse Courses', 'szkolakademia'); ?>
                                </div>
                            </a>
                            
                            <a href="<?php echo esc_url(home_url('profile')); ?>" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <?php esc_html_e('Profile', 'szkolakademia'); ?>
                                </div>
                            </a>
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" 
                               class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <?php esc_html_e('Logout', 'szkolakademia'); ?>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo esc_url(wp_login_url()); ?>" 
                       class="text-white hover:text-gray-200">
                        <?php esc_html_e('Login', 'szkolakademia'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_registration_url()); ?>" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <?php esc_html_e('Register', 'szkolakademia'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
