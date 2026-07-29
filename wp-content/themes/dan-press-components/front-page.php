<?php
/**
 * Template: front-page.php
 *
 * Homepage layout — composes all sections via template parts and blocks.
 * Individual components are built on their own feature branches.
 *
 * @package Dan Press
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php
    // Hero Section (feature/hero-section — already built)
    get_template_part( 'template-parts/hero' );
    ?>

    <?php
    // Client Logos Marquee (feature/client-logos)
    get_template_part( 'template-parts/client-logos' );
    ?>

    <?php
    // Services Grid (feature/services-grid)
    get_template_part( 'template-parts/services' );
    ?>

    <?php
    // About / Why Partner With Us (feature/about-section)
    get_template_part( 'template-parts/about' );
    ?>

    <?php
    // Testimonials / Ratings (feature/testimonials)
    get_template_part( 'template-parts/testimonials' );
    ?>

    <?php
    // Three-Column Value Propositions (feature/value-props)
    get_template_part( 'template-parts/value-props' );
    ?>

    <?php
    // Blog Feed (feature/blog-feed)
    get_template_part( 'template-parts/blog-feed' );
    ?>

    <?php
    // FAQs (feature/faqs)
    get_template_part( 'template-parts/faqs' );
    ?>

    <?php
    // CTA / Request a Quote (feature/cta-section)
    get_template_part( 'template-parts/cta' );
    ?>

</main>

<?php get_footer(); ?>
