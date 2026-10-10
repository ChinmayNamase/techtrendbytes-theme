<?php
/**
 * Tech Trend Bytes — theme functions.
 *
 * A standalone theme. There is no parent: every template, style and script here belongs to
 * this site, so anything can be changed without working around another theme's decisions.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

// Read from style.css rather than repeating the number here: a constant that has to be kept in
// step with the stylesheet header will drift, and a stale value serves browsers cached CSS.
define( 'TTB_VERSION', wp_get_theme( 'techtrendbytes' )->get( 'Version' ) );

/**
 * The transparent logo already in the media library, used when no logo has been chosen in the
 * Customizer yet. A theme switch resets the logo setting, and a header with no mark in it looks
 * broken, so this keeps the site looking finished from the first second.
 */
define( 'TTB_FALLBACK_LOGO', 'https://techtrendbytes.com/wp-content/uploads/2026/09/tech-trend-bytes-logo-transparent.png' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/customizer.php';

/**
 * Theme supports and menus.
 */
function ttb_setup() {
	load_theme_textdomain( 'techtrendbytes', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 260,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu (header)', 'techtrendbytes' ),
			'footer'  => __( 'Footer links', 'techtrendbytes' ),
		)
	);

	// Google Discover wants 1200px or wider. These are the sizes the templates ask for.
	add_image_size( 'ttb-lead', 1280, 720, true );
	add_image_size( 'ttb-card', 720, 405, true );
	add_image_size( 'ttb-thumb', 240, 240, true );

	set_post_thumbnail_size( 720, 405, true );
}
add_action( 'after_setup_theme', 'ttb_setup' );

/**
 * Content width, used by WordPress when it sizes embeds.
 */
function ttb_content_width() {
	$GLOBALS['content_width'] = 820;
}
add_action( 'after_setup_theme', 'ttb_content_width', 0 );

/**
 * Styles and scripts.
 *
 * No jQuery, no framework. One stylesheet and one small script, both served from this theme.
 */
