<?php
/**
 * Vertical card: image on top, title + meta below.
 *
 * $args:
 *   'variant' => '' | 'compact' | 'feature'
 *   'badge'   => optional text shown as a pill over the thumbnail (compact variant)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$variant = isset( $args['variant'] ) ? $args['variant'] : '';
$badge   = isset( $args['badge'] ) ? $args['badge'] : '';
$class   = 'card-vertical' . ( $variant ? ' card-vertical--' . esc_attr( $variant ) : '' );
$size    = 'feature' === $variant ? 'techrato-hero' : 'techrato-card';
?>
<article class="<?php echo esc_attr( $class ); ?>">
	<a class="thumb" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( $size ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
		<?php endif; ?>
		<?php if ( $badge ) : ?><span class="badge-tag"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
	</a>
	<?php if ( 'feature' === $variant ) : ?>
		<div class="tags">
			<?php
			$cats = get_the_category();
			foreach ( array_slice( $cats, 0, 2 ) as $cat ) {
				echo '<a class="pill" href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
			}
			?>
		</div>
	<?php endif; ?>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<?php techrato_card_meta(); ?>
</article>
