<?php
/**
 * Customizer settings.
 *
 * Deliberately few. Everything here is something that genuinely changes site to site: the
 * social accounts, the accent colour, and the line in the footer.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

/**
 * The social accounts the theme knows how to draw an icon for.
 */
function ttb_social_networks() {
	return array(
		'x'         => __( 'X (Twitter)', 'techtrendbytes' ),
		'facebook'  => __( 'Facebook', 'techtrendbytes' ),
		'instagram' => __( 'Instagram', 'techtrendbytes' ),
		'linkedin'  => __( 'LinkedIn', 'techtrendbytes' ),
		'youtube'   => __( 'YouTube', 'techtrendbytes' ),
		'telegram'  => __( 'Telegram', 'techtrendbytes' ),
	);
}

/**
 * Register the settings.
 */
function ttb_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'ttb_options',
		array(
			'title'       => __( 'Tech Trend Bytes', 'techtrendbytes' ),
			'priority'    => 30,
			'description' => __( 'Accent colour and the social accounts shown in the header and footer.', 'techtrendbytes' ),
		)
	);

	$wp_customize->add_setting(
		'ttb_accent',
		array(
			'default'           => '#3b1edb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ttb_accent',
			array(
				'label'       => __( 'Accent colour', 'techtrendbytes' ),
				'description' => __( 'Used for links, buttons and the reading progress bar.', 'techtrendbytes' ),
				'section'     => 'ttb_options',
			)
		)
	);

	foreach ( ttb_social_networks() as $key => $label ) {
		$wp_customize->add_setting(
			'ttb_social_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'ttb_social_' . $key,
			array(
				/* translators: %s: network name */
				'label'   => sprintf( __( '%s link', 'techtrendbytes' ), $label ),
				'section' => 'ttb_options',
				'type'    => 'url',
			)
		);
	}

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}
}
add_action( 'customize_register', 'ttb_customize_register' );

/**
 * Print the chosen accent colour as a CSS variable.
 *
 * Only the one variable is overridden, so the rest of the palette — including every dark mode
 * value — keeps working whatever colour is picked.
 */
function ttb_accent_css() {
	$accent = get_theme_mod( 'ttb_accent', '#3b1edb' );

	if ( ! $accent || '#3b1edb' === strtolower( $accent ) ) {
		return;
	}

	printf(
		'<style id="ttb-accent">:root{--brand:%1$s}:root[data-theme="dark"]{--brand:%1$s}</style>',
		esc_attr( $accent )
	);
}
add_action( 'wp_head', 'ttb_accent_css', 20 );

/**
 * Live preview for the title and accent, so the Customizer does not reload the whole page.
 */
function ttb_customize_preview_js() {
	wp_enqueue_script(
		'ttb-customizer',
		get_template_directory_uri() . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		TTB_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'ttb_customize_preview_js' );
