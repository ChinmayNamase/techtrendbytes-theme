<?php
/**
 * Search results.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrap">

	<header class="page-head">
		<span class="kicker"><?php esc_html_e( 'Search', 'techtrendbytes' ); ?></span>
		<h1>
			<?php
			printf(
				/* translators: %s: the search term */
				esc_html__( 'Results for “%s”', 'techtrendbytes' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
		<div class="page-head-form"><?php get_search_form(); ?></div>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="grid grid--3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/card' );
			endwhile;
			?>
		</div>
		<?php ttb_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/none' ); ?>
	<?php endif; ?>

</div>

<?php
get_footer();
