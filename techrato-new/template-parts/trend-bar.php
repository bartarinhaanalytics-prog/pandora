<?php
/**
 * The "داغ‌ترین‌ها" strip under the hero.
 *
 * The <i> between items is the green separator dot the stylesheet draws; without
 * it every headline runs into the next one.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! techrato_home_shows( 'trend' ) ) {
	return;
}

$techrato_trend = new WP_Query( array(
	'posts_per_page'         => (int) techrato_home_option( 'trend', 'count' ),
	'post_status'            => 'publish',
	'ignore_sticky_posts'    => true,
	'orderby'                => 'comment_count',
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
) );

if ( ! $techrato_trend->have_posts() ) {
	return;
}
?>
<div class="trend-bar">
	<span class="trend-title"><?php echo esc_html( techrato_home_option( 'trend', 'title' ) ); ?></span>
	<div class="trend-list">
		<?php
		$techrato_first = true;
		while ( $techrato_trend->have_posts() ) :
			$techrato_trend->the_post();

			if ( ! $techrato_first ) {
				echo '<i></i>';
			}
			$techrato_first = false;
			?>
			<a href="<?php the_permalink(); ?>"><?php echo esc_html( wp_trim_words( get_the_title(), 5, '' ) ); ?></a>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</div>
