<?php
/**
 * Overlay hero card — big image with gradient + white text on top.
 * Expects the current loop post (call the_post() before including).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="card-hero">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'techrato-hero' ); ?>
	<?php else : ?>
		<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
	<?php endif; ?>
	<div class="card-hero-body">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
		<?php techrato_card_meta(); ?>
		<span class="hero-dots" aria-hidden="true"><span class="is-active"></span><span></span><span></span></span>
	</div>
</article>
