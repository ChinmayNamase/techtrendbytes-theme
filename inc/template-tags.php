<?php
/**
 * The small functions the templates call.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

/**
 * The site mark: the chosen logo, the fallback logo, or the site name as text.
 */
function ttb_brand() {
	$url  = home_url( '/' );
	$name = get_bloginfo( 'name', 'display' );

	echo '<a class="brand" href="' . esc_url( $url ) . '" rel="home">';

	if ( has_custom_logo() ) {
		$id  = get_theme_mod( 'custom_logo' );
		$img = wp_get_attachment_image( $id, 'full', false, array( 'class' => 'brand-img', 'alt' => $name ) );
		echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapes.
	} else {
		echo '<img class="brand-img" src="' . esc_url( TTB_FALLBACK_LOGO ) . '" alt="' . esc_attr( $name ) . '" width="130" height="48" decoding="async">';
	}

	echo '<span class="brand-name">' . esc_html( $name ) . '</span>';
	echo '</a>';
}

/**
 * Reading time from the post's own word count.
 *
 * 220 words a minute is a fair average for this kind of writing.
 */
function ttb_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) ) );
	$minutes = max( 1, (int) round( $words / 220 ) );

	/* translators: %d: number of minutes */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'techtrendbytes' ), $minutes );
}

/**
 * The first category, as a chip.
 *
 * The colour rotates through the palette by term id, so a list of stories is not one flat
 * block of the same colour, and a given category always looks the same wherever it appears.
 */
function ttb_category_chip( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$cats    = get_the_category( $post_id );

	if ( empty( $cats ) ) {
		return;
	}

	$cat  = $cats[0];
	$tone = ( $cat->term_id % 4 ) + 1;

	printf(
		'<a class="chip chip--%1$d" href="%2$s">%3$s</a>',
		(int) $tone,
		esc_url( get_category_link( $cat->term_id ) ),
		esc_html( $cat->name )
	);
}

/**
 * Author, date and reading time.
 */
function ttb_meta( $show_author = true ) {
	echo '<div class="meta">';

	if ( $show_author ) {
		printf(
			'<a class="meta-author" href="%1$s">%2$s<span>%3$s</span></a>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_avatar( get_the_author_meta( 'ID' ), 40, '', '', array( 'class' => 'meta-avatar' ) ),
			esc_html( get_the_author() )
		);
	}

	printf(
		'<time datetime="%1$s">%2$s</time><span class="meta-read">%3$s</span>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_html( ttb_reading_time() )
	);

	echo '</div>';
}

/**
 * Numbered pagination.
 */
function ttb_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'end_size'           => 1,
			'prev_text'          => __( 'Previous', 'techtrendbytes' ),
			'next_text'          => __( 'Next', 'techtrendbytes' ),
			'screen_reader_text' => __( 'More stories', 'techtrendbytes' ),
			// No 'class' argument on purpose. WordPress uses it to *replace* the default
			// `pagination` class rather than add to it, which silently detached the stylesheet
			// and left the page numbers as bare underlined links.
		)
	);
}

/**
 * A contents list built from the post's own h2 headings.
 *
 * Long explainers are this site's best pages, and a reader landing from search usually wants
 * one section of them. Nothing is printed for a post with fewer than three headings, because a
 * two-item contents list is noise.
 */
function ttb_table_of_contents( $content ) {
	if ( ! preg_match_all( '/<h2[^>]*\bid=["\']([^"\']+)["\'][^>]*>(.*?)<\/h2>/is', $content, $m, PREG_SET_ORDER ) ) {
		return;
	}

	if ( count( $m ) < 3 ) {
		return;
	}
	?>
	<nav class="toc" aria-labelledby="toc-h">
		<button class="toc-btn" type="button" aria-expanded="true" aria-controls="toc-list">
			<span id="toc-h"><?php esc_html_e( 'What is in this article', 'techtrendbytes' ); ?></span>
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
		</button>
		<ol class="toc-list" id="toc-list">
			<?php foreach ( $m as $h ) : ?>
				<li><a href="#<?php echo esc_attr( $h[1] ); ?>"><?php echo esc_html( wp_strip_all_tags( $h[2] ) ); ?></a></li>
			<?php endforeach; ?>
		</ol>
	</nav>
	<?php
}

