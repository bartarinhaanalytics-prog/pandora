<?php
/**
 * احراز هویت با OTP (ورود/ثبت‌نام با شماره موبایل).
 *
 * - OTP هش‌شده در transient با انقضا ذخیره می‌شود (نه plaintext ماندگار).
 * - محدودیت ارسال مجدد (cooldown) و محدودیت تعداد تلاش.
 * - username کاربر = شماره موبایل نرمال‌شده.
 * - ارسال پیامک از طریق فیلتر aramesh_send_otp_sms قابل جایگزینی است.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

const ARAMESH_OTP_TTL       = 120; // ثانیه اعتبار کد.
const ARAMESH_OTP_COOLDOWN  = 60;  // ثانیه بین دو ارسال.
const ARAMESH_OTP_MAX_TRY   = 5;   // حداکثر تلاش برای هر کد.

/**
 * نرمال‌سازی شماره موبایل ایران به شکل 09XXXXXXXXX.
 * ارقام فارسی/عربی را هم به لاتین تبدیل می‌کند.
 *
 * @return string|false شماره نرمال یا false در صورت نامعتبر بودن.
 */
function aramesh_normalize_mobile( $raw ) {
	$raw = (string) $raw;
	// تبدیل ارقام فارسی و عربی به لاتین.
	$fa = array( '۰','۱','۲','۳','۴','۵','۶','۷','۸','۹' );
	$ar = array( '٠','١','٢','٣','٤','٥','٦','٧','٨','٩' );
	$en = array( '0','1','2','3','4','5','6','7','8','9' );
	$raw = str_replace( $fa, $en, $raw );
	$raw = str_replace( $ar, $en, $raw );
	// فقط ارقام.
	$digits = preg_replace( '/\D+/', '', $raw );

	// حالت‌های ورودی: 0098xxxxxxxxxx , 98xxxxxxxxxx , 9xxxxxxxxx , 09xxxxxxxxx
	if ( strpos( $digits, '0098' ) === 0 ) {
		$digits = substr( $digits, 4 );
	} elseif ( strpos( $digits, '98' ) === 0 && strlen( $digits ) === 12 ) {
		$digits = substr( $digits, 2 );
	}
	if ( strlen( $digits ) === 10 && strpos( $digits, '9' ) === 0 ) {
		$digits = '0' . $digits;
	}
	// باید 09 و 11 رقم باشد.
	if ( preg_match( '/^09\d{9}$/', $digits ) ) {
		return $digits;
	}
	return false;
}

/**
 * تولید کد OTP (۵ رقمی).
 */
function aramesh_generate_otp() {
	return (string) wp_rand( 10000, 99999 );
}

/**
 * کلید transient برای یک شماره.
 */
function aramesh_otp_key( $mobile ) {
	return 'aramesh_otp_' . md5( $mobile );
}
function aramesh_otp_cd_key( $mobile ) {
	return 'aramesh_otp_cd_' . md5( $mobile );
}

/**
 * ارسال پیامک OTP — قابل جایگزینی.
 * پیاده‌سازی پیش‌فرض فقط لاگ می‌کند (برای توسعه).
 *
 * برای اتصال ارائه‌دهنده واقعی:
 *   add_filter( 'aramesh_send_otp_sms', function( $sent, $mobile, $code ){ ... return true; }, 10, 3 );
 */
function aramesh_send_otp_sms( $mobile, $code ) {
	$sent = apply_filters( 'aramesh_send_otp_sms', null, $mobile, $code );
	if ( null !== $sent ) {
		return (bool) $sent;
	}
	// پیش‌فرض: ثبت در لاگ.
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( sprintf( '[Aramesh OTP] mobile=%s code=%s', $mobile, $code ) );
	}
	return true;
}

/**
 * پاسخ خطا برای AJAX.
 */
function aramesh_otp_error( $message, $code = 400, $extra = array() ) {
	wp_send_json_error( array_merge( array( 'message' => $message ), $extra ), $code );
}

/**
 * AJAX: درخواست کد OTP.
 */
