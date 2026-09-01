<?php
/**
 * The column beside a post. Markup follows ContentPage.html.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_recent = new WP_Query( array(
	'posts_per_page'      => (int) techrato_home_option( 'side', 'count' ),
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'post__not_in'        => isset( $args['exclude'] ) ? (array) $args['exclude'] : array(),
) );
?>
<aside class="article-sidebar">

	<?php if ( $techrato_recent->have_posts() ) : ?>
		<section class="sidebar-card">
			<h3><?php esc_html_e( 'جدیدترین اخبار تکنولوژی', 'techrato' ); ?></h3>
			<div class="sidebar-news">
				<?php while ( $techrato_recent->have_posts() ) : $techrato_recent->the_post(); ?>
					<a href="<?php the_permalink(); ?>">
						<?php echo techrato_thumb( 'techrato-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<b><?php the_title(); ?></b>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php techrato_ads_render_banners( 'sidebar' ); ?>

	<?php get_template_part( 'template-parts/sidebar-social', null, array( 'style' => 'article' ) ); ?>

</aside>