/**
 * The author box under an article.
 */
function ttb_author_box() {
	$id  = get_the_author_meta( 'ID' );
	$bio = get_the_author_meta( 'description', $id );

	if ( ! $bio ) {
		$bio = __( 'Writes about phones, AI and the technology that actually matters to buyers in India.', 'techtrendbytes' );
	}
	?>
	<aside class="authorbox">
		<?php echo get_avatar( $id, 112, '', '', array( 'class' => 'authorbox-img' ) ); ?>
		<div class="authorbox-body">
			<span class="kicker"><?php esc_html_e( 'Written by', 'techtrendbytes' ); ?></span>
			<h2 class="authorbox-name"><?php echo esc_html( get_the_author_meta( 'display_name', $id ) ); ?></h2>
			<p><?php echo esc_html( $bio ); ?></p>
			<a class="btn btn--ghost" href="<?php echo esc_url( get_author_posts_url( $id ) ); ?>">
				<?php esc_html_e( 'All posts by this author', 'techtrendbytes' ); ?>
			</a>
		</div>
	</aside>
	<?php
}

/**
 * Three more posts from the same category.
 *
 * Every article used to be a dead end, which is why pages per visit sat at one. If the category
 * holds nothing else, nothing is printed rather than padding the row with unrelated stories.
 */
function ttb_related_posts() {
	$cats = wp_get_post_categories( get_the_ID() );

	if ( empty( $cats ) ) {
		return;
	}

	$related = new WP_Query(
		array(
			'category__in'        => $cats,
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => true,
		)
	);

	if ( ! $related->have_posts() ) {
		wp_reset_postdata();
		return;
	}
	?>
	<section class="related" aria-labelledby="related-h">
		<div class="sec-head"><h2 id="related-h"><?php esc_html_e( 'Read next', 'techtrendbytes' ); ?></h2></div>
		<div class="grid grid--3">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				get_template_part( 'template-parts/card' );
			endwhile;
			?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}

/**
 * The strip of category links under the header.
 *
 * It is the fastest way into the rest of the site from any page, and it is built from the
 * categories that actually have posts in them, so it cannot go stale.
 */
function ttb_topic_rail() {
	$cats = get_categories(
		array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 8,
			'hide_empty' => true,
		)
	);

	if ( empty( $cats ) ) {
		return;
	}
	?>
	<div class="rail">
		<div class="wrap rail-in">
			<span class="rail-label"><?php esc_html_e( 'Topics', 'techtrendbytes' ); ?></span>
			<ul class="rail-list">
				<?php foreach ( $cats as $cat ) : ?>
					<li<?php echo is_category( $cat->term_id ) ? ' class="is-on"' : ''; ?>>
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
							<?php echo esc_html( $cat->name ); ?>
							<b><?php echo esc_html( number_format_i18n( $cat->count ) ); ?></b>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Breadcrumbs. Yoast draws them if it is active; otherwise nothing is printed rather than a
 * second, differently shaped trail appearing on some pages and not others.
 */
function ttb_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) {
		yoast_breadcrumb( '<nav class="crumbs">', '</nav>' );
	}
}

/**
 * Fallback for the header menu before one has been assigned.
 */
function ttb_menu_fallback() {
	echo '<ul class="nav-list">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 5,
		)
	);
	echo '</ul>';
}

/**
 * The heading for a paged list, so page 3 does not silently claim to be the homepage.
 */
function ttb_list_heading() {
	$paged = max( 1, (int) get_query_var( 'paged' ) );

	if ( $paged > 1 ) {
		/* translators: %s: page number */
		printf( esc_html__( 'All stories — page %s', 'techtrendbytes' ), esc_html( number_format_i18n( $paged ) ) );
		return;
	}

	esc_html_e( 'All stories', 'techtrendbytes' );
}

/**
 * The social account links, drawn only for accounts that have a URL set.
 */
