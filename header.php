<?php
/**
 * The page head and the site header.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?> data-appearance="system">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
	/* Set the theme before the first paint, so a reader who chose dark never sees a white flash. */
	(function () {
		try {
			var m = localStorage.getItem('ttb-appearance') || 'system';
			var dark = m === 'dark' || (m === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
			var r = document.documentElement;
			r.setAttribute('data-appearance', m);
			r.setAttribute('data-theme', dark ? 'dark' : 'light');
		} catch (e) {}
	})();
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'techtrendbytes' ); ?></a>

<?php if ( is_singular( 'post' ) ) : ?>
	<div class="progress" aria-hidden="true"><span class="progress-bar"></span></div>
<?php endif; ?>

<header class="hd" id="hd">
	<div class="wrap hd-in">
		<button class="icon-btn hd-burger" type="button" aria-expanded="false" aria-controls="nav" aria-label="<?php esc_attr_e( 'Menu', 'techtrendbytes' ); ?>">
			<span class="burger-lines" aria-hidden="true"><i></i><i></i><i></i></span>
		</button>

		<?php ttb_brand(); ?>

		<nav class="nav" id="nav" aria-label="<?php esc_attr_e( 'Main menu', 'techtrendbytes' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'depth'          => 2,
					'fallback_cb'    => 'ttb_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="hd-tools">
			<?php ttb_social_links( 'social social--hd' ); ?>

			<button class="icon-btn" id="search-open" type="button" aria-expanded="false" aria-controls="search-panel" aria-label="<?php esc_attr_e( 'Search', 'techtrendbytes' ); ?>">
				<svg viewBox="0 0 24 24" width="19" height="19" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.3-3.3"/></svg>
			</button>

			<button class="icon-btn appearance" id="appearance" type="button" aria-label="<?php esc_attr_e( 'Appearance', 'techtrendbytes' ); ?>">
				<svg class="ic-sun" viewBox="0 0 24 24" width="19" height="19" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.6v2.2M12 19.2v2.2M2.6 12h2.2M19.2 12h2.2M5.4 5.4l1.6 1.6M17 17l1.6 1.6M18.6 5.4 17 7M7 17l-1.6 1.6"/></svg>
				<svg class="ic-moon" viewBox="0 0 24 24" width="19" height="19" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
				<svg class="ic-auto" viewBox="0 0 24 24" width="19" height="19" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><rect x="2.6" y="4.2" width="18.8" height="13" rx="2"/><path d="M8.5 20.4h7"/></svg>
				<span class="appearance-text" aria-live="polite"></span>
			</button>
		</div>
	</div>

	<?php ttb_topic_rail(); ?>

	<div class="search-panel" id="search-panel" hidden>
		<div class="wrap">
			<?php get_search_form(); ?>
		</div>
	</div>
</header>

<main class="site-main" id="main">
