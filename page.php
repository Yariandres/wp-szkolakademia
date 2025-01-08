<?php
/**
 * The default page template
 *
 * @package SzkolaKademia
 */

get_header();
?>

<main class="site-main">
    <div class="container">
        <?php while (have_posts()): the_post(); ?>
            <article <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
                    <?php 
                    the_content();
                    wp_link_pages([
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'szkolakademia'),
                        'after'  => '</div>',
                    ]);
                    ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
