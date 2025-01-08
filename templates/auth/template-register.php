<?php
/**
 * Template Name: Register
 *
 * @package SzkolaKademia
 */

get_header();
?>

<main class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                <?php esc_html_e('Create Account', 'szkolakademia'); ?>
            </h1>

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="space-y-6">
                <input type="hidden" name="action" value="szkolakademia_register">
                <?php wp_nonce_field('registration_nonce', 'registration_nonce'); ?>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        <?php esc_html_e('Username', 'szkolakademia'); ?>
                    </label>
                    <input type="text" name="username" id="username" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        <?php esc_html_e('Email', 'szkolakademia'); ?>
                    </label>
                    <input type="email" name="email" id="email" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        <?php esc_html_e('Password', 'szkolakademia'); ?>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700">
                        <?php esc_html_e('Confirm Password', 'szkolakademia'); ?>
                    </label>
                    <input type="password" name="password_confirm" id="password_confirm" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?php esc_html_e('Register', 'szkolakademia'); ?>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                <?php esc_html_e('Already have an account?', 'szkolakademia'); ?>
                <a href="<?php echo esc_url(home_url('/auth/signin')); ?>" 
                   class="font-medium text-blue-600 hover:text-blue-500">
                    <?php esc_html_e('Sign in', 'szkolakademia'); ?>
                </a>
            </p>
        </div>
    </div>
</main>

<?php get_footer(); ?>