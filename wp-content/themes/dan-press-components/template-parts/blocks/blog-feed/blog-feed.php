<?php
/**
 * Block: Blog Feed
 *
 * Displays recent blog posts in a card grid.
 * Uses WP_Query — content settings via ACF.
 *
 * @package Dan Press
 */

$heading    = get_field( 'blog_feed_heading' ) ?: 'Our [accent]Blog[/accent]';
$intro      = get_field( 'blog_feed_intro' ) ?: 'News, articles, and updates from our team.';
$btn_text   = get_field( 'blog_feed_btn_text' ) ?: 'Browse All Articles';
$btn_url    = get_field( 'blog_feed_btn_url' );
$post_count = (int) get_field( 'blog_feed_count' );

if ( $post_count < 1 ) {
    $post_count = 3;
}

$heading_html = preg_replace(
    '/\[accent\](.*?)\[\/accent\]/',
    '<span class="dsd-accent-word">$1</span>',
    esc_html( $heading )
);

if ( ! $btn_url ) {
    $btn_url = get_permalink( get_option( 'page_for_posts' ) );
    if ( ! $btn_url ) {
        $btn_url = home_url( '/blog/' );
    }
}

$selected_posts = (array) get_field( 'blog_feed_posts' );

if ( $selected_posts ) {
    // Curated mode: render selected posts in the admin's chosen order.
    $blog_args = array(
        'post__in'            => array_map( 'absint', $selected_posts ),
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'orderby'             => 'post__in',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
    );
} else {
    // Fallback mode: most recent published posts.
    $blog_args = array(
        'posts_per_page'      => $post_count,
        'post_status'         => 'publish',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
    );
}

$blog_query = new WP_Query( $blog_args );
?>

<section <?php echo get_block_wrapper_attributes( array( 'class' => 'dsd-blog-feed dsd-block' ) ); ?>>
    <div class="container dsd-blog-feed-inner">

        <div class="dsd-blog-feed-header">
            <div>
                <h2 class="dsd-section-heading"><?php echo $heading_html; ?></h2>
                <p class="dsd-blog-feed-intro"><?php echo esc_html( $intro ); ?></p>
            </div>

            <a href="<?php echo esc_url( $btn_url ); ?>" class="dsd-btn dsd-btn--ghost">
                <?php echo esc_html( $btn_text ); ?>
            </a>
        </div>

        <?php if ( $blog_query->have_posts() ) : ?>
            <div class="dsd-blog-grid">
                <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>

                    <article class="dsd-blog-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="dsd-blog-card__image">
                                <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="dsd-blog-card__content">
                            <?php
                            $categories = get_the_category();
                            if ( $categories ) :
                            ?>
                                <div class="dsd-blog-card__cats">
                                    <?php foreach ( array_slice( $categories, 0, 3 ) as $cat ) : ?>
                                        <span class="dsd-blog-card__cat"><?php echo esc_html( $cat->name ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h3 class="dsd-blog-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        </div>
                    </article>

                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
</section>
