<?php
/**
 * Comment form + comment list, styled to match the design.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-wrap">

	<div class="comment-form-wrap">
		<?php
		comment_form( array(
			'title_reply'          => __( 'دیدگاه ها و نظرات خود را بنویسید', 'techrato' ),
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'    => '</h3>',
			'class_submit'         => 'btn',
			'label_submit'         => __( 'ارسال', 'techrato' ),
			'comment_field'        => '<textarea id="comment" name="comment" placeholder="' . esc_attr__( 'متن دیدگاه', 'techrato' ) . '" required></textarea>',
			'fields'                => array(
				'author' => '<div class="row"><span><input id="author" name="author" type="text" placeholder="' . esc_attr__( 'نام شما', 'techrato' ) . '" required></span>' .
					'<span><input id="email" name="email" type="email" placeholder="' . esc_attr__( 'ایمیل شما', 'techrato' ) . '" required></span></div>',
			),
		) );
		?>
	</div>

	<?php if ( have_comments() ) : ?>
		<h3 class="comment-list-title">
			<?php
			/* translators: %s: number of comments */
			printf( esc_html__( 'نظرات ثبت شده (%s نظر)', 'techrato' ), esc_html( get_comments_number() ) );
			?>
		</h3>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'    => 'ol',
				'short_ping' => true,
				'callback' => 'techrato_comment_callback',
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="pagination">
				<div><?php previous_comments_link( '« ' . esc_html__( 'قبل', 'techrato' ) ); ?></div>
				<div><?php next_comments_link( esc_html__( 'بعد', 'techrato' ) . ' »' ); ?></div>
			</div>
		<?php endif; ?>

	<?php elseif ( ! comments_open() ) : ?>
		<p style="color:var(--text-dim);font-size:13px;"><?php esc_html_e( 'امکان ثبت دیدگاه برای این مطلب بسته شده است.', 'techrato' ); ?></p>
	<?php endif; ?>

</div>
