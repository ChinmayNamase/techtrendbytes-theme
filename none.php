<?php
/**
 * Shown when a query returns nothing.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="empty">
	<h2><?php esc_html_e( 'Nothing here yet', 'techtrendbytes' ); ?></h2>

	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'No story matched that search. Try a shorter phrase, or a product name on its own.', 'techtrendbytes' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'There are no posts in this section at the moment.', 'techtrendbytes' ); ?></p>
		<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'techtrendbytes' ); ?></a>
	<?php endif; ?>
</section>
