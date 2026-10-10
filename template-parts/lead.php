<?php
/**
 * The lead story at the top of the homepage.
 *
 * Its image is loaded eagerly and marked high priority: it is almost always the largest thing
 * on the screen, so it is what Core Web Vitals measures.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'lead' ); ?>>
	<a class="lead-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'ttb-lead',
				array(
					'loading'       => 'eager',
					'decoding'      => 'async',
					'fetchpriority' => 'high',
					'alt'           => '',
				)
			);
		}
		?>
	</a>

	<div class="lead-body">
		<span class="kicker"><?php esc_html_e( 'Latest story', 'techtrendbytes' ); ?></span>
		<?php ttb_category_chip(); ?>
		<h2 class="lead-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="lead-dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php ttb_meta(); ?>
		<a class="btn" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read the story', 'techtrendbytes' ); ?></a>
	</div>
</article>
