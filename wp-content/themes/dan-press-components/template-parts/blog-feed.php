<?php
/**
 * Template Part: Blog Feed
 *
 * Displays recent blog posts in a card grid.
 * Uses WP_Query — no ACF fields needed.
 *
 * @package Dan Press
 */

$blog_args = array(
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
);

$blog_query = new WP_Query( $blog_args );
?>

<section class="dsd-blog-feed" id="blog">
    <div class="container dsd-blog-feed-inner">

        <div class="dsd-blog-feed-header">
            <div>
                <span class="dsd-blog-feed-badge">READ</span>
                <h2 class="dsd-section-heading">Our <span class="dsd-accent-word">Blog</span></h2>
                <p class="dsd-blog-feed-intro">News, articles, and updates from our team.</p>
            </div>

            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="dsd-btn dsd-btn--ghost">
                Browse All Articles
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
