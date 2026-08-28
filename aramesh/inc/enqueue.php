<?php
/**
 * بارگذاری استایل و اسکریپت‌ها.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * صف استایل و اسکریپت‌های فرانت.
 */
function aramesh_enqueue_assets() {
	$ver = ARAMESH_VERSION;

	// Bootstrap 5 RTL (محلی، بدون CDN).
	wp_enqueue_style( 'bootstrap-rtl', ARAMESH_URI . '/assets/css/bootstrap.rtl.min.css', array(), '5.3.3' );

	// فونت فارسی (fallback به سیستم؛ فایل فونت داخل بسته نیست).
	wp_enqueue_style( 'aramesh-fonts', ARAMESH_URI . '/assets/css/fonts.css', array(), $ver );

	// استایل اصلی قالب.
	wp_enqueue_style( 'aramesh-theme', ARAMESH_URI . '/assets/css/theme.css', array( 'bootstrap-rtl', 'aramesh-fonts' ), $ver );

	// style.css قالب (هدر رسمی).
	wp_enqueue_style( 'aramesh-style', get_stylesheet_uri(), array( 'aramesh-theme' ), $ver );

	// Bootstrap bundle (شامل Popper) — در فوتر.
	wp_enqueue_script( 'bootstrap-bundle', ARAMESH_URI . '/assets/js/bootstrap.bundle.min.js', array(), '5.3.3', true );

	// اسکریپت قالب.
	wp_enqueue_script( 'aramesh-theme', ARAMESH_URI . '/assets/js/theme.js', array( 'bootstrap-bundle' ), $ver, true );

	// داده‌های موردنیاز JS (AJAX + nonce).
	wp_localize_script(
		'aramesh-theme',
		'ArameshData',
		array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'restUrl'    => esc_url_raw( rest_url( 'aramesh/v1/' ) ),
			'nonce'      => wp_create_nonce( 'aramesh_nonce' ),
			'restNonce'  => wp_create_nonce( 'wp_rest' ),
			'isLoggedIn' => is_user_logged_in(),
			'i18n'       => array(
				'sending'     => __( 'در حال ارسال…', 'aramesh' ),
				'resendIn'    => __( 'ارسال مجدد تا %s ثانیه', 'aramesh' ),
				'resend'      => __( 'ارسال مجدد کد', 'aramesh' ),
				'invalidCode' => __( 'کد واردشده معتبر نیست.', 'aramesh' ),
				'genericErr'  => __( 'خطایی رخ داد. دوباره تلاش کنید.', 'aramesh' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aramesh_enqueue_assets' );

/**
 * پخش‌کننده ویدیو فقط در صفحه جلسه (سبک نگه‌داشتن بقیه صفحات).
 */
function aramesh_enqueue_player_assets() {
	// استایل پخش‌کننده در صفحهٔ جلسه و صفحهٔ دوره (برای تریلر) لازم است.
	if ( is_singular( array( 'lesson', 'course' ) ) || is_page_template( 'single-lesson.php' ) ) {
		wp_enqueue_style( 'aramesh-player', ARAMESH_URI . '/assets/css/player.css', array( 'aramesh-theme' ), ARAMESH_VERSION );
	}
	// اسکریپت پخش‌کننده فقط در صفحهٔ جلسه.
	if ( is_singular( 'lesson' ) || is_page_template( 'single-lesson.php' ) ) {
		wp_enqueue_script( 'aramesh-player', ARAMESH_URI . '/assets/js/player.js', array( 'aramesh-theme' ), ARAMESH_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'aramesh_enqueue_player_assets', 20 );

/**
 * preconnect برای فونت‌ها (در صورت میزبانی خارجی توسط سایت).
 */
function aramesh_resource_hints( $hints, $relation_type ) {
	return $hints;
}
add_filter( 'wp_resource_hints', 'aramesh_resource_hints', 10, 2 );

/**
 * کلاس منوی اصلی بوت‌استرپ روی آیتم‌های منو.
 */
function aramesh_nav_link_class( $classes, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$classes[] = 'nav-item';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'aramesh_nav_link_class', 10, 3 );
