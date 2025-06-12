<?php
// -----------------------------------------------------------------------------
// Template: archive.php
// -----------------------------------------------------------------------------
// The template for displaying archives of posts (e.g., by category, tag, or date).

get_header(); ?>

<main id="main" class="site-main" role="main">

    <header class="page-header">
        <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
        <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
    </header>

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
                    <?php the_excerpt(); // Use the_excerpt() for archive views ?>
                </div>
            </article>
    <?php
        endwhile;
    else :
        echo '<p>No posts found.</p>';
    endif;
    ?>

</main>

<?php get_footer(); ?>