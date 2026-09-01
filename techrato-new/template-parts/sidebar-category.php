<?php
/**
 * The column beside a category, archive or author listing.
 *
 * Markup follows CategoryPage.html: a latest-posts card and the social card,
 * with the 300x120 ad pair between them.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The newest posts of the whole site, not of one category: this column is the
// reader's way out of the page they are on.
$techrato_side_query = new WP_Query( array(
	'posts_per_page'      => (int) techrato_home_option( 'side', 'count' ),
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'post__not_in'        => isset( $args['exclude'] ) ? (array) $args['exclude'] : array(),
) );
?>
<aside class="category-sidebar">

	<?php if ( $techrato_side_query->have_posts() ) : ?>
		<section class="category-side-card">
			<div class="category-side-card__head">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'تازه‌ترین‌ها', 'techrato' ); ?></span>
					<h3><?php esc_html_e( 'جدیدترین اخبار تکنولوژی', 'techrato' ); ?></h3>
				</div>
			</div>
			<div class="category-side-list">
				<?php while ( $techrato_side_query->have_posts() ) : $techrato_side_query->the_post(); ?>
					<a class="category-side-item" href="<?php the_permalink(); ?>">
						<span class="category-side-item__thumb"><?php echo techrato_thumb( 'techrato-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
						<span class="category-side-item__title"><span><?php the_title(); ?></span></span>
					</a>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</section>
	<?php endif; ?>

	<?php // Two 300x120 banners — the slot the old theme kept in this column. ?>
	<?php techrato_ads_render_banners( 'sidebar' ); ?>

	<?php get_template_part( 'template-parts/sidebar-social', null, array( 'style' => 'category' ) ); ?>

</aside>
