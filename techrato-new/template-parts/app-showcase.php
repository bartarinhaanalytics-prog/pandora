<?php
/**
 * "معرفی اپلیکیشن‌ها" — shown on the homepage, category archives and posts.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_apps_term = techrato_home_term( 'apps', 'cat', array( 'app-software', 'application', 'app' ) );

$techrato_apps_query = new WP_Query( array(
	'posts_per_page'      => techrato_home_option( 'apps', 'count' ),
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'cat'                 => $techrato_apps_term ? (int) $techrato_apps_term->term_id : '',
	'post__not_in'        => isset( $args['exclude'] ) ? (array) $args['exclude'] : array(),
) );
?>
<section class="app-news-section hero-pattern">
	<div class="container">
		<?php // Native ads belong above the box, not inside it. ?>
		<?php techrato_ads_render_apps_native(); ?>

		<?php
		techrato_section_heading(
			__( 'اپلیکیشن و نرم‌افزار', 'techrato' ),
			techrato_home_option( 'apps', 'title' ),
			$techrato_apps_term ? get_category_link( $techrato_apps_term->term_id ) : '',
			__( 'مشاهده بیشتر', 'techrato' )
		);
		?>

		<?php if ( $techrato_apps_query->have_posts() ) : ?>
			<div class="app-news-grid">
				<?php while ( $techrato_apps_query->have_posts() ) : $techrato_apps_query->the_post(); ?>
					<?php get_template_part( 'template-parts/card', 'app' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
