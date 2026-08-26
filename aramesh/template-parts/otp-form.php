<?php
/**
 * فرم ورود/ثبت‌نام با OTP (قابل استفاده مجدد).
 * args از طریق query var 'aramesh_otp_args':
 *   region   => 'iran' | 'international'
 *   redirect => URL بازگشت
 *
 * @package Aramesh
 */

$args     = (array) get_query_var( 'aramesh_otp_args' );
$region   = isset( $args['region'] ) ? $args['region'] : 'iran';
$redirect = isset( $args['redirect'] ) ? $args['redirect'] : '';
?>
<div class="card-soft p-4 p-md-5 otp-box">
	<form data-otp-form data-region="<?php echo esc_attr( $region ); ?>" data-redirect="<?php echo esc_url( $redirect ); ?>" novalidate>

		<div data-otp-step="mobile">
			<label class="form-label" for="otp-mobile"><?php esc_html_e( 'شماره موبایل', 'aramesh' ); ?></label>
			<input type="tel" id="otp-mobile" name="mobile" class="form-control mb-2" placeholder="09xxxxxxxxx" inputmode="numeric" autocomplete="tel" required>
			<p class="otp-note mb-3"><?php esc_html_e( 'برای ورود یا ساخت حساب، شماره موبایل خود را وارد کنید. کد تایید پیامک می‌شود.', 'aramesh' ); ?></p>
			<button type="button" class="btn btn-primary w-100" data-otp-send><?php esc_html_e( 'دریافت کد تایید', 'aramesh' ); ?></button>
		</div>

		<div data-otp-step="code" class="is-hidden">
			<label class="form-label" for="otp-code"><?php esc_html_e( 'کد تایید', 'aramesh' ); ?></label>
			<input type="text" id="otp-code" name="code" class="form-control otp-code mb-2" placeholder="- - - - -" inputmode="numeric" maxlength="6" autocomplete="one-time-code">
			<button type="button" class="btn btn-primary w-100 mb-2" data-otp-verify><?php esc_html_e( 'ورود', 'aramesh' ); ?></button>
			<button type="button" class="btn btn-ghost w-100" data-otp-resend><?php esc_html_e( 'ارسال مجدد کد', 'aramesh' ); ?></button>
		</div>

		<div class="form-message mt-3" data-otp-message role="status"></div>
	</form>

	<p class="text-secondary small text-center mt-3 mb-0">
		<?php esc_html_e( 'با ورود، قوانین و حریم خصوصی را می‌پذیرید.', 'aramesh' ); ?>
		<a href="<?php echo esc_url( aramesh_page_url( 'legal' ) ); ?>"><?php esc_html_e( 'مطالعه', 'aramesh' ); ?></a>
	</p>
</div>
