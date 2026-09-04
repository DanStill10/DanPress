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

    // Load the compiled front-end stylesheet into the block editor's content
    // iframe so blocks preview with real site styling (dark backgrounds,
    // fonts, etc.) instead of the default white canvas. `add_editor_style()`
    // is the mechanism that reaches the iframe — enqueuing via
    // `enqueue_block_editor_assets` only affects the outer admin page, never
    // the iframe. Production filenames are content-hashed, so the actual
    // path is resolved from the manifest rather than hardcoded.
    $manifest_path = get_theme_file_path( 'public/entrypoints.json' );
    if ( file_exists( $manifest_path ) ) {
        $manifest = json_decode( file_get_contents( $manifest_path ), true );
        if ( ! empty( $manifest['app']['css'][0] ) ) {
            add_editor_style( 'public/' . $manifest['app']['css'][0] );
        }
    }

    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 1200;
    }
}
add_action( 'after_setup_theme', 'dsd_theme_setup' );

/**
 * Enqueue Theme-wide Frontend Assets (from Bud.js)
 *
 * This function enqueues the main site styles and scripts for the front-end
 * of the website. Fonts (Inter, Space Grotesk, JetBrains Mono) are
 * self-hosted and bundled into app.css via @fontsource, so no external
 * font requests are required.
 *
 * It handles both development and production environments.
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
            [],
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
                        [],
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
}
add_action( 'enqueue_block_editor_assets', 'dsd_enqueue_block_editor_assets' );

function dan_press_register_nav_menu() {
    register_nav_menu( 'primary_menu', __( 'Primary Menu', 'dan-press' ) );
}
add_action( 'after_setup_theme', 'dan_press_register_nav_menu' );

/**
 * wp_nav_menu() falls back to wp_page_menu() (auto-listing every published
 * page) whenever no menu is assigned to a theme location — its markup
 * doesn't match .main-navigation's styling either. Replace that with a
 * quiet no-op for visitors, and a direct link for admins so the missing
 * menu is obvious instead of silently auto-populating.
 */
function dsd_primary_menu_fallback() {
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    printf(
        '<ul><li><a href="%s">%s</a></li></ul>',
        esc_url( admin_url( 'nav-menus.php' ) ),
        esc_html__( 'Set up the Primary Menu', 'dan-press' )
    );
}

/*
|--------------------------------------------------------------------------
| ACF Local JSON — Load & Save from theme acf-json/ directory
|--------------------------------------------------------------------------
*/
function dsd_acf_json_load( $paths ) {
    $paths[] = get_theme_file_path( '/acf-json' );
    return $paths;
}
add_filter( 'acf/settings/load_json', 'dsd_acf_json_load' );

function dsd_acf_json_save( $path ) {
    return get_theme_file_path( '/acf-json' );
}
add_filter( 'acf/settings/save_json', 'dsd_acf_json_save' );

/*
|--------------------------------------------------------------------------
| ACF Block Registration
|--------------------------------------------------------------------------
*/

/**
 * Build an anchor's href attribute, omitted entirely when rendering an ACF
 * block's editor preview ($is_preview). Block preview HTML is real, live
 * markup injected into the block-editor canvas — a real href there is a
 * real, clickable link, and if the canvas's click-interception isn't
 * airtight (an iframed-canvas/ACF-version gap), clicking it navigates the
 * whole editor to that URL. Dropping the href in preview makes that
 * impossible while leaving the front-end render untouched.
 */
function dsd_block_href( $url, $is_preview ) {
    return $is_preview ? '' : 'href="' . esc_url( $url ) . '"';
}

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
        'contact' => array(
            'title'       => __( 'Contact', 'dan-press' ),
            'description' => __( 'Heading, intro, and embedded WPForms form. Always anchored as #contact so the header button can scroll to it.', 'dan-press' ),
            // Anchor support disabled: the block hardcodes id="contact" itself,
            // so a user-set anchor here could collide with it.
            'supports'    => array(
                'align'   => false,
                'anchor'  => false,
                'spacing' => array(
                    'padding' => true,
                ),
            ),
        ),
    );

    $default_supports = array(
        'align'   => false,
        'anchor'  => true,
        'spacing' => array(
            'padding' => true,
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
            'supports'        => $settings['supports'] ?? $default_supports,
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

    $wp_customize->add_setting( 'contact_page_id', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'contact_page_id', array(
        'label'       => __( 'Contact Page', 'dan-press' ),
        'description' => __( 'Used everywhere except the homepage, where the button scrolls to the on-page Contact section instead.', 'dan-press' ),
        'section'     => 'dsd_header_cta_section',
        'type'        => 'dropdown-pages',
    ) );
}
add_action( 'customize_register', 'dsd_header_customize_register' );

/**
 * Header/CTA contact destination: scrolls to the homepage's #contact
 * section when already on the front page, otherwise links to the
 * dedicated Contact page chosen above (falls back to home + #contact if
 * none is set yet, which still works — the anchor just resolves after a
 * page load instead of a same-page scroll).
 */
function dsd_get_contact_url() {
    if ( is_front_page() ) {
        return '#contact';
    }

    $contact_page_id = (int) get_theme_mod( 'contact_page_id' );
    if ( $contact_page_id ) {
        $url = get_permalink( $contact_page_id );
        if ( $url ) {
            return $url;
        }
    }

    return home_url( '/#contact' );
}

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

/*
|--------------------------------------------------------------------------
| Blog Category Filter (posts page)
|--------------------------------------------------------------------------
|
| The category filter bar on index.php submits a blog_cat query var.
| This hook applies it to the MAIN query. It must live here (functions.php),
| not in the template, because the main query has already run by the time
| index.php loads. Guards applied so it can never touch other queries:
|   - Only the main query (never secondary loops or admin)
|   - Only on the posts page (is_home() && ! is_front_page())
|   - Only when a valid, existing category slug is supplied
*/
function dsd_blog_apply_category_filter( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() || is_front_page() ) {
        return;
    }

    if ( empty( $_GET['blog_cat'] ) ) {
        return;
    }

    $slug = sanitize_title( wp_unslash( $_GET['blog_cat'] ) );

    // Validate the slug against a real term before it touches the query —
    // prevents junk/arbitrary input from ever reaching SQL.
    $term = get_term_by( 'slug', $slug, 'category' );
    if ( ! $term ) {
        return;
    }

    $query->set( 'category_name', $term->slug );
}
add_action( 'pre_get_posts', 'dsd_blog_apply_category_filter' );
