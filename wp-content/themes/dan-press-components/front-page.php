<?php
/**
 * Template: front-page.php
 *
 * Homepage — Hero (Customizer) + Gutenberg block content area.
 *
 * @package Dan Press
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php get_template_part( 'template-parts/hero' ); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="dsd-blocks-area">
            <?php the_content(); ?>
        </div>
    <?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
