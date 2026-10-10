<?php
/**
 * The search form.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;
$ttb_id = 'search-' . wp_unique_id();
?>
<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $ttb_id ); ?>">
		<?php esc_html_e( 'Search this site', 'techtrendbytes' ); ?>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $ttb_id ); ?>"
		class="searchform-input"
		name="s"
		value="<?php echo get_search_query(); ?>"
		placeholder="<?php esc_attr_e( 'Search phones, AI, guides…', 'techtrendbytes' ); ?>"
		autocomplete="off">
	<button class="btn" type="submit"><?php esc_html_e( 'Search', 'techtrendbytes' ); ?></button>
</form>
