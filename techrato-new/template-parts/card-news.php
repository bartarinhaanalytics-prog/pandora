<?php
/**
 * The four-across card used by "آخرین اخبار" and the archive listings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$techrato_cat = techrato_primary_category();
?>
<article class="news-card">
	<div class="news-card__image">
		<?php echo techrato_thumb( 'techrato-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	</div>
	<?php if ( $techrato_cat ) : ?>
		<span class="story-category"><?php echo esc_html( $techrato_cat ); ?></span>
	<?php endif; ?>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
	<small><?php echo esc_html( techrato_time_ago() ); ?></small>
</article>
