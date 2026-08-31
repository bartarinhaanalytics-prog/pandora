<?php
/**
 * A card in the "مطالب مشابه" strip.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$techrato_cat = techrato_primary_category();
?>
<article class="related-card">
	<div class="related-card__image"><?php echo techrato_thumb( 'techrato-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<?php if ( $techrato_cat ) : ?>
		<span><?php echo esc_html( $techrato_cat ); ?></span>
	<?php endif; ?>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
</article>
