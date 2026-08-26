<?php
/**
 * Template Name: ورود و عضویت
 * صفحه ۵ — ورود/عضویت با OTP.
 *
 * @package Aramesh
 */

// اگر کاربر وارد شده، به داشبورد.
if ( is_user_logged_in() ) {
	wp_safe_redirect( aramesh_page_url( 'account' ) );
	exit;
}

$redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : aramesh_page_url( 'account' );

get_header();
?>
<section class="section">
	<div class="container">
		<div class="row justify-content-center g-5 align-items-center">
			<div class="col-lg-5 order-lg-2">
				<?php
				set_query_var( 'aramesh_otp_args', array( 'region' => 'iran', 'redirect' => $redirect ) );
				get_template_part( 'template-parts/otp-form' );
				?>
			</div>
			<div class="col-lg-6 order-lg-1">
				<span class="eyebrow"><?php esc_html_e( 'ورود / عضویت', 'aramesh' ); ?></span>
				<h1 class="mb-3"><?php esc_html_e( 'ورود ساده با شماره موبایل', 'aramesh' ); ?></h1>
				<p class="lead-soft mb-4"><?php esc_html_e( 'برای ورود یا ساخت حساب، فقط شماره موبایل خود را وارد کنید. رمز عبور لازم نیست؛ با کد پیامکی وارد می‌شوید.', 'aramesh' ); ?></p>
				<ul class="check-list">
					<li><?php esc_html_e( 'دسترسی سریع به «دوره‌های من»', 'aramesh' ); ?></li>
					<li><?php esc_html_e( 'پیگیری پیشرفت یادگیری', 'aramesh' ); ?></li>
					<li><?php esc_html_e( 'پشتیبانی و منابع دوره', 'aramesh' ); ?></li>
				</ul>
				<p class="text-secondary mt-4">
					<?php esc_html_e( 'خارج از ایران هستید؟', 'aramesh' ); ?>
					<a href="<?php echo esc_url( aramesh_page_url( 'register_intl' ) ); ?>"><?php esc_html_e( 'راهنمای ثبت‌نام بین‌المللی', 'aramesh' ); ?></a>
				</p>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
