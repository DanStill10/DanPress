<?php
/**
 * Theme functions and definitions
 *
 * @package Dan Press
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if ( ! file_exists( $composer = __DIR__ . '/vendor/autoload.php' ) ) {
	wp_die( __( 'Error locating autoloader. Please run <code>composer install</code>.', 'dan-press' ) );
}

require $composer;

/*
|--------------------------------------------------------------------------
| Theme Setup
|--------------------------------------------------------------------------
|
| This is where you can register theme support, menus, and other features.
|
*/
// Example: add_theme_support('title-tag');


/*
|--------------------------------------------------------------------------
| Enqueue Theme Assets
|--------------------------------------------------------------------------
|
| This function enqueues the theme's main stylesheets and scripts
| that are compiled by Bud.js.
|
*/
function danpress_enqueue_assets() {
	// Default to production if WP_ENV is not set.
	if ( ! defined( 'WP_ENV' ) ) {
		define( 'WP_ENV', 'production' );
	}

	$theme_uri = get_template_directory_uri();
	$dist_uri  = $theme_uri . '/public';

	// Handle Development vs. Production assets.
	if ( WP_ENV === 'development' && defined( 'BUD_DEV_URL' ) ) {
		// Development: Load assets from the Bud dev server.
		$dist_uri = BUD_DEV_URL . '/public';
		wp_enqueue_style( 'danpress-app', "{$dist_uri}/css/app.css", [], null );
		wp_enqueue_script( 'danpress-app', "{$dist_uri}/js/app.js", [], null, true );

	} else {
		// Production: Load assets from the manifest file.
		$manifest_path = get_template_directory() . '/public/entrypoints.json';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );

			// Enqueue CSS files from the manifest.
			if ( ! empty( $manifest['app']['css'] ) ) {
				foreach ( $manifest['app']['css'] as $css_file ) {
					wp_enqueue_style( 'danpress-' . pathinfo( $css_file, PATHINFO_FILENAME ), "{$dist_uri}/{$css_file}", [], null );
				}
			}

			// Enqueue JS files from the manifest.
			if ( ! empty( $manifest['app']['js'] ) ) {
				foreach ( $manifest['app']['js'] as $js_file ) {
					wp_enqueue_script( 'danpress-' . pathinfo( $js_file, PATHINFO_FILENAME ), "{$dist_uri}/{$js_file}", [], null, true );
				}
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'danpress_enqueue_assets' );


/*
|--------------------------------------------------------------------------
| Register Custom Blocks
|--------------------------------------------------------------------------
|
| This function automatically finds and registers all custom blocks
| located in the theme's /blocks/ directory.
|
*/
function dan_press_register_blocks() {
	$block_folders = glob( get_template_directory() . '/blocks/*', GLOB_ONLYDIR );

	if ( $block_folders ) {
		foreach ( $block_folders as $block_folder ) {
			register_block_type( $block_folder );
		}
	}
}
add_action( 'init', 'dan_press_register_blocks' );

// Note: You can define BUD_DEV_URL and WP_ENV in your wp-config.php for local development
// define('WP_ENV', 'development');
// define('BUD_DEV_URL', 'http://localhost:3000'); // Or whatever port you use.

