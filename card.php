<?php
/**
 * One story card, used by the homepage, the archives, search and Read next.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'card' ); ?>>
	<a class="card-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'ttb-card',
				array(
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => '',
				)
			);
		} else {
			echo '<span class="card-media-empty"></span>';
		}
		?>
	</a>

	<div class="card-body">
		<?php ttb_category_chip(); ?>
		<h3 class="card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="card-dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php ttb_meta( false ); ?>
	</div>
</article>
