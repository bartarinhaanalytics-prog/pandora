<?php
/**
 * 404 error template.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main id="primary">
	<div class="container">
		<div class="error-404">
			<div class="code">404</div>
			<h1><?php esc_html_e( 'صفحه مورد نظر پیدا نشد', 'techrato' ); ?></h1>
			<p style="color:var(--text-muted);margin-bottom:26px;"><?php esc_html_e( 'ممکن است صفحه حذف شده یا آدرس اشتباه وارد شده باشد.', 'techrato' ); ?></p>
			<div style="max-width:420px;margin:0 auto 26px;">
				<?php get_search_form(); ?>
			</div>
			<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به صفحه اصلی', 'techrato' ); ?></a>
		</div>
	</div>
</main>
<?php get_footer(); ?>
