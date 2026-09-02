<?php
/**
 * App-showcase card — small thumbnail, eyebrow category label, title.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$__cats = get_the_category();
?>
<article class="card-app">
	<a class="thumb" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'techrato-square' ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
		<?php endif; ?>
	</a>
	<div class="body">
		<span class="eyebrow"><?php echo esc_html( isset( $__cats[0] ) ? $__cats[0]->name : __( 'نرم‌افزار و اپلیکیشن', 'techrato' ) ); ?></span>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	</div>
</article>
