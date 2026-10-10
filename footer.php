<?php
/**
 * The site footer.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="ft">
	<div class="wrap ft-cols">
		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<div class="ft-col"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		<?php else : ?>
			<div class="ft-col ft-col--about">
				<h2 class="ft-h"><?php esc_html_e( 'About', 'techtrendbytes' ); ?></h2>
				<p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
				<p><?php esc_html_e( 'Tech tips, how-to guides and plain explanations across Windows, Mac, Linux, Android, iOS and security — written for readers in India since 2023.', 'techtrendbytes' ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
			<div class="ft-col"><?php dynamic_sidebar( 'footer-2' ); ?></div>
		<?php else : ?>
			<div class="ft-col">
				<h2 class="ft-h"><?php esc_html_e( 'Pages', 'techtrendbytes' ); ?></h2>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'ft-list',
							'depth'          => 1,
						)
					);
				} else {
					echo '<ul class="ft-list">';
					wp_list_pages( array( 'title_li' => '', 'depth' => 1 ) );
					echo '</ul>';
				}
				?>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
			<div class="ft-col"><?php dynamic_sidebar( 'footer-3' ); ?></div>
		<?php else : ?>
			<div class="ft-col">
				<h2 class="ft-h"><?php esc_html_e( 'Popular topics', 'techtrendbytes' ); ?></h2>
				<ul class="ft-list">
					<?php
					wp_list_categories(
						array(
							'title_li'   => '',
							'number'     => 6,
							'orderby'    => 'count',
							'order'      => 'DESC',
							'show_count' => false,
						)
					);
					?>
				</ul>
			</div>
		<?php endif; ?>
	</div>

	<div class="wrap ft-base">
		<?php ttb_social_links( 'social social--ft' ); ?>

		<span>
			<?php
			printf(
				/* translators: 1: year, 2: site name */
				esc_html__( '© %1$s %2$s. All rights reserved.', 'techtrendbytes' ),
				esc_html( gmdate( 'Y' ) ),
				esc_html( get_bloginfo( 'name' ) )
			);
			?>
		</span>
		<a class="ft-top" href="#hd"><?php esc_html_e( 'Back to top', 'techtrendbytes' ); ?></a>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
