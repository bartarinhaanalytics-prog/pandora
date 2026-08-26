<?php
/**
 * کارت مقاله.
 * انتظار: query var 'aramesh_card_id' (post ID).
 *
 * @package Aramesh
 */

$post_id = (int) get_query_var( 'aramesh_card_id' );
if ( ! $post_id ) {
	return;
}
$permalink = get_permalink( $post_id );
$cats      = get_the_category( $post_id );
$cat       = ! empty( $cats ) ? $cats[0] : null;
?>
<article class="a-card hover-lift">
	<a class="a-card__media" href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<?php echo get_the_post_thumbnail( $post_id, 'aramesh-card', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $post_id ) ) ) ); ?>
		<?php else : ?>
			<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'book', 40 ); ?></span>
		<?php endif; ?>
		<?php if ( $cat ) : ?>
			<span class="a-card__flag badge-soft"><?php echo esc_html( $cat->name ); ?></span>
		<?php endif; ?>
	</a>
	<div class="a-card__body">
		<h3 class="a-card__title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
		<p class="a-card__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post_id ), 18, '…' ) ); ?></p>
		<div class="a-card__meta">
			<span><?php echo aramesh_icon( 'calendar', 16 ); ?> <?php echo esc_html( get_the_date( '', $post_id ) ); ?></span>
			<span><?php echo aramesh_icon( 'clock', 16 ); ?> <?php echo esc_html( sprintf( __( '%d دقیقه مطالعه', 'aramesh' ), aramesh_reading_time( $post_id ) ) ); ?></span>
		</div>
	</div>
</article>
