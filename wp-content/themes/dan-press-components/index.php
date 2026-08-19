<?php
// -----------------------------------------------------------------------------
// Template: index.php
// -----------------------------------------------------------------------------
// Renders the blog posts page (set via Settings > Reading > "Posts page").
// Styled as a list: title + author + excerpt, no dates.
//
// CATEGORY FILTER — HOW IT WORKS (read before editing)
// -----------------------------------------------------------------------------
// 1. The filter bar below is a <form method="get"> made of submit buttons.
//    Clicking a button sends name=blog_cat with that button's value as the
//    query string, e.g. /blog/?blog_cat=news. The "All" button sends an empty
//    value, which clears the filter. No JavaScript required.
// 2. That query param must be applied to WordPress's MAIN query. The main
//    query has already executed by the time this template loads, so the hook
//    CANNOT be registered here — it lives in functions.php via pre_get_posts
//    (see dsd_blog_apply_category_filter). It only fires on the posts page
//    (is_home() && ! is_front_page()) and only for the main query.
// 3. The category slug is validated against a real term in functions.php
//    before it touches the query, so junk input never reaches SQL.
// 4. Active-state styling below compares the current $_GET['blog_cat'] with
//    each button's value, so the pressed filter stays highlighted after load.
// 5. the_posts_pagination() below preserves the existing query string, so
//    pagination links automatically carry the active filter (page 2 of a
//    filtered list still shows that filter). If you replace pagination,
//    you must re-append blog_cat yourself or filters break across pages.
// 6. Categories are listed with hide_empty so the bar only shows categories
//    that actually contain published posts.

get_header(); ?>

<main id="main" class="site-main" role="main">

    <div class="container">

    <?php if ( is_home() && ! is_front_page() ) : ?>
        <header class="dsd-archive-header">
            <h1 class="dsd-section-heading"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <?php
    $current_cat = isset( $_GET['blog_cat'] ) ? sanitize_title( wp_unslash( $_GET['blog_cat'] ) ) : '';
    $categories  = get_categories( array(
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    if ( $categories ) :
    ?>
        <form class="dsd-archive-filters" method="get" action="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">
            <button type="submit" name="blog_cat" value="" class="dsd-archive-filter<?php echo $current_cat ? '' : ' is-active'; ?>">
                All
            </button>
            <?php foreach ( $categories as $cat ) : ?>
                <button type="submit" name="blog_cat" value="<?php echo esc_attr( $cat->slug ); ?>" class="dsd-archive-filter<?php echo $current_cat === $cat->slug ? ' is-active' : ''; ?>">
                    <?php echo esc_html( $cat->name ); ?>
                </button>
            <?php endforeach; ?>
        </form>
    <?php endif; ?>

    <?php
    // The main WordPress Loop (already filtered by blog_cat via pre_get_posts)
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
        <p class="dsd-archive-empty"><?php echo $current_cat ? 'No posts match this category yet.' : 'No posts yet.'; ?></p>
    <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
