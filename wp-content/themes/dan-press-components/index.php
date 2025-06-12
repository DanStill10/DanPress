<?php
// -----------------------------------------------------------------------------
// Template: index.php
// -----------------------------------------------------------------------------
// The ultimate fallback template. WordPress will use this if it can't find
// a more specific template file in the hierarchy. It's a good safety net.
// Its structure is often similar to archive.php.

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php if ( is_home() && ! is_front_page() ) : ?>
        <header>
            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <?php
    // The main WordPress Loop
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
    ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                </header>

                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div>
            </article>
    <?php
        endwhile;
    else :
        echo '<p>No content to display.</p>';
    endif;
    ?>

</main>

<?php get_footer(); ?>
