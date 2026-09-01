<?php
/**
 * The column beside a category, archive or author listing.
 *
 * Markup follows CategoryPage.html: a popular-posts card and the social card,
 * with the 300x120 ad pair between them.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_side_term = techrato_home_term( 'side', 'cat', array( 'smartphone', 'mobile' ) );
$techrato_side_id   = $techrato_side_term ? (int) $techrato_side_term->term_id : 0;
$techrato_side_name = techrato_home_option( 'side', 'title' );
?>
<aside class="category-sidebar">

	<?php if ( techrato_home_shows( 'side' ) ) : ?>
		<?php
		$techrato_side_query = new WP_Query( array(
			'posts_per_page'      => (int) techrato_home_option( 'side', 'count' ),
			'post_status'         => 'publish',
			'ignore_sticky_posts'  => true,
			'cat'                 => $techrato_side_id ? $techrato_side_id : '',
		) );
		?>
		<?php if ( $techrato_side_query->have_posts() ) : ?>
			<section class="category-side-card">
				<div class="category-side-card__head">
					<div>
						<span class="eyebrow"><?php esc_html_e( 'پربازدید', 'techrato' ); ?></span>
						<h3><?php echo esc_html( $techrato_side_name ? $techrato_side_name : ( $techrato_side_term ? $techrato_side_term->name : __( 'موبایل', 'techrato' ) ) ); ?></h3>
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
	<?php endif; ?>

	<?php // Two 300x120 banners — the slot the old theme kept in this column. ?>
	<?php techrato_ads_render_banners( 'sidebar' ); ?>

	<?php get_template_part( 'template-parts/sidebar-social', null, array( 'style' => 'category' ) ); ?>

	<?php if ( is_active_sidebar( 'sidebar-article' ) ) : ?>
		<div class="sidebar-widgets"><?php dynamic_sidebar( 'sidebar-article' ); ?></div>
	<?php endif; ?>

</aside>
