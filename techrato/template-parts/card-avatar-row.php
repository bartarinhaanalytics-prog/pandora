<?php
/**
 * Small avatar-style row card — round thumbnail + one-line title.
 * Used in the 3-column "اخبار مرتبط با..." sections.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="card-avatar-row">
	<a class="thumb" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'techrato-square' ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
		<?php endif; ?>
	</a>
	<div class="body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php techrato_card_meta( get_the_ID(), false ); ?>
	</div>
</article>
