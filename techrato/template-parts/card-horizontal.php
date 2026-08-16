<?php
/**
 * Horizontal card: small square thumbnail beside title + meta.
 * Used in the sidebar "مقالات آموزشی" list.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="card-horizontal">
	<a class="thumb" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'techrato-thumb' ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
		<?php endif; ?>
	</a>
	<div class="body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php techrato_card_meta( get_the_ID(), false ); ?>
	</div>
</article>
