<?php
/**
 * Category, tag, author and date archives.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="wrap">

	<?php ttb_breadcrumbs(); ?>

	<header class="page-head">
		<h1><?php the_archive_title(); ?></h1>
		<?php
		$ttb_desc = get_the_archive_description();
		if ( $ttb_desc ) :
			?>
			<div class="page-head-dek"><?php echo wp_kses_post( $ttb_desc ); ?></div>
		<?php endif; ?>
		<p class="page-head-count">
			<?php
			printf(
				/* translators: %s: number of stories */
				esc_html( _n( '%s story', '%s stories', (int) $GLOBALS['wp_query']->found_posts, 'techtrendbytes' ) ),
				esc_html( number_format_i18n( (int) $GLOBALS['wp_query']->found_posts ) )
			);
			?>
		</p>
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
