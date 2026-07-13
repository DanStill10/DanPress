<?php
/**
 * Theme functions and definitions
 *
 * @package Dan Press
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Register Composer Auto-Loader
|--------------------------------------------------------------------------
*/
if ( ! file_exists( $composer = __DIR__ . '/vendor/autoload.php' ) ) {
	wp_die( __( 'Error locating autoloader. Please run <code>composer install</code>.', 'dan-press' ) );
}
require $composer;

/**
 * Enqueue Google Fonts for the theme.
 */
function dan_press_enqueue_fonts() {
    wp_enqueue_style(
        'dan-press-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        [],
        null
    );
}
add_action( 'wp_enqueue_scripts', 'dan_press_enqueue_fonts' );


/*
|--------------------------------------------------------------------------
| Enqueue Theme-wide Frontend Assets (from Bud.js)
|--------------------------------------------------------------------------
|
| This function enqueues the main site styles, scripts, and Google Fonts
| for the front-end of the website. It handles both development and
| production environments.
|
*/
function danpress_enqueue_theme_assets() {
    // Step 1: Check if we are in development or production.
    // You can set define('WP_ENV', 'development'); in your wp-config.php.
    if ( defined( 'WP_ENV' ) && WP_ENV === 'development' ) {
        // --- DEVELOPMENT MODE ---
        // Load assets directly from the Bud dev server URL.
        $dev_url = 'http://localhost:3000'; // This should match the URL from `npx bud dev`

        wp_enqueue_style(
            'dan-press-app',
            "{$dev_url}/app.css",
            ['dan-press-google-fonts'],
            null
        );

    } else {
        // --- PRODUCTION MODE ---
        // Load assets from the /public directory using the manifest.
        $dist_uri      = get_template_directory_uri() . '/public';
        $manifest_path = get_template_directory() . '/public/entrypoints.json';

        if ( file_exists( $manifest_path ) ) {
            $manifest = json_decode( file_get_contents( $manifest_path ), true );

            // Enqueue the main stylesheet from the manifest.
            if ( ! empty( $manifest['app']['css'] ) ) {
                foreach ( $manifest['app']['css'] as $css_file ) {
                    wp_enqueue_style(
                        'dan-press-app',
                        "{$dist_uri}/{$css_file}",
                        ['dan-press-google-fonts'],
                        null
                    );
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'danpress_enqueue_theme_assets' );

function dan_press_register_nav_menu() {
    register_nav_menu( 'primary_menu', __( 'Primary Menu', 'dan-press' ) );
}
add_action( 'after_setup_theme', 'dan_press_register_nav_menu' );
