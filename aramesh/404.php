<?php
/**
 * صفحه ۴۰۴ (Page 18) — آرام و مینیمال.
 *
 * @package Aramesh
 */

get_header();
?>
<section class="section">
	<div class="container">
		<div class="card-soft p-5 text-center mx-auto" style="max-width:640px">
			<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'leaf', 48 ); ?></div>
			<h1 class="display-6 fw-bold mb-2">۴۰۴</h1>
			<h2 class="h5 mb-2"><?php esc_html_e( 'صفحه‌ای که دنبالش بودید پیدا نشد', 'aramesh' ); ?></h2>
			<p class="text-secondary"><?php esc_html_e( 'ممکن است نشانی تغییر کرده باشد. از جستجو یا لینک‌های زیر استفاده کنید.', 'aramesh' ); ?></p>
			<div style="max-width:420px;margin-inline:auto" class="my-3"><?php get_search_form(); ?></div>
			<div class="d-flex flex-wrap gap-2 justify-content-center">
				<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'aramesh' ); ?></a>
				<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'دوره‌ها', 'aramesh' ); ?></a>
				<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'مقالات', 'aramesh' ); ?></a>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
