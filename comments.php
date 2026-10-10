<?php
/**
 * Comments.
 *
 * @package techtrendbytes
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>

<section class="comments" id="comments">

	<?php if ( have_comments() ) : ?>
		<div class="sec-head">
			<h2>
				<?php
				$ttb_count = (int) get_comments_number();
				printf(
					/* translators: %s: comment count */
					esc_html( _n( '%s comment', '%s comments', $ttb_count, 'techtrendbytes' ) ),
					esc_html( number_format_i18n( $ttb_count ) )
				);
				?>
			</h2>
		</div>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => esc_html__( 'Older comments', 'techtrendbytes' ),
				'next_text' => esc_html__( 'Newer comments', 'techtrendbytes' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="comments-closed"><?php esc_html_e( 'Comments are closed on this story.', 'techtrendbytes' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => esc_html__( 'Leave a comment', 'techtrendbytes' ),
			'class_submit'       => 'btn',
			'comment_notes_before' => '',
		)
	);
	?>

</section>
