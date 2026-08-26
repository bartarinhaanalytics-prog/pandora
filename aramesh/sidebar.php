<?php
/**
 * کناره (مجله/مقاله).
 *
 * @package Aramesh
 */

?>
<div class="aramesh-sidebar">
	<?php if ( is_active_sidebar( 'sidebar-article' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-article' ); ?>
	<?php else : ?>

		<section class="widget card-soft p-4 mb-4">
			<h3 class="widget-title h5 mb-3"><?php esc_html_e( 'جستجو', 'aramesh' ); ?></h3>
			<?php get_search_form(); ?>
		</section>

		<section class="widget card-soft p-4 mb-4">
			<h3 class="widget-title h5 mb-3"><?php esc_html_e( 'دسته‌بندی مقالات', 'aramesh' ); ?></h3>
			<ul class="footer-links">
				<?php wp_list_categories( array( 'title_li' => '', 'show_count' => true ) ); ?>
			</ul>
		</section>

		<section class="widget cta-soft p-4 mb-4">
			<h3 class="h5 mb-2"><?php esc_html_e( 'دوره‌های آموزشی', 'aramesh' ); ?></h3>
			<p class="text-secondary small mb-3"><?php esc_html_e( 'برای یادگیری عمیق‌تر، دوره‌های تخصصی را ببینید.', 'aramesh' ); ?></p>
			<a class="btn btn-primary w-100" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?></a>
		</section>

	<?php endif; ?>
</div>
