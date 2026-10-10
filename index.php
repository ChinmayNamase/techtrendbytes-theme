<?php
/**
 * The homepage and the fallback for anything without a more specific template.
 *
 * Page one leads with the newest story at full size, then runs the rest three across. Later
 * pages are the grid only: a "lead story" on page four of the archive would be a lie.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ttb_paged     = max( 1, (int) get_query_var( 'paged' ) );
$ttb_show_lead = ( 1 === $ttb_paged && is_home() && ! is_paged() );
?>

<div class="wrap">

	<?php if ( have_posts() ) : ?>

		<?php if ( $ttb_show_lead ) : ?>
			<?php
			/*
			 * Page one of the homepage had no <h1> at all: the lead story is an <h2> because it is
			 * one item in a list, not the subject of the page. Screen readers and search engines
			 * both expect exactly one top-level heading, so the page gets a real one. It is
			 * visually hidden because the logo already says this in the header.
			 */
			?>
			<h1 class="screen-reader-text">
				<?php
				printf(
					/* translators: 1: site name, 2: tagline */
					esc_html__( '%1$s - %2$s', 'techtrendbytes' ),
					esc_html( get_bloginfo( 'name' ) ),
					esc_html( get_bloginfo( 'description' ) )
				);
				?>
			</h1>
			<?php
			the_post();
			get_template_part( 'template-parts/lead' );
			?>
			<div class="sec-head">
				<h2><?php esc_html_e( 'More stories', 'techtrendbytes' ); ?></h2>
			</div>
		<?php else : ?>
			<div class="sec-head">
				<h1><?php ttb_list_heading(); ?></h1>
			</div>
		<?php endif; ?>

		<div class="grid grid--3 grid--feature">
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