function aramesh_ajax_request_otp() {
	check_ajax_referer( 'aramesh_nonce', 'nonce' );

	$mobile = aramesh_normalize_mobile( isset( $_POST['mobile'] ) ? wp_unslash( $_POST['mobile'] ) : '' );
	if ( ! $mobile ) {
		aramesh_otp_error( __( 'شماره موبایل معتبر نیست.', 'aramesh' ) );
	}

	// cooldown.
	if ( get_transient( aramesh_otp_cd_key( $mobile ) ) ) {
		aramesh_otp_error( __( 'برای ارسال مجدد کمی صبر کنید.', 'aramesh' ), 429 );
	}

	$code = aramesh_generate_otp();
	set_transient(
		aramesh_otp_key( $mobile ),
		array( 'hash' => wp_hash_password( $code ), 'tries' => 0 ),
		ARAMESH_OTP_TTL
	);
	set_transient( aramesh_otp_cd_key( $mobile ), 1, ARAMESH_OTP_COOLDOWN );

	$ok = aramesh_send_otp_sms( $mobile, $code );
	if ( ! $ok ) {
		aramesh_otp_error( __( 'ارسال پیامک ناموفق بود. بعداً تلاش کنید.', 'aramesh' ), 502 );
	}

	$payload = array(
		'message'  => __( 'کد تایید ارسال شد.', 'aramesh' ),
		'cooldown' => ARAMESH_OTP_COOLDOWN,
		'ttl'      => ARAMESH_OTP_TTL,
	);
	// در حالت توسعه، کد را برای تست بازگردان (فقط با WP_DEBUG).
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && 'log' === aramesh_option( 'otp_provider', 'log' ) ) {
		$payload['dev_code'] = $code;
	}
	wp_send_json_success( $payload );
}
add_action( 'wp_ajax_nopriv_aramesh_request_otp', 'aramesh_ajax_request_otp' );
add_action( 'wp_ajax_aramesh_request_otp', 'aramesh_ajax_request_otp' );

/**
 * AJAX: بررسی کد و ورود/ثبت‌نام.
 */
function aramesh_ajax_verify_otp() {
	check_ajax_referer( 'aramesh_nonce', 'nonce' );

	$mobile = aramesh_normalize_mobile( isset( $_POST['mobile'] ) ? wp_unslash( $_POST['mobile'] ) : '' );
	$code   = isset( $_POST['code'] ) ? preg_replace( '/\D+/', '', wp_unslash( $_POST['code'] ) ) : '';
	$region = isset( $_POST['region'] ) ? sanitize_text_field( wp_unslash( $_POST['region'] ) ) : 'iran';
	$redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : '';

	if ( ! $mobile ) {
		aramesh_otp_error( __( 'شماره موبایل معتبر نیست.', 'aramesh' ) );
	}
	$record = get_transient( aramesh_otp_key( $mobile ) );
	if ( ! $record || empty( $record['hash'] ) ) {
		aramesh_otp_error( __( 'کد منقضی شده است. دوباره درخواست دهید.', 'aramesh' ), 410 );
	}
	if ( (int) $record['tries'] >= ARAMESH_OTP_MAX_TRY ) {
		delete_transient( aramesh_otp_key( $mobile ) );
		aramesh_otp_error( __( 'تعداد تلاش‌ها زیاد شد. کد جدید بگیرید.', 'aramesh' ), 429 );
	}

	require_once ABSPATH . 'wp-includes/class-phpass.php';
	$hasher = new PasswordHash( 8, true );
	if ( ! $hasher->CheckPassword( $code, $record['hash'] ) ) {
		$record['tries'] = (int) $record['tries'] + 1;
		set_transient( aramesh_otp_key( $mobile ), $record, ARAMESH_OTP_TTL );
		aramesh_otp_error( __( 'کد واردشده صحیح نیست.', 'aramesh' ), 401, array( 'remaining' => ARAMESH_OTP_MAX_TRY - $record['tries'] ) );
	}

	// موفق — پاک کردن کد.
	delete_transient( aramesh_otp_key( $mobile ) );

	// یافتن یا ساخت کاربر با username = mobile.
	$user = get_user_by( 'login', $mobile );
	if ( ! $user ) {
		$user_id = wp_insert_user(
			array(
				'user_login' => $mobile,
				'user_pass'  => wp_generate_password( 24, true, true ),
				'role'       => 'subscriber',
				'display_name' => $mobile,
			)
		);
		if ( is_wp_error( $user_id ) ) {
			aramesh_otp_error( __( 'ایجاد حساب ناموفق بود.', 'aramesh' ), 500 );
		}
		update_user_meta( $user_id, 'aramesh_mobile', $mobile );
		update_user_meta( $user_id, 'aramesh_region', in_array( $region, array( 'iran', 'international' ), true ) ? $region : 'iran' );
		$user = get_user_by( 'id', $user_id );
		/**
		 * پس از ساخت کاربر جدید با OTP.
		 */
		do_action( 'aramesh_user_registered', $user_id, $mobile, $region );
	} else {
		// به‌روزرسانی منطقه در صورت ارسال.
		if ( in_array( $region, array( 'iran', 'international' ), true ) ) {
			update_user_meta( $user->ID, 'aramesh_region', $region );
		}
	}

	// ورود کاربر.
	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID, true );
	do_action( 'wp_login', $user->user_login, $user );

	// مقصد بازگشت.
	$dest = $redirect ? $redirect : home_url( '/account' );
	// اگر در جریان خرید بودیم، به checkout هدایت شود (توسط JS تنظیم می‌شود).
	$dest = apply_filters( 'aramesh_otp_redirect', $dest, $user, $region );

	wp_send_json_success(
		array(
			'message'  => __( 'ورود موفق بود.', 'aramesh' ),
			'redirect' => $dest,
		)
	);
}
add_action( 'wp_ajax_nopriv_aramesh_verify_otp', 'aramesh_ajax_verify_otp' );
add_action( 'wp_ajax_aramesh_verify_otp', 'aramesh_ajax_verify_otp' );

