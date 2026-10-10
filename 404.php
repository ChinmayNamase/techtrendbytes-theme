<?php
/**
 * The page shown when a URL does not exist.
 *
 * It offers a search box and the newest stories rather than a dead end, because most 404s here
 * come from an old link that changed shape.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrap">

	<header class="page-head page-head--404">
		<span class="kicker">404</span>
		<h1><?php esc_html_e( 'That page has moved or never existed', 'techtrendbytes' ); ?></h1>
		<p class="page-head-dek"><?php esc_html_e( 'Search for what you were after, or pick one of the newest stories below.', 'techtrendbytes' ); ?></p>
		<div class="page-head-form"><?php get_search_form(); ?></div>
	</header>

	<?php
	$ttb_recent = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => true,
		)
	);

	if ( $ttb_recent->have_posts() ) :
		?>
		<div class="sec-head"><h2><?php esc_html_e( 'Newest stories', 'techtrendbytes' ); ?></h2></div>
		<div class="grid grid--3">
			<?php
			while ( $ttb_recent->have_posts() ) :
				$ttb_recent->the_post();
				get_template_part( 'template-parts/card' );
			endwhile;
			?>
		</div>
		<?php
		wp_reset_postdata();
	endif;
	?>

</div>

<?php
get_footer();
