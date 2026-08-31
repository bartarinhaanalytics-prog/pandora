<?php
/**
 * The large lead card at the top of the homepage.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$techrato_cat = techrato_primary_category();
?>
<article class="feature-card">
	<?php echo str_replace( '<img', '<img class="feature-card__image"', techrato_thumb( 'techrato-hero' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<div class="feature-card__overlay"></div>
	<div class="feature-card__content">
		<?php if ( $techrato_cat ) : ?>
			<span class="category-pill"><?php echo esc_html( $techrato_cat ); ?></span>
		<?php endif; ?>
		<h2><?php the_title(); ?></h2>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		<div class="story-meta">
			<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<i></i>
			<span><?php echo esc_html( techrato_time_ago() ); ?></span>
		</div>
	</div>
	<div class="feature-card__notch">
		<a class="feature-card__button" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	</div>
</article>
