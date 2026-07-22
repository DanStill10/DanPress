<?php
/**
 * Template: front-page.php
 *
 * Homepage layout — composes all sections via template parts.
 *
 * @package Dan Press
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php get_template_part( 'template-parts/hero' ); ?>
    <?php get_template_part( 'template-parts/client-logos' ); ?>
    <?php get_template_part( 'template-parts/services' ); ?>
    <?php get_template_part( 'template-parts/about' ); ?>
    <?php get_template_part( 'template-parts/testimonials' ); ?>
    <?php get_template_part( 'template-parts/value-props' ); ?>
    <?php get_template_part( 'template-parts/blog-feed' ); ?>
    <?php get_template_part( 'template-parts/faqs' ); ?>
    <?php get_template_part( 'template-parts/cta' ); ?>

</main>

<?php get_footer(); ?>
