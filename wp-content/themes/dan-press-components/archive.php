<?php
// -----------------------------------------------------------------------------
// Template: archive.php
// -----------------------------------------------------------------------------
// Displays category/date/tag archives (e.g. /category/news/). These pages are
// already "pre-filtered" by WordPress, so unlike index.php there is no filter
// bar here — just the list styling shared with the blog index.

get_header(); ?>

<main id="main" class="site-main" role="main">

    <div class="container">

    <header class="dsd-archive-header">
        <?php the_archive_title( '<h1 class="dsd-section-heading">', '</h1>' ); ?>
        <?php the_archive_description( '<div class="dsd-archive-description">', '</div>' ); ?>
    </header>

    <?php
    // The main WordPress Loop
    if ( have_posts() ) :
    ?>
        <div class="dsd-archive-list">
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( 'dsd-archive-item' ); ?>>
                    <h2 class="dsd-archive-item__title">
                        <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                    </h2>

                    <span class="dsd-archive-item__author">By <?php the_author_posts_link(); ?></span>

                    <div class="dsd-archive-item__excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                </article>

            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination( array(
            'mid_size'           => 2,
            'screen_reader_text' => 'Post navigation',
        ) ); ?>

    <?php else : ?>
        <p class="dsd-archive-empty">No posts found.</p>
    <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
