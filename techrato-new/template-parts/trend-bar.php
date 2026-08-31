<?php
/**
 * The "داغ‌ترین‌ها" strip under the hero.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_trend = new WP_Query( array(
	'posts_per_page'      => 5,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'orderby'             => 'comment_count',
) );

if ( ! $techrato_trend->have_posts() ) {
	return;
}
?>
<div class="trend-bar">
	<span class="trend-bar__label"><?php esc_html_e( 'داغ‌ترین‌ها', 'techrato' ); ?></span>
	<div class="trend-bar__items">
		<?php while ( $techrato_trend->have_posts() ) : $techrato_trend->the_post(); ?>
			<a href="<?php the_permalink(); ?>"><?php echo esc_html( wp_trim_words( get_the_title(), 6, '' ) ); ?></a>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
