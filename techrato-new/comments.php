<?php
/**
 * Comments, built to the design's own markup: a form card on one side and
 * the thread on the other.
 *
 * @package techrato
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}

$techrato_count = (int) get_comments_number();
?>

<div class="section-heading comments-heading">
	<div>
		<span class="eyebrow"><?php esc_html_e( 'گفت‌وگوی کاربران', 'techrato' ); ?></span>
		<h2><?php esc_html_e( 'دیدگاه‌ها', 'techrato' ); ?></h2>
	</div>
	<span class="comments-count-pill">
		<?php
		printf(
			/* translators: %s: number of comments */
			esc_html( _n( '%s دیدگاه', '%s دیدگاه', $techrato_count, 'techrato' ) ),
			esc_html( number_format_i18n( $techrato_count ) )
		);
		?>
	</span>
</div>

<div class="comments-layout">

	<?php if ( comments_open() ) : ?>
		<div class="comments-card">
			<?php
			// The design puts the name, email and message boxes in one grid.
			// A logged-in visitor gets no name or email box, so the grid has to
			// be opened by whichever field actually comes first.
			$techrato_open = is_user_logged_in() ? '<div class="comment-grid">' : '';

			comment_form( array(
				'title_reply'          => __( 'دیدگاه شما', 'techrato' ),
				'title_reply_to'       => __( 'پاسخ به %s', 'techrato' ),
				'title_reply_before'   => '<h2>',
				'title_reply_after'    => '</h2>',
				'cancel_reply_before'  => ' <small>',
				'cancel_reply_after'   => '</small>',
				'comment_notes_before' => '<p>' . esc_html__( 'نشانی ایمیل شما منتشر نخواهد شد.', 'techrato' ) . '</p>',
				'comment_notes_after'  => '',
				'class_submit'         => 'comment-submit',
				'label_submit'         => __( 'ارسال دیدگاه', 'techrato' ),
				'submit_field'         => '%1$s %2$s',
				'comment_field'        => $techrato_open . '<textarea class="comment-field" id="comment" name="comment" rows="5" required placeholder="' . esc_attr__( 'دیدگاه خود را بنویسید...', 'techrato' ) . '"></textarea></div>',
				'fields'               => array(
					'author' => '<div class="comment-grid"><input class="comment-field" id="author" name="author" type="text" required placeholder="' . esc_attr__( 'نام شما', 'techrato' ) . '" value="">',
					'email'  => '<input class="comment-field" id="email" name="email" type="email" required placeholder="' . esc_attr__( 'ایمیل شما', 'techrato' ) . '" value="">',
				),
			) );
			?>
		</div>
	<?php endif; ?>

	<?php if ( have_comments() ) : ?>
		<div class="comments-thread">
			<?php
			wp_list_comments( array(
				'style'  => 'div',
				'walker' => new Techrato_Comment_Walker(),
			) );
			?>

			<?php
			$techrato_pages = get_comment_pages_count();
			if ( $techrato_pages > 1 && get_option( 'page_comments' ) ) :
				?>
				<div class="comments-pagination">
					<?php
					paginate_comments_links( array(
						'prev_text' => __( 'قبلی', 'techrato' ),
						'next_text' => __( 'بعدی', 'techrato' ),
					) );
					?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

</div>

<?php if ( ! comments_open() && $techrato_count ) : ?>
	<p class="comments-closed"><?php esc_html_e( 'امکان ثبت دیدگاه برای این مطلب بسته شده است.', 'techrato' ); ?></p>
<?php endif; ?>
