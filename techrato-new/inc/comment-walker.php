<?php
/**
 * Comment list rendered with the design's own markup.
 *
 * @package techrato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The little "reply" arrow used on every comment.
 */
function techrato_comment_reply_icon() {
	return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 8l-5 4 5 4"></path><path d="M4 12h9a6 6 0 0 1 6 6"></path></svg>';
}

/**
 * The design shows a single letter in the avatar square rather than a picture,
 * so the thread stays tidy even when a commenter has no Gravatar.
 *
 * @param WP_Comment $comment Comment object.
 * @return string One character, ready to print.
 */
function techrato_comment_initial( $comment ) {
	$name = trim( (string) get_comment_author( $comment ) );

	if ( '' === $name ) {
		return '؟';
	}

	return function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 1, 'UTF-8' ) : substr( $name, 0, 1 );
}

/**
 * Walker that prints .comment-item blocks and wraps children in
 * .comment-replies, exactly as the design does.
 */
class Techrato_Comment_Walker extends Walker_Comment {

	/**
	 * Opens the replies box under a comment.
	 *
	 * @param string $output Unused; this walker echoes.
	 * @param int    $depth  Current depth.
	 * @param array  $args   wp_list_comments arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;
		echo '<div class="comment-replies">';
	}

	/**
	 * Closes the replies box.
	 *
	 * @param string $output Unused; this walker echoes.
	 * @param int    $depth  Current depth.
	 * @param array  $args   wp_list_comments arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;
		echo '</div>';
	}

	/**
	 * Prints one comment.
	 *
	 * @param string     $output            Unused; this walker echoes.
	 * @param WP_Comment $data_object       The comment.
	 * @param int        $depth             Current depth.
	 * @param array      $args              wp_list_comments arguments.
	 * @param int        $current_object_id Unused.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$comment = $data_object;
		$depth++;

		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment']       = $comment;

		$classes = array( 'comment-item' );

		if ( $depth > 1 ) {
			$classes[] = 'comment-item--reply';
		}

		$max_depth = isset( $args['max_depth'] ) ? $args['max_depth'] : 0;
		$approved  = '1' === $comment->comment_approved;
		?>
		<article <?php comment_class( implode( ' ', $classes ), $comment ); ?> id="comment-<?php comment_ID(); ?>">
			<div class="comment-item__avatar" aria-hidden="true"><?php echo esc_html( techrato_comment_initial( $comment ) ); ?></div>
			<div class="comment-item__body">
				<div class="comment-item__head">
					<div>
						<strong><?php echo esc_html( get_comment_author( $comment ) ); ?></strong>
						<span>
							<?php
							printf(
								/* translators: %s: how long ago the comment was posted */
								esc_html__( '%s پیش', 'techrato' ),
								esc_html( human_time_diff( (int) strtotime( $comment->comment_date_gmt . ' GMT' ), current_time( 'timestamp', true ) ) )
							);
							?>
						</span>
					</div>
					<?php
					// The reply text carries the icon, because WordPress builds
					// the whole link and there is no hook for its insides.
					echo get_comment_reply_link( array(
						'depth'      => $depth,
						'max_depth'  => $max_depth,
						'reply_text' => techrato_comment_reply_icon() . esc_html__( 'پاسخ', 'techrato' ),
					), $comment ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>

				<?php if ( ! $approved ) : ?>
					<p class="comment-awaiting"><?php esc_html_e( 'دیدگاه شما در انتظار تأیید است.', 'techrato' ); ?></p>
				<?php endif; ?>

				<?php comment_text( $comment ); ?>
		<?php
	}

	/**
	 * Closes one comment. Children, if any, were printed in between.
	 *
	 * @param string     $output      Unused; this walker echoes.
	 * @param WP_Comment $data_object The comment.
	 * @param int        $depth       Current depth.
	 * @param array      $args        wp_list_comments arguments.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
		echo '</div></article>';
	}
}
