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


/*
|--------------------------------------------------------------------------
| Block Registration and Asset Enqueueing
|--------------------------------------------------------------------------
|
| This is the main function that handles all custom block logic.
| It performs two critical steps:
| 1. Registers the block type on the server using block.json.
| 2. Enqueues the compiled JavaScript for the editor.
|
*/
function dan_press_block_init() {
	// Step 1: Automatically register all blocks in the /blocks/ directory.
	$block_folders = glob( get_template_directory() . '/blocks/*', GLOB_ONLYDIR );
	if ( $block_folders ) {
		foreach ( $block_folders as $block_folder ) {
			register_block_type( $block_folder );
		}
	}

	// Step 2: Enqueue the single, compiled JavaScript file for the editor.
	// We use the .asset.php file to automatically handle dependencies and versioning.
	$asset_file_path = get_template_directory() . '/build/index.asset.php';
	if ( file_exists( $asset_file_path ) ) {
		$asset_file = require $asset_file_path;

		wp_enqueue_script(
			'dan-press-blocks-editor', // A unique handle for the editor script.
			get_template_directory_uri() . '/build/index.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true // Load in footer.
		);
	}
}
// We run everything on the 'init' hook for simplicity and reliability.
add_action( 'init', 'dan_press_block_init' );


/*
|--------------------------------------------------------------------------
| Enqueue Theme-wide Frontend Assets (from Bud.js)
|--------------------------------------------------------------------------
|
| This function enqueues the main site styles and scripts for the
| front-end of the website.
|
*/
function danpress_enqueue_theme_assets() {
	// This function can be filled out later to load your main app.css
	// from your Bud.js build process (`npx bud build`).
}
add_action( 'wp_enqueue_scripts', 'danpress_enqueue_theme_assets' );