function ttb_assets() {
	wp_enqueue_style(
		'ttb-fonts',
		'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'ttb-style', get_stylesheet_uri(), array( 'ttb-fonts' ), TTB_VERSION );

	wp_enqueue_script( 'ttb-script', get_template_directory_uri() . '/assets/js/theme.js', array(), TTB_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ttb_assets' );

/**
 * Start the webfont download a beat earlier.
 */
function ttb_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'ttb_resource_hints', 10, 2 );

/**
 * Footer widget areas.
 *
 * Three columns. If they are left empty the footer falls back to the site description, the
 * footer menu and the social links, so it is never a blank strip.
 */
function ttb_widgets() {
	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf( __( 'Footer column %d', 'techtrendbytes' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<div id="%1$s" class="ft-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="ft-h">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'ttb_widgets' );

/**
 * Body classes the stylesheet keys off.
 */
function ttb_body_class( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'ttb-list';
	}
	if ( is_singular( 'post' ) ) {
		$classes[] = 'ttb-article';
	}
	return $classes;
}
add_filter( 'body_class', 'ttb_body_class' );

/**
 * Let Google Discover have the full-size featured image.
 *
 * Yoast otherwise hands over the 1024px copy, which is under Discover's 1200px minimum for a
 * large card. Discover was this site's largest traffic source, so this matters more than it
 * looks.
 */
function ttb_full_size_opengraph_image() {
	return 'full';
}
add_filter( 'wpseo_opengraph_image_size', 'ttb_full_size_opengraph_image' );

/**
 * Give every h2 in a post an id, so the contents list can link to it.
 *
 * Ids are derived from the heading text and de-duplicated, and an id the author already wrote
 * is left alone.
 */
function ttb_heading_ids( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$used = array();

	return preg_replace_callback(
		'/<h2(?![^>]*\bid=)([^>]*)>(.*?)<\/h2>/is',
		function ( $m ) use ( &$used ) {
			$slug = sanitize_title( wp_strip_all_tags( $m[2] ) );
			if ( '' === $slug ) {
				$slug = 'section';
			}
			$base = $slug;
			$n    = 2;
			while ( isset( $used[ $slug ] ) ) {
				$slug = $base . '-' . $n++;
			}
			$used[ $slug ] = true;

			return '<h2 id="' . esc_attr( $slug ) . '"' . $m[1] . '>' . $m[2] . '</h2>';
		},
		$content
	);
}
add_filter( 'the_content', 'ttb_heading_ids', 9 );

/**
 * Excerpt length and ending, for the story cards.
 */
function ttb_excerpt_length() {
	return 26;
}
add_filter( 'excerpt_length', 'ttb_excerpt_length' );

function ttb_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'ttb_excerpt_more' );

/**
 * Trim the front end of things this theme does not use.
 */
function ttb_trim_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'ttb_trim_head' );

/**
 * Drop the emoji script. It is 12KB that this site never uses.
 */
function ttb_no_emoji() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'ttb_no_emoji' );

/**
 * Tell the browser about the hero image before it has finished reading the stylesheet.
 *
 * The largest thing on the homepage and on an article is the featured image, so it is what
 * Largest Contentful Paint measures. Without a preload the browser cannot start fetching it
 * until the CSS has parsed and the layout is built, which on a slow connection costs seconds.
 */
function ttb_preload_lcp_image() {
	$id = 0;

	if ( is_singular( 'post' ) || is_page() ) {
		$id = get_post_thumbnail_id();
	} elseif ( is_home() && ! is_paged() ) {
		$first = get_posts(
			array(
				'posts_per_page'      => 1,
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => true,
				'fields'              => 'ids',
			)
		);
		if ( $first ) {
			$id = get_post_thumbnail_id( $first[0] );
		}
	}

	if ( ! $id ) {
		return;
	}

	$size = is_singular( 'post' ) || is_page() ? 'full' : 'ttb-lead';
	$src  = wp_get_attachment_image_url( $id, $size );

	if ( ! $src ) {
		return;
	}

	$srcset = wp_get_attachment_image_srcset( $id, $size );
	$sizes  = ttb_lead_sizes();

	printf(
		'<link rel="preload" as="image" href="%1$s"%2$s%3$s fetchpriority="high">' . "\n",
		esc_url( $src ),
		$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : '',
		$srcset ? ' imagesizes="' . esc_attr( $sizes ) . '"' : ''
	);
}
add_action( 'wp_head', 'ttb_preload_lcp_image', 1 );

/**
 * The widths the hero image is actually displayed at.
 *
 * WordPress defaults to `100vw`, which on a desktop makes the browser download an image wide
 * enough for the whole window. The lead image only ever occupies about half the content column,
 * so saying so avoids fetching roughly twice the pixels needed.
 */
function ttb_lead_sizes() {
	return '(max-width: 860px) 94vw, (max-width: 1280px) 52vw, 640px';
}

/**
 * Apply those widths to the lead and card images.
 */
function ttb_image_sizes_attr( $sizes, $size ) {
	$name = is_array( $size ) ? '' : $size;

	if ( 'ttb-lead' === $name ) {
		return ttb_lead_sizes();
	}
	if ( 'ttb-card' === $name ) {
		return '(max-width: 860px) 132px, (max-width: 1280px) 30vw, 380px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'ttb_image_sizes_attr', 10, 2 );

/**
 * Put the real display widths on the hero and card images.
 *
 * WordPress assumes `100vw`, so a desktop browser downloads an image wide enough for the whole
 * window even though the lead sits in roughly half the content column. Filtering the attributes
 * directly is more reliable than `wp_calculate_image_sizes`, which does not always receive the
 * named size.
 */
function ttb_image_attrs( $attr, $attachment, $size ) {
	$name = is_array( $size ) ? '' : $size;

	if ( 'ttb-lead' === $name ) {
		$attr['sizes'] = ttb_lead_sizes();
	} elseif ( 'ttb-card' === $name ) {
		$attr['sizes'] = '(max-width: 860px) 140px, (max-width: 1280px) 31vw, 400px';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ttb_image_attrs', 10, 3 );
