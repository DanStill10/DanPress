<?php
// -----------------------------------------------------------------------------
// Template: page.php
// -----------------------------------------------------------------------------
// The template for displaying all individual static pages (e.g., /about, /contact).

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php
    // The main WordPress Loop
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
    ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>

    <?php
        endwhile;
    endif;
    ?>

</main>

<?php get_footer(); ?>