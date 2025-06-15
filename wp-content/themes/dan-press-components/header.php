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
    <?php wp_head(); // CRITICAL: WordPress hook for plugins and styles ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); // Important hook for accessibility and plugins ?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'dan-press' ); ?></a>

<header id="masthead" class="site-header">
    <div class="container site-header-inner">

        <div class="site-branding">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title">
                Stillbuilt
            </a>
            <p class="site-description">From complex problems to elegant solutions.</p>
        </div><!-- .site-branding -->

        <nav id="site-navigation" class="main-navigation">
            <?php
            // This will display your primary navigation menu.
            // Go to Appearance > Menus in your WordPress admin to create it.
            wp_nav_menu(
                array(
                    'theme_location' => 'primary_menu', // You'll need to register this location.
                    'menu_id'        => 'primary-menu',
                )
            );
            ?>
        </nav><!-- #site-navigation -->

    </div><!-- .site-header-inner -->
</header><!-- #masthead -->

<div id="content" class="site-content">