function ttb_social_links( $class = 'social' ) {
	$icons = array(
		'x'         => 'M17.3 3h3.3l-7.2 8.2L21.5 21h-6l-4.7-6-5.4 6H2.1l7.7-8.8L2.2 3h6.2l4.2 5.5L17.3 3Z',
		'facebook'  => 'M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5h1.65V3.6A22 22 0 0 0 14.3 3.5c-2.4 0-4 1.45-4 4.1v2.3H7.6V13h2.7v8h3.2Z',
		'instagram' => 'M12 7.4a4.6 4.6 0 1 0 0 9.2 4.6 4.6 0 0 0 0-9.2Zm0 7.6a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm5.9-7.8a1.07 1.07 0 1 1-2.15 0 1.07 1.07 0 0 1 2.15 0ZM21 8.9c-.05-1.45-.4-2.7-1.45-3.75-1.05-1.05-2.3-1.4-3.75-1.45C14.3 3.6 9.7 3.6 8.2 3.7c-1.45.05-2.7.4-3.75 1.45C3.4 6.2 3.05 7.45 3 8.9c-.1 1.5-.1 6.1 0 7.6.05 1.45.4 2.7 1.45 3.75 1.05 1.05 2.3 1.4 3.75 1.45 1.5.1 6.1.1 7.6 0 1.45-.05 2.7-.4 3.75-1.45 1.05-1.05 1.4-2.3 1.45-3.75.1-1.5.1-6.1 0-7.6Zm-1.9 9.2a3 3 0 0 1-1.7 1.7c-1.2.5-4 .35-5.4.35s-4.2.15-5.4-.35a3 3 0 0 1-1.7-1.7c-.5-1.2-.35-4-.35-5.4s-.15-4.2.35-5.4a3 3 0 0 1 1.7-1.7c1.2-.5 4-.35 5.4-.35s4.2-.15 5.4.35a3 3 0 0 1 1.7 1.7c.5 1.2.35 4 .35 5.4s.15 4.2-.35 5.4Z',
		'linkedin'  => 'M6.94 8.5H3.56V21h3.38V8.5ZM5.25 3a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92ZM21 14.35c0-3.3-1.78-4.84-4.15-4.84-1.91 0-2.77 1.05-3.25 1.79V8.5H10.2V21h3.4v-6.2c0-1.64.31-3.22 2.34-3.22 2 0 2.03 1.87 2.03 3.33V21H21v-6.65Z',
		'youtube'   => 'M21.6 7.6a2.5 2.5 0 0 0-1.76-1.77C18.27 5.4 12 5.4 12 5.4s-6.27 0-7.84.43A2.5 2.5 0 0 0 2.4 7.6C2 9.18 2 12 2 12s0 2.82.4 4.4a2.5 2.5 0 0 0 1.76 1.77c1.57.43 7.84.43 7.84.43s6.27 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77C22 14.82 22 12 22 12s0-2.82-.4-4.4ZM10 15.1V8.9l5.2 3.1-5.2 3.1Z',
		'telegram'  => 'M21.7 4.3 2.9 11.5c-1 .4-1 1.8.05 2.1l4.7 1.5 1.8 5.5c.25.75 1.2.95 1.75.4l2.6-2.55 4.7 3.45c.7.5 1.7.1 1.85-.75L22.9 5.4c.15-.85-.65-1.45-1.2-1.1ZM8.9 14.3l9.2-5.7-7.55 6.8-.35 3.35L8.9 14.3Z',
	);

	$out = '';

	foreach ( ttb_social_networks() as $key => $label ) {
		$url = get_theme_mod( 'ttb_social_' . $key, '' );

		if ( ! $url || ! isset( $icons[ $key ] ) ) {
			continue;
		}

		$out .= sprintf(
			'<a class="social-link" href="%1$s" rel="noopener noreferrer me" target="_blank"><span class="screen-reader-text">%2$s</span><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path fill="currentColor" d="%3$s"/></svg></a>',
			esc_url( $url ),
			esc_html( $label ),
			esc_attr( $icons[ $key ] )
		);
	}

	if ( ! $out ) {
		return;
	}

	echo '<div class="' . esc_attr( $class ) . '">' . $out . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above.
}
