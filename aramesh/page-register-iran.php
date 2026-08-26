<?php
/**
 * Template Name: ثبت‌نام داخل ایران
 * صفحه ۷ — ثبت‌نام/ورود کاربران داخل ایران با OTP و سپس مسیر خرید.
 *
 * @package Aramesh
 */

get_header();

$redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : aramesh_page_url( 'my_courses' );
$logged   = is_user_logged_in();
?>
<section class="section">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="row justify-content-center g-5 align-items-center mt-1">
			<div class="col-lg-5 order-lg-2">
				<?php if ( $logged ) : ?>
					<div class="card-soft p-4 p-md-5 otp-box text-center">
						<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'check', 40 ); ?></div>
						<h2 class="h4"><?php esc_html_e( 'شما وارد شده‌اید', 'aramesh' ); ?></h2>
						<p class="text-secondary"><?php esc_html_e( 'می‌توانید مسیر خرید دوره را ادامه دهید.', 'aramesh' ); ?></p>
						<a class="btn btn-primary w-100 mt-2" href="<?php echo esc_url( $redirect ); ?>"><?php esc_html_e( 'ادامه', 'aramesh' ); ?></a>
					</div>
				<?php else : ?>
					<?php
					set_query_var( 'aramesh_otp_args', array( 'region' => 'iran', 'redirect' => $redirect ) );
					get_template_part( 'template-parts/otp-form' );
					?>
				<?php endif; ?>
			</div>

			<div class="col-lg-6 order-lg-1">
				<span class="eyebrow"><?php esc_html_e( 'ثبت‌نام داخل ایران', 'aramesh' ); ?></span>
				<h1 class="mb-3"><?php esc_html_e( 'ثبت‌نام و خرید در چند ثانیه', 'aramesh' ); ?></h1>
				<p class="lead-soft mb-4"><?php esc_html_e( 'با شماره موبایل خود وارد شوید، کد پیامکی را تایید کنید و به مسیر پرداخت و فعال‌سازی دوره بروید.', 'aramesh' ); ?></p>
				<div class="row g-3">
					<?php
					$steps = array(
						array( '۱', __( 'شماره موبایل', 'aramesh' ), __( 'شماره خود را وارد کنید.', 'aramesh' ) ),
						array( '۲', __( 'کد تایید', 'aramesh' ), __( 'کد پیامک‌شده را بزنید.', 'aramesh' ) ),
						array( '۳', __( 'پرداخت', 'aramesh' ), __( 'پرداخت ریالی امن.', 'aramesh' ) ),
						array( '۴', __( 'دسترسی به دوره', 'aramesh' ), __( 'در «دوره‌های من».', 'aramesh' ) ),
					);
					foreach ( $steps as $s ) :
						?>
						<div class="col-6">
							<div class="d-flex gap-2 align-items-start">
								<span class="step__num flex-shrink-0" style="width:36px;height:36px"><?php echo esc_html( $s[0] ); ?></span>
								<span><span class="fw-bold d-block"><?php echo esc_html( $s[1] ); ?></span><span class="text-secondary small"><?php echo esc_html( $s[2] ); ?></span></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<p class="text-secondary small mt-4"><?php echo aramesh_icon( 'shield', 16 ); ?> <?php esc_html_e( 'شماره شما فقط برای احراز هویت و اطلاع‌رسانی دوره استفاده می‌شود.', 'aramesh' ); ?></p>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
