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

		<?php $term_image = ( is_category() || is_tag() || is_tax() ) ? techrato_term_image() : ''; ?>
		<div class="archive-header<?php echo $term_image ? ' has-image' : ''; ?>">
			<?php if ( is_category() || is_tag() || is_tax() ) : ?>
				<div class="archive-header-text">
					<span class="pill"><?php esc_html_e( 'دسته بندی', 'techrato' ); ?></span>
					<h1><?php single_term_title(); ?></h1>
					<?php $desc = term_description(); ?>
					<?php if ( $desc ) : ?>
						<p><?php echo wp_kses_post( $desc ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $term_image ) : ?>
					<div class="archive-header-image"><?php echo $term_image; // phpcs:ignore WordPress.Security.EscapeOutput — built by techrato_term_image(). ?></div>
				<?php else : ?>
					<?php techrato_term_image_debug(); ?>
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
				$techrato_has_posts = have_posts();
				// Real links to the sub-categories of this category (or, on a
				// sub-category, to its siblings). A category with neither gets
				// no tabs rather than buttons that lead nowhere.
				$archive_tabs = techrato_archive_tabs();
				?>
				<div class="widget list-widget">
					<?php if ( $archive_tabs ) : ?>
						<div class="tabs" style="margin-bottom:16px;">
							<?php foreach ( $archive_tabs as $tab ) : ?>
								<a href="<?php echo esc_url( $tab['url'] ); ?>"<?php echo $tab['current'] ? ' class="is-active" aria-current="page"' : ''; ?>><?php echo esc_html( $tab['label'] ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( $techrato_has_posts ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/card', 'list-row', array( 'tags' => true ) ); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<?php techrato_empty_card_notice( __( 'مطلبی برای نمایش یافت نشد.', 'techrato' ) ); ?>
					<?php endif; ?>
				</div>

				<?php if ( $techrato_has_posts ) : ?>
					<div class="pagination">
						<div><?php echo get_previous_posts_link( '« ' . esc_html__( 'قبل', 'techrato' ) ); ?></div>
						<div>
							<?php
							/* translators: 1: current page 2: total pages */
							printf( esc_html__( 'صفحه %1$s از %2$s', 'techrato' ), esc_html( max( 1, get_query_var( 'paged' ) ) ), esc_html( $GLOBALS['wp_query']->max_num_pages ) );
							?>
						</div>
						<div><?php echo get_next_posts_link( esc_html__( 'بعد', 'techrato' ) . ' »' ); ?></div>
					</div>
				<?php endif; ?>
			</div>

			<aside>
				<div class="widget">
					<h3 class="widget-title"><?php esc_html_e( 'مقالات آموزشی تکراتو', 'techrato' ); ?></h3>
					<?php
					$learning = techrato_query_by_slug( 'learning', 3 );
					if ( $learning->have_posts() ) :
						while ( $learning->have_posts() ) : $learning->the_post();
							get_template_part( 'template-parts/card', 'horizontal' );
						endwhile;
						wp_reset_postdata();
						?>
						<a class="more-link" href="<?php echo esc_url( techrato_more_url( 'more_link_learning', $learning, 'learning' ) ); ?>"><?php esc_html_e( 'مشاهده مطالب بیشتر', 'techrato' ); ?></a>
					<?php else : ?>
						<?php techrato_empty_card_notice(); ?>
					<?php endif; ?>
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
