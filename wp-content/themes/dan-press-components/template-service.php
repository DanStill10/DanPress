<?php
/**
 * Template Name: Service Detail
 *
 * Detail page template for the Services grid's CTA cards. Wraps the
 * standard content editor with a styled hero (eyebrow, title, subtitle,
 * featured image) and a closing CTA, so these pages read as an
 * intentional part of the site rather than a bare WordPress Page.
 *
 * @package Dan Press
 */

get_header();

while ( have_posts() ) :
    the_post();

    $eyebrow  = get_field( 'service_eyebrow' );
    $subtitle = get_field( 'service_subtitle' );

    $cta_heading  = get_theme_mod( 'cta_heading', 'Get In Touch' );
    $cta_subtitle = get_theme_mod( 'cta_subtitle', 'Have a project in mind? Let\'s talk about it.' );
    $cta_btn_text = get_theme_mod( 'header_contact_text', 'Get In Touch' );
    $cta_btn_url  = get_theme_mod( 'header_contact_url', '#contact' );
    ?>

    <main id="main" class="site-main" role="main">

        <header class="dsd-service-hero">
            <div class="container dsd-service-hero__inner">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="dsd-service-hero__back">&larr; Back to Home</a>

                <?php if ( $eyebrow ) : ?>
                    <span class="dsd-service-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
                <?php endif; ?>

                <h1 class="dsd-service-hero__title"><?php the_title(); ?></h1>

                <?php if ( $subtitle ) : ?>
                    <p class="dsd-service-hero__subtitle"><?php echo esc_html( $subtitle ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="dsd-service-hero__image container">
                    <?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?>
                </div>
            <?php endif; ?>
        </header>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'dsd-service-article' ); ?>>
            <div class="container">
                <div class="dsd-service-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </article>

        <section class="dsd-cta dsd-block">
            <div class="container">
                <div class="dsd-cta-inner">
                    <h2 class="dsd-cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>

                    <?php if ( $cta_subtitle ) : ?>
                        <p class="dsd-cta-subtitle"><?php echo esc_html( $cta_subtitle ); ?></p>
                    <?php endif; ?>

                    <a href="<?php echo esc_url( $cta_btn_url ); ?>" class="dsd-btn dsd-btn--primary">
                        <?php echo esc_html( $cta_btn_text ); ?>
                    </a>
                </div>
            </div>
        </section>

    </main>

<?php
endwhile;

get_footer();
