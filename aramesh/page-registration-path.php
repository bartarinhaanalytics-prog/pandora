<?php
/**
 * Template Name: انتخاب مسیر ثبت‌نام
 * صفحه ۶ — انتخاب محل زندگی / تفاوت مسیر ثبت‌نام.
 *
 * @package Aramesh
 */

get_header();

$redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : '';
$iran_url = aramesh_page_url( 'register_iran' );
$intl_url = aramesh_page_url( 'register_intl' );
if ( $redirect ) {
	$iran_url = add_query_arg( 'redirect_to', rawurlencode( $redirect ), $iran_url );
}
?>
<section class="section">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center section-head mt-2" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'ثبت‌نام', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php esc_html_e( 'کجا زندگی می‌کنید؟', 'aramesh' ); ?></h1>
			<p class="lead-soft"><?php esc_html_e( 'برای انتخاب مسیر مناسب ثبت‌نام و پرداخت، محل زندگی خود را مشخص کنید.', 'aramesh' ); ?></p>
		</div>

		<div class="row g-4 justify-content-center">
			<div class="col-md-6 col-lg-5">
				<div class="path-card text-center h-100">
					<div class="path-card__icon mx-auto"><?php echo aramesh_icon( 'pin', 30 ); ?></div>
					<h2 class="h4"><?php esc_html_e( 'داخل ایران هستم', 'aramesh' ); ?></h2>
					<p class="text-secondary"><?php esc_html_e( 'ثبت‌نام سریع با شماره موبایل و کد پیامکی، پرداخت ریالی از طریق درگاه و فعال‌سازی خودکار دوره.', 'aramesh' ); ?></p>
					<ul class="check-list text-start my-4">
						<li><?php esc_html_e( 'ورود با شماره موبایل و OTP', 'aramesh' ); ?></li>
						<li><?php esc_html_e( 'پرداخت ریالی امن', 'aramesh' ); ?></li>
						<li><?php esc_html_e( 'دسترسی آنی به دوره', 'aramesh' ); ?></li>
					</ul>
					<a class="btn btn-primary w-100" href="<?php echo esc_url( $iran_url ); ?>" data-region-choice="iran"><?php esc_html_e( 'ادامه مسیر داخل ایران', 'aramesh' ); ?></a>
				</div>
			</div>

			<div class="col-md-6 col-lg-5">
				<div class="path-card text-center h-100">
					<div class="path-card__icon mx-auto"><?php echo aramesh_icon( 'telegram', 30 ); ?></div>
					<h2 class="h4"><?php esc_html_e( 'خارج از ایران هستم', 'aramesh' ); ?></h2>
					<p class="text-secondary"><?php esc_html_e( 'ثبت‌نام و پرداخت با هماهنگی منشی از طریق تلگرام؛ بدون نیاز به شماره ایران یا پرداخت ریالی.', 'aramesh' ); ?></p>
					<ul class="check-list text-start my-4">
						<li><?php esc_html_e( 'هماهنگی از طریق تلگرام', 'aramesh' ); ?></li>
						<li><?php esc_html_e( 'روش پرداخت متناسب با کشور شما', 'aramesh' ); ?></li>
						<li><?php esc_html_e( 'فعال‌سازی دستی و پشتیبانی‌شده', 'aramesh' ); ?></li>
					</ul>
					<a class="btn btn-outline-primary w-100" href="<?php echo esc_url( $intl_url ); ?>" data-region-choice="international"><?php esc_html_e( 'ادامه مسیر خارج ایران', 'aramesh' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
