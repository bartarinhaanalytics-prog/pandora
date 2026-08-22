<?php
/**
 * Shared body for category/tag/archive/blog/search listing pages:
 * breadcrumb, title+description, sidebar, tabbed post list, pagination, app showcase.
 * Used by category.php, archive.php, index.php and search.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<main id="primary">
	<div class="container">

		<?php techrato_breadcrumbs(); ?>

		<?php
		$is_term    = is_category() || is_tag() || is_tax();
		$term_desc  = $is_term ? term_description() : '';
		$found_via  = '';
		$term_image = $is_term ? techrato_term_image( null, 'large', $found_via ) : '';

		// No plugin field? The picture is often pasted into the category
		// description itself, which puts it above the text — lift it out so it
		// can sit beside the description like every other source.
		if ( $is_term && ! $term_image ) {
			list( $term_image, $term_desc ) = techrato_extract_first_image( $term_desc );
			if ( $term_image ) {
				$found_via = 'the category description';
			}
		}
		?>
		<div class="archive-header<?php echo $term_image ? ' has-image' : ''; ?>">
			<?php if ( $is_term ) : ?>
				<div class="archive-header-text">
					<span class="pill"><?php esc_html_e( 'دسته بندی', 'techrato' ); ?></span>
					<h1><?php single_term_title(); ?></h1>
					<?php if ( $term_desc ) : ?>
						<div class="archive-header-desc"><?php echo wp_kses_post( $term_desc ); ?></div>
					<?php endif; ?>
					<?php techrato_term_image_debug( $found_via ); ?>
				</div>
				<?php if ( $term_image ) : ?>
					<div class="archive-header-image"><?php echo wp_kses_post( $term_image ); ?></div>
				<?php endif; ?>
			<?php elseif ( is_search() ) : ?>
				<span class="pill"><?php esc_html_e( 'جستجو', 'techrato' ); ?></span>
				<h1>
					<?php
					/* translators: %s: search query */
					printf( esc_html__( 'نتایج جستجو برای: «%s»', 'techrato' ), esc_html( get_search_query() ) );
					?>
				</h1>
			<?php else : ?>
				<span class="pill"><?php esc_html_e( 'اخبار و مقالات', 'techrato' ); ?></span>
				<h1><?php esc_html_e( 'آخرین مطالب تکراتو', 'techrato' ); ?></h1>
			<?php endif; ?>
		</div>

		<div class="layout-with-sidebar">

			<div>
				<div class="section-title">
					<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/></svg></span>
					<h2><?php esc_html_e( 'آخرین مطالب', 'techrato' ); ?></h2>
					<span class="bar"></span>
				</div>

				<?php
				$techrato_has_posts    = have_posts();
				$techrato_current_term = ( is_category() || is_tag() || is_tax() ) ? get_queried_object() : null;
				$techrato_term_id      = $techrato_current_term instanceof WP_Term ? (int) $techrato_current_term->term_id : 0;
				$techrato_paged        = max( 1, (int) get_query_var( 'paged' ) );
				$techrato_max_pages    = (int) $GLOBALS['wp_query']->max_num_pages;

				// Real links to this category's sub-categories (or, on a
				// sub-category, its siblings). A category with neither gets no
				// tabs rather than buttons that lead nowhere.
				$archive_tabs = techrato_archive_tabs();
				?>
				<div class="widget list-widget">
					<?php
					get_template_part( 'template-parts/feed-box', null, array(
						'tabs'       => $archive_tabs,
						'query'      => $GLOBALS['wp_query'],
						'term_id'    => $techrato_term_id,
						'card'       => 'list-row',
						'card_args'  => array( 'tags' => true ),
						'more_url'   => $techrato_term_id ? get_category_link( $techrato_term_id ) : techrato_more_url( '', $GLOBALS['wp_query'] ),
						'empty_text' => __( 'مطلبی برای نمایش یافت نشد.', 'techrato' ),
						'push_url'   => true,
					) );
					?>
				</div>

				<?php if ( $techrato_has_posts ) : ?>
					<?php // Kept for crawlers and for visitors without JavaScript; the load-more button replaces it when scripts run. ?>
					<div class="pagination js-classic-pagination">
						<div><?php echo get_previous_posts_link( '« ' . esc_html__( 'قبل', 'techrato' ) ); ?></div>
						<div>
							<?php
							/* translators: 1: current page 2: total pages */
							printf( esc_html__( 'صفحه %1$s از %2$s', 'techrato' ), esc_html( $techrato_paged ), esc_html( $techrato_max_pages ) );
							?>
						</div>
						<div><?php echo get_next_posts_link( esc_html__( 'بعد', 'techrato' ) . ' »' ); ?></div>
					</div>
				<?php endif; ?>
			</div>

			<aside>
				<?php
				$side_term  = techrato_box_term( 'box_sidebar_cat', array( 'mobile', 'mobiles', 'phone', 'smartphone' ) );
				$side_id    = $side_term ? (int) $side_term->term_id : 0;
				$side_query = new WP_Query( array(
					'posts_per_page'      => 3,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'cat'                 => $side_id ? $side_id : '',
				) );
				?>
				<div class="widget">
					<h3 class="widget-title"><?php echo esc_html( $side_term ? $side_term->name : __( 'موبایل', 'techrato' ) ); ?></h3>
					<?php
					get_template_part( 'template-parts/feed-box', null, array(
						'query'    => $side_query,
						'term_id'  => $side_id,
						'card'     => 'horizontal',
						'more_url' => $side_term ? get_category_link( $side_id ) : techrato_more_url( 'more_link_learning', $side_query ),
					) );
					?>
				</div>

				<?php get_template_part( 'template-parts/promo-follow' ); ?>

				<?php if ( is_active_sidebar( 'sidebar-article' ) ) : ?>
					<?php dynamic_sidebar( 'sidebar-article' ); ?>
				<?php endif; ?>
			</aside>
		</div>
	</div>

	<?php get_template_part( 'template-parts/app-showcase' ); ?>
</main>
