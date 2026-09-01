<?php
/**
 * Shared body for category, tag, archive, blog and search listings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_is_term = is_category() || is_tag() || is_tax();
$techrato_desc    = $techrato_is_term ? term_description() : '';
$techrato_found   = '';
$techrato_image   = $techrato_is_term ? techrato_term_image( null, 'large', $techrato_found ) : '';

// No plugin field? The picture is often pasted into the description itself,
// which puts it above the text — lift it out so it can sit beside it.
if ( $techrato_is_term && ! $techrato_image ) {
	list( $techrato_image, $techrato_desc ) = techrato_extract_first_image( $techrato_desc );
}
?>
<main id="primary" class="category-page">

	<section class="category-intro">
		<div class="container">
			<?php techrato_breadcrumbs(); ?>

			<div class="category-hero<?php echo $techrato_image ? ' has-image' : ''; ?>">
				<div class="category-hero__content">
					<?php if ( $techrato_is_term ) : ?>
						<span class="eyebrow"><?php esc_html_e( 'دسته بندی', 'techrato' ); ?></span>
						<h1><?php single_term_title(); ?></h1>
						<?php if ( $techrato_desc ) : ?>
							<p><?php echo wp_kses_post( $techrato_desc ); ?></p>
						<?php endif; ?>
					<?php elseif ( is_search() ) : ?>
						<span class="eyebrow"><?php esc_html_e( 'جستجو', 'techrato' ); ?></span>
						<h1>
							<?php
							/* translators: %s: search query */
							printf( esc_html__( 'نتایج جستجو برای: «%s»', 'techrato' ), esc_html( get_search_query() ) );
							?>
						</h1>
					<?php else : ?>
						<span class="eyebrow"><?php esc_html_e( 'اخبار و مقالات', 'techrato' ); ?></span>
						<h1><?php esc_html_e( 'آخرین مطالب تکراتو', 'techrato' ); ?></h1>
					<?php endif; ?>
				</div>

				<?php if ( $techrato_image ) : ?>
					<div class="category-hero__image"><?php echo wp_kses_post( $techrato_image ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php // Two banners above the listing, on category and archive pages. ?>
	<?php if ( techrato_ads_has( 'cat_top' ) ) : ?>
		<section class="section ad-section"><div class="container">
			<?php techrato_ads_render_banners( 'cat_top' ); ?>
		</div></section>
	<?php endif; ?>

	<section class="category-content">
		<div class="container category-layout">
			<div class="category-main-column">
				<?php
				techrato_section_heading(
					__( 'تازه‌ترین مطالب', 'techrato' ),
					__( 'آخرین مطالب', 'techrato' ),
					'',
					'',
					'category-latest-heading'
				);

				$techrato_term_id = ( is_category() || is_tag() || is_tax() ) && get_queried_object() instanceof WP_Term
					? (int) get_queried_object()->term_id
					: 0;

				get_template_part( 'template-parts/feed-box', null, array(
					'query'      => $GLOBALS['wp_query'],
					'term_id'    => $techrato_term_id,
					'card'       => 'category-post',
					'list_class' => 'category-post-list',
					'ads'        => 'cat_native',
					'more_url'   => $techrato_term_id ? get_category_link( $techrato_term_id ) : techrato_more_url( '', $GLOBALS['wp_query'] ),
					'empty_text' => __( 'مطلبی برای نمایش یافت نشد.', 'techrato' ),
					'push_url'   => true,
				) );
				?>
			</div>

			<?php get_template_part( 'template-parts/sidebar-category' ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/app-showcase' ); ?>
</main>
