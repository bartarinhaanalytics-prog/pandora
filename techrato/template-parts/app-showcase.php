<?php
/**
 * "معرفی نرم‌افزار و اپلیکیشن" section — shown on the homepage,
 * category archives and single posts.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Category, title and count come from
// Customizer > صفحه نخست تکراتو > معرفی نرم‌افزار و اپلیکیشن.
$techrato_apps_term = techrato_home_term( 'apps', 'cat', array( 'app-software', 'application', 'app' ) );

$techrato_apps_query = new WP_Query( array(
	'posts_per_page'      => techrato_home_option( 'apps', 'count' ),
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'cat'                 => $techrato_apps_term ? (int) $techrato_apps_term->term_id : '',
	'post__not_in'        => isset( $args['exclude'] ) ? (array) $args['exclude'] : array(),
) );
?>
<section class="block app-showcase-wrap">
	<div class="container">
		<div class="section-title">
			<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18"/></svg></span>
			<h2><?php echo esc_html( techrato_home_option( 'apps', 'title' ) ); ?></h2>
			<span class="bar"></span>
		</div>

		<?php if ( $techrato_apps_query->have_posts() ) : ?>
			<div class="app-showcase-grid">
				<?php while ( $techrato_apps_query->have_posts() ) : $techrato_apps_query->the_post(); ?>
					<?php get_template_part( 'template-parts/card', 'app' ); ?>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<?php techrato_empty_card_notice(); ?>
		<?php endif; ?>
	</div>
</section>
