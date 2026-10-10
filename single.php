<?php
/**
 * A single post.
 *
 * The body sits in one narrow column, because a line of text 1100px wide is unreadable, but
 * the featured image, tables and anything marked "wide" are allowed to break out of it.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article <?php post_class( 'post' ); ?>>

		<div class="wrap">
			<?php ttb_breadcrumbs(); ?>

			<header class="post-head">
				<?php ttb_category_chip(); ?>
				<h1 class="post-title"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="post-dek"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<?php ttb_meta(); ?>
			</header>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="post-hero">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'loading'       => 'eager',
						'decoding'      => 'async',
						'fetchpriority' => 'high',
					)
				);
				?>
				<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
					<figcaption><?php echo esc_html( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="wrap">
			<div class="prose">
				<?php
				$ttb_content = apply_filters( 'the_content', get_the_content() );
				ttb_table_of_contents( $ttb_content );
				echo $ttb_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already filtered.

				wp_link_pages(
					array(
						'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'techtrendbytes' ),
						'after'  => '</nav>',
					)
				);
				?>
			</div>

			<?php if ( has_tag() ) : ?>
				<div class="tags">
					<span class="kicker"><?php esc_html_e( 'Tagged', 'techtrendbytes' ); ?></span>
					<?php the_tags( '', '', '' ); ?>
				</div>
			<?php endif; ?>

			<?php ttb_author_box(); ?>

			<?php ttb_related_posts(); ?>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>

	</article>

	<?php
endwhile;

get_footer();
