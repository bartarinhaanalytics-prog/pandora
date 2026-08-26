<?php
/**
 * قالب نظرات.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area card-soft p-4 p-md-5">
	<?php if ( have_comments() ) : ?>
		<h2 class="h5 mb-4">
			<?php
			$count = get_comments_number();
			printf(
				esc_html( _n( '%s دیدگاه', '%s دیدگاه', $count, 'aramesh' ) ),
				esc_html( number_format_i18n( $count ) )
			);
			?>
		</h2>
		<ol class="comment-list list-unstyled">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 48,
					'short_ping'  => true,
				)
			);
			?>
		</ol>
		<?php the_comments_pagination( array( 'prev_text' => __( 'قبلی', 'aramesh' ), 'next_text' => __( 'بعدی', 'aramesh' ) ) ); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'         => 'row g-3',
			'title_reply_before' => '<h2 class="h5 mb-3">',
			'title_reply_after'  => '</h2>',
			'class_submit'       => 'btn btn-primary',
		)
	);
	?>
</div>
