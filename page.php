<?php
/**
 * A static page: About, Contact, Privacy Policy and the rest.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article <?php post_class( 'post post--page' ); ?>>
		<div class="wrap">
			<?php ttb_breadcrumbs(); ?>

			<header class="post-head">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="post-hero post-hero--page">
					<?php the_post_thumbnail( 'full', array( 'decoding' => 'async' ) ); ?>
				</figure>
			<?php endif; ?>

			<div class="prose">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'techtrendbytes' ),
						'after'  => '</nav>',
					)
				);
				?>
			</div>

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
