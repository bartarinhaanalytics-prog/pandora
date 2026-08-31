<?php
/**
 * A row in the category / archive listing.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="category-post">
	<a class="category-post__image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<?php echo techrato_thumb( 'techrato-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</a>
	<div class="category-post__content">
		<?php $techrato_cats = array_slice( get_the_category(), 0, 2 ); ?>
		<?php if ( $techrato_cats ) : ?>
			<div class="category-post__tags">
				<?php foreach ( $techrato_cats as $techrato_cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $techrato_cat ) ); ?>"><?php echo esc_html( $techrato_cat->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
		<div class="category-post__meta">
			<span><?php echo esc_html( get_the_date() ); ?></span>
			<span class="meta-dot"></span>
			<span><?php comments_number( __( 'بدون دیدگاه', 'techrato' ), __( '۱ دیدگاه', 'techrato' ), __( '% دیدگاه', 'techrato' ) ); ?></span>
		</div>
	</div>
</article>
