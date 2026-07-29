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
| Theme Support
|--------------------------------------------------------------------------
*/
function dsd_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ) );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 1200;
    }
}
add_action( 'after_setup_theme', 'dsd_theme_setup' );

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
                foreach ( $manifest['app']['js'] as $index => $js_file ) {
                    $handle = 'dan-press-app-' . $index;
                    $deps   = $index > 0 ? array( 'dan-press-app-' . ( $index - 1 ) ) : array();
                    wp_enqueue_script(
                        $handle,
                        "{$dist_uri}/{$js_file}",
                        $deps,
                        null,
                        true
                    );
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'danpress_enqueue_theme_assets' );

/*
|--------------------------------------------------------------------------
| Enqueue Block Editor Assets
|--------------------------------------------------------------------------
*/
function dsd_enqueue_block_editor_assets() {
    $asset_file = get_theme_file_path( 'build/index.asset.php' );
    if ( ! file_exists( $asset_file ) ) {
        return;
    }
    $asset = require $asset_file;

    wp_enqueue_script(
        'dsd-blocks',
        get_theme_file_uri( 'build/index.js' ),
        $asset['dependencies'],
        $asset['version'],
        true
    );

    $editor_css = get_theme_file_path( 'build/editor.css' );
    if ( file_exists( $editor_css ) ) {
        wp_enqueue_style(
            'dsd-blocks-editor',
            get_theme_file_uri( 'build/editor.css' ),
            [],
            $asset['version']
        );
    }
}
add_action( 'enqueue_block_editor_assets', 'dsd_enqueue_block_editor_assets' );

function dan_press_register_nav_menu() {
    register_nav_menu( 'primary_menu', __( 'Primary Menu', 'dan-press' ) );
}
add_action( 'after_setup_theme', 'dan_press_register_nav_menu' );

/*
|--------------------------------------------------------------------------
| ACF Block Registration
|--------------------------------------------------------------------------
*/
function dsd_register_acf_blocks() {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    $blocks = array(
        'client-logos' => array(
            'title'       => __( 'Client Logos', 'dan-press' ),
            'description' => __( 'Infinite-scroll marquee of client logos.', 'dan-press' ),
        ),
        'services' => array(
            'title'       => __( 'Services Grid', 'dan-press' ),
            'description' => __( 'Grid of service cards with icons.', 'dan-press' ),
        ),
        'about' => array(
            'title'       => __( 'About Section', 'dan-press' ),
            'description' => __( 'Two-column layout with text, stats, and image carousel.', 'dan-press' ),
        ),
        'testimonials' => array(
            'title'       => __( 'Testimonials', 'dan-press' ),
            'description' => __( 'Rating cards with quotes and avatars.', 'dan-press' ),
        ),
        'value-props' => array(
            'title'       => __( 'Value Propositions', 'dan-press' ),
            'description' => __( 'Three-column grid of value prop cards.', 'dan-press' ),
        ),
        'blog-feed' => array(
            'title'       => __( 'Blog Feed', 'dan-press' ),
            'description' => __( 'Post card grid from WP_Query.', 'dan-press' ),
        ),
        'faqs' => array(
            'title'       => __( 'FAQ Accordion', 'dan-press' ),
            'description' => __( 'Expandable FAQ pairs.', 'dan-press' ),
        ),
        'cta' => array(
            'title'       => __( 'Call to Action', 'dan-press' ),
            'description' => __( 'CTA section with heading and button.', 'dan-press' ),
        ),
    );

    foreach ( $blocks as $name => $settings ) {
        acf_register_block_type( array(
            'name'            => $name,
            'title'           => $settings['title'],
            'description'     => $settings['description'],
            'render_template' => get_template_directory() . '/template-parts/blocks/' . $name . '/' . $name . '.php',
            'category'        => 'dan-press',
            'icon'            => 'layout',
            'mode'            => 'preview',
            'supports'        => array(
                'align'   => false,
                'anchor'  => true,
                'spacing' => array(
                    'padding' => true,
                ),
            ),
        ) );
    }
}
add_action( 'acf/init', 'dsd_register_acf_blocks' );

/*
|--------------------------------------------------------------------------
| Register Block Category
|--------------------------------------------------------------------------
*/
function dsd_block_categories( $categories, $post ) {
    return array_merge( $categories, array(
        array(
            'slug'  => 'dan-press',
            'title' => __( 'Dan Press Components', 'dan-press' ),
        ),
    ) );
}
add_filter( 'block_categories_all', 'dsd_block_categories', 10, 2 );

/*
|--------------------------------------------------------------------------
| Customizer: Header Contact Button
|--------------------------------------------------------------------------
*/
function dsd_header_customize_register( $wp_customize ) {

    $wp_customize->add_panel( 'dsd_header_panel', array(
        'title'    => __( 'Header', 'dan-press' ),
        'priority' => 29,
    ) );

    $wp_customize->add_section( 'dsd_header_cta_section', array(
        'title' => __( 'Contact Button', 'dan-press' ),
        'panel' => 'dsd_header_panel',
    ) );

    $wp_customize->add_setting( 'header_contact_text', array(
        'default'           => 'Get In Touch',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'header_contact_text', array(
        'label'   => __( 'Button Text (leave empty to hide)', 'dan-press' ),
        'section' => 'dsd_header_cta_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'header_contact_url', array(
        'default'           => '#contact',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'header_contact_url', array(
        'label'   => __( 'Button URL', 'dan-press' ),
        'section' => 'dsd_header_cta_section',
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'dsd_header_customize_register' );

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

    // --- Background Video ---
    $wp_customize->add_setting( 'hero_bg_video', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'hero_bg_video', array(
        'label'       => __( 'Background Video URL (mp4)', 'dan-press' ),
        'description' => __( 'Upload a video via Media Library and paste the URL here. Leave empty for solid background.', 'dan-press' ),
        'section'     => 'dsd_hero_section',
        'type'        => 'url',
    ) );

    // --- Background Image (fallback / poster) ---
    $wp_customize->add_setting( 'hero_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_bg_image', array(
        'label'   => __( 'Background Image (fallback / video poster)', 'dan-press' ),
        'section' => 'dsd_hero_section',
    ) ) );

    // --- Accent Word Color Override ---
    $wp_customize->add_setting( 'hero_accent_color', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( 'hero_accent_color', array(
        'label'       => __( 'Accent Word Color (leave empty for default #0066FF)', 'dan-press' ),
        'section'     => 'dsd_hero_section',
        'type'        => 'color',
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
