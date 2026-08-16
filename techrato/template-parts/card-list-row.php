<?php
/**
 * List row card: thumbnail (right) + title/excerpt/meta (left) + save button.
 * Used on the homepage "جدیدترین اخبار تکنولوژی" list and on category archives.
 *
 * $args:
 *   'featured' => bool, slightly larger title/thumb for the first item
 *   'excerpt'  => bool, show the one-line description (default true)
 *   'tags'     => bool, show category pills next to the meta (default false)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$featured = ! empty( $args['featured'] );
$excerpt  = isset( $args['excerpt'] ) ? $args['excerpt'] : true;
$tags     = ! empty( $args['tags'] );
$class    = 'card-list-row' . ( $featured ? ' card-list-row--featured' : '' );
?>
<article class="<?php echo esc_attr( $class ); ?>">
	<a class="thumb" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'techrato-list' ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
		<?php endif; ?>
	</a>
	<div class="body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $excerpt ) : ?>
			<p class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
		<?php endif; ?>
		<div class="row-meta">
			<button class="save-btn" aria-label="<?php esc_attr_e( 'ذخیره', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 4h12v17l-6-4-6 4V4Z"/></svg>
			</button>
			<?php techrato_card_meta(); ?>
			<?php if ( $tags ) : ?>
				<?php foreach ( array_slice( get_the_category(), 0, 2 ) as $cat ) : ?>
					<a class="pill" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</article>
