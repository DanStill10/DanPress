<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until the <div id="content">
 *
 * @package Dan Press
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'dan-press' ); ?></a>

<header id="masthead" class="site-header">
    <div class="container site-header-inner">

        <div class="site-branding">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title">
                Stillbuilt
            </a>
            <p class="site-description">From complex problems to elegant solutions.</p>
        </div><!-- .site-branding -->

        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="hamburger-icon"></span>
            <span class="screen-reader-text">Menu</span>
        </button>

        <?php
        $contact_btn_text = get_theme_mod( 'header_contact_text', 'Get In Touch' );
        $contact_btn_url  = get_theme_mod( 'header_contact_url', '#contact' );
        ?>
        <nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary_menu',
                    'menu_id'        => 'primary-menu',
                )
            );
            ?>
            <?php if ( $contact_btn_text ) : ?>
                <a href="<?php echo esc_url( $contact_btn_url ); ?>" class="dsd-header-cta"><?php echo esc_html( $contact_btn_text ); ?></a>
            <?php endif; ?>
        </nav><!-- #site-navigation -->

        <?php if ( $contact_btn_text ) : ?>
            <a href="<?php echo esc_url( $contact_btn_url ); ?>" class="dsd-header-cta dsd-header-cta--mobile"><?php echo esc_html( $contact_btn_text ); ?></a>
        <?php endif; ?>

    </div><!-- .site-header-inner -->
</header><!-- #masthead -->

<div id="content" class="site-content">
