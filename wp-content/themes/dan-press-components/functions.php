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

        wp_enqueue_script(
            'dan-press-app',
            "{$dev_url}/app.js",
            [],
            null,
            true
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

            // Enqueue the main script from the manifest.
            if ( ! empty( $manifest['app']['js'] ) ) {
                foreach ( $manifest['app']['js'] as $js_file ) {
                    wp_enqueue_script(
                        'dan-press-app',
                        "{$dist_uri}/{$js_file}",
                        [],
                        null,
                        true
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

/*
|--------------------------------------------------------------------------
| Customizer: Hero Section Fields
|--------------------------------------------------------------------------
*/
function dsd_hero_customize_register( $wp_customize ) {

    // Panel
    $wp_customize->add_panel( 'dsd_hero_panel', array(
        'title'    => __( 'Hero Section', 'dan-press' ),
        'priority' => 30,
    ) );

    // Section
    $wp_customize->add_section( 'dsd_hero_section', array(
        'title' => __( 'Hero Content', 'dan-press' ),
        'panel' => 'dsd_hero_panel',
    ) );

    // --- Cycling Words ---
    $wp_customize->add_setting( 'hero_words', array(
        'default'           => 'Solve, Build, Ship',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_words', array(
        'label'   => __( 'Cycling Words (comma-separated)', 'dan-press' ),
        'section' => 'dsd_hero_section',
        'type'    => 'text',
    ) );

    // --- Subtitle ---
    $wp_customize->add_setting( 'hero_subtitle', array(
        'default'           => 'From complex problems to elegant solutions.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_subtitle', array(
        'label'   => __( 'Subtitle', 'dan-press' ),
        'section' => 'dsd_hero_section',
        'type'    => 'text',
    ) );

    // --- Primary CTA ---
    $wp_customize->add_setting( 'hero_cta_primary_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_cta_primary_text', array(
        'label'   => __( 'Primary Button Text', 'dan-press' ),
        'section' => 'dsd_hero_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'hero_cta_primary_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_cta_primary_url', array(
        'label'   => __( 'Primary Button URL', 'dan-press' ),
        'section' => 'dsd_hero_section',
        'type'    => 'url',
    ) );

    // --- Secondary CTA ---
    $wp_customize->add_setting( 'hero_cta_secondary_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'hero_cta_secondary_text', array(
        'label'   => __( 'Secondary Button Text', 'dan-press' ),
        'section' => 'dsd_hero_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'hero_cta_secondary_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_cta_secondary_url', array(
        'label'   => __( 'Secondary Button URL', 'dan-press' ),
        'section' => 'dsd_hero_section',
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'dsd_hero_customize_register' );

/*
|--------------------------------------------------------------------------
| Customizer: CTA Section Fields
|--------------------------------------------------------------------------
*/
function dsd_cta_customize_register( $wp_customize ) {

    // Panel
    $wp_customize->add_panel( 'dsd_cta_panel', array(
        'title'    => __( 'CTA Section', 'dan-press' ),
        'priority' => 31,
    ) );

    // Section
    $wp_customize->add_section( 'dsd_cta_section', array(
        'title' => __( 'CTA Content', 'dan-press' ),
        'panel' => 'dsd_cta_panel',
    ) );

    // --- Heading ---
    $wp_customize->add_setting( 'cta_heading', array(
        'default'           => 'Get In Touch',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'cta_heading', array(
        'label'   => __( 'Heading', 'dan-press' ),
        'section' => 'dsd_cta_section',
        'type'    => 'text',
    ) );

    // --- Subtitle ---
    $wp_customize->add_setting( 'cta_subtitle', array(
        'default'           => 'Have a project in mind? Let\'s talk about it.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'cta_subtitle', array(
        'label'   => __( 'Subtitle', 'dan-press' ),
        'section' => 'dsd_cta_section',
        'type'    => 'text',
    ) );

    // --- Button Text ---
    $wp_customize->add_setting( 'cta_btn_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'cta_btn_text', array(
        'label'   => __( 'Button Text', 'dan-press' ),
        'section' => 'dsd_cta_section',
        'type'    => 'text',
    ) );

    // --- Button URL ---
    $wp_customize->add_setting( 'cta_btn_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'cta_btn_url', array(
        'label'   => __( 'Button URL', 'dan-press' ),
        'section' => 'dsd_cta_section',
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'dsd_cta_customize_register' );
