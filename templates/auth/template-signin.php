<?php
/**
 * Template Name: Sign In Page
 *
 * @package SzkolaKademia
 */

// Redirect course users to dashboard
if (is_course_user()) {
    wp_redirect(home_url('/course/dashboard'));
    exit;
}

// TODO: commented out for now
// // Redirect admin users to wp-admin
// if (current_user_can('manage_options')) {
//     wp_redirect(admin_url());
//     exit;
// }

get_header();
?>

<main class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                <?php esc_html_e('Sign In', 'szkolakademia'); ?>
            </h1>

            <?php if (isset($_GET['login']) && $_GET['login'] == 'failed'): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                    <p><?php esc_html_e('Invalid username or password.', 'szkolakademia'); ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logged_out']) && $_GET['logged_out'] == 'true'): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6">
                    <p><?php esc_html_e('You have been logged out.', 'szkolakademia'); ?></p>
                </div>
            <?php endif; ?>

            <form action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post" class="space-y-6">
                <div>
                    <label for="user_login" class="block text-sm font-medium text-gray-700">
                        <?php esc_html_e('Username or Email', 'szkolakademia'); ?>
                    </label>
                    <input type="text" name="log" id="user_login" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="user_pass" class="block text-sm font-medium text-gray-700">
                        <?php esc_html_e('Password', 'szkolakademia'); ?>
                    </label>
                    <input type="password" name="pwd" id="user_pass" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="rememberme" id="rememberme"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="rememberme" class="ml-2 block text-sm text-gray-900">
                            <?php esc_html_e('Remember me', 'szkolakademia'); ?>
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" 
                           class="font-medium text-blue-600 hover:text-blue-500">
                            <?php esc_html_e('Forgot password?', 'szkolakademia'); ?>
                        </a>
                    </div>
                </div>

                <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/dashboard')); ?>">

                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?php esc_html_e('Sign in', 'szkolakademia'); ?>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                <?php esc_html_e('Need an account?', 'szkolakademia'); ?>
                <a href="<?php echo esc_url(home_url('/register')); ?>" 
                   class="font-medium text-blue-600 hover:text-blue-500">
                    <?php esc_html_e('Register', 'szkolakademia'); ?>
                </a>
            </p>
        </div>
    </div>
</main>

<?php get_footer(); ?>