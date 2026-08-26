<?php
/**
 * کارت دوره.
 * انتظار: query var 'aramesh_card_id' (course ID).
 *
 * @package Aramesh
 */

$course_id = (int) get_query_var( 'aramesh_card_id' );
if ( ! $course_id ) {
	return;
}

$featured   = '1' === get_post_meta( $course_id, '_aramesh_featured', true );
$bestseller = '1' === get_post_meta( $course_id, '_aramesh_bestseller', true );
$duration   = get_post_meta( $course_id, '_aramesh_duration', true );
$lessons    = get_post_meta( $course_id, '_aramesh_lesson_count', true );
$short      = get_post_meta( $course_id, '_aramesh_short_desc', true );
$permalink  = get_permalink( $course_id );
$owned      = aramesh_user_has_course( $course_id );
?>
<article class="a-card hover-lift">
	<a class="a-card__media" href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail( $course_id ) ) : ?>
			<?php echo get_the_post_thumbnail( $course_id, 'aramesh-card', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $course_id ) ) ) ); ?>
		<?php else : ?>
			<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'sprout', 40 ); ?></span>
		<?php endif; ?>
		<?php if ( $bestseller || $featured ) : ?>
			<span class="a-card__flag badge-soft <?php echo $bestseller ? 'badge-best' : 'badge-accent'; ?>">
				<?php echo esc_html( $bestseller ? __( 'پرفروش', 'aramesh' ) : __( 'پیشنهاد ویژه', 'aramesh' ) ); ?>
			</span>
		<?php endif; ?>
	</a>
	<div class="a-card__body">
		<h3 class="a-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a></h3>
		<?php if ( $short ) : ?><p class="a-card__desc"><?php echo esc_html( wp_trim_words( $short, 18, '…' ) ); ?></p><?php endif; ?>
		<div class="a-card__meta">
			<?php if ( $duration ) : ?><span><?php echo aramesh_icon( 'clock', 16 ); ?> <?php echo esc_html( $duration ); ?></span><?php endif; ?>
			<?php if ( $lessons ) : ?><span><?php echo aramesh_icon( 'video', 16 ); ?> <?php echo esc_html( $lessons ); ?> <?php esc_html_e( 'جلسه', 'aramesh' ); ?></span><?php endif; ?>
		</div>
	</div>
	<div class="a-card__foot">
		<div class="course-price"><?php echo wp_kses_post( aramesh_price_html( $course_id ) ); ?></div>
		<?php if ( $owned ) : ?>
			<a class="btn btn-outline-primary btn-sm" href="<?php echo esc_url( aramesh_page_url( 'my_courses' ) ); ?>"><?php esc_html_e( 'ورود به دوره', 'aramesh' ); ?></a>
		<?php else : ?>
			<a class="btn btn-primary btn-sm" href="<?php echo esc_url( $permalink ); ?>"><?php esc_html_e( 'مشاهده دوره', 'aramesh' ); ?></a>
		<?php endif; ?>
	</div>
</article>
