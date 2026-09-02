<?php
/**
 * Card in the "معرفی اپلیکیشن‌ها" strip.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$techrato_cat = techrato_primary_category();
?>
<article class="app-news-card">
	<div class="app-news-card__image">
		<?php echo techrato_thumb( 'techrato-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="app-card-notch">
			<a href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e( 'مشاهده خبر', 'techrato' ); ?>"><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
	</div>
	<?php if ( $techrato_cat ) : ?>
		<span><?php echo esc_html( $techrato_cat ); ?></span>
	<?php endif; ?>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
</article>