/**
 * تخصیص خودکار دوره پس از «پرداخت موفق» در حالت دستی/آزمایشی.
 * درگاه واقعی باید پس از تایید پرداخت، aramesh_grant_course را صدا بزند.
 */
function aramesh_ajax_mock_checkout() {
	check_ajax_referer( 'aramesh_nonce', 'nonce' );
	if ( ! is_user_logged_in() ) {
		aramesh_otp_error( __( 'ابتدا وارد شوید.', 'aramesh' ), 401 );
	}
	$course_id = isset( $_POST['course_id'] ) ? (int) $_POST['course_id'] : 0;
	if ( ! $course_id || 'course' !== get_post_type( $course_id ) ) {
		aramesh_otp_error( __( 'دوره نامعتبر است.', 'aramesh' ) );
	}

	$mode = aramesh_option( 'payment_mode', 'manual' );
	if ( 'gateway' === $mode ) {
		// انتقال به درگاه واقعی از طریق فیلتر.
		$gateway = apply_filters( 'aramesh_payment_gateway_url', '', $course_id, get_current_user_id() );
		if ( $gateway ) {
			wp_send_json_success( array( 'redirect' => esc_url_raw( $gateway ) ) );
		}
		aramesh_otp_error( __( 'درگاه پرداخت پیکربندی نشده است.', 'aramesh' ), 501 );
	}

	// حالت دستی/آزمایشی: اعطای مستقیم برای نمایش جریان.
	aramesh_grant_course( get_current_user_id(), $course_id, 'manual-demo' );
	wp_send_json_success(
		array(
			'message'  => __( 'دسترسی دوره فعال شد.', 'aramesh' ),
			'redirect' => home_url( '/account/courses' ),
		)
	);
}
add_action( 'wp_ajax_aramesh_mock_checkout', 'aramesh_ajax_mock_checkout' );

/**
 * ذخیره شماره در پروفایل ادمین (نمایش).
 */
function aramesh_show_mobile_field( $user ) {
	$mobile = get_user_meta( $user->ID, 'aramesh_mobile', true );
	$region = get_user_meta( $user->ID, 'aramesh_region', true );
	echo '<h2>' . esc_html__( 'اطلاعات Aramesh', 'aramesh' ) . '</h2><table class="form-table"><tr><th>' . esc_html__( 'موبایل', 'aramesh' ) . '</th><td>' . esc_html( $mobile ) . '</td></tr><tr><th>' . esc_html__( 'منطقه', 'aramesh' ) . '</th><td>' . esc_html( $region ) . '</td></tr></table>';
}
add_action( 'show_user_profile', 'aramesh_show_mobile_field' );
add_action( 'edit_user_profile', 'aramesh_show_mobile_field' );
