<?php
/**
 * Aramesh theme bootstrap.
 *
 * قالب فارسی RTL برای روان‌شناس و فروش دوره‌های ویدیویی.
 * این فایل فقط ماژول‌های داخل inc/ را بارگذاری می‌کند تا منطق تفکیک‌شده بماند.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

define( 'ARAMESH_VERSION', '1.0.0' );
define( 'ARAMESH_DIR', get_template_directory() );
define( 'ARAMESH_URI', get_template_directory_uri() );

/**
 * بارگذاری ماژول‌های قالب.
 * ترتیب مهم است: setup و post-types قبل از بقیه.
 */
$aramesh_modules = array(
	'inc/setup.php',            // theme supports, menus, image sizes
	'inc/enqueue.php',          // css/js
	'inc/post-types.php',       // CPT: course, lesson, testimonial
	'inc/taxonomies.php',       // course_category
	'inc/meta-boxes.php',       // فیلدهای بومی بدون وابستگی به Page Builder
	'inc/theme-options.php',    // Customizer: تلگرام، تلفن، آدرس، پروفایل دکتر ...
	'inc/template-functions.php', // توابع کمکی نمایش
	'inc/auth-otp.php',         // ورود/ثبت‌نام با OTP (abstraction)
	'inc/entitlements.php',     // مالکیت دوره + جدول اختصاصی
	'inc/progress.php',         // پیشرفت جلسات + جدول اختصاصی
	'inc/video.php',            // پخش‌کننده ویدیوی محافظت‌شده (abstraction)
	'inc/seo.php',              // schema, OpenGraph, breadcrumb
	'inc/demo-content.php',     // بارگذار محتوای نمونه (اختیاری، از پیشخوان)
);

foreach ( $aramesh_modules as $aramesh_module ) {
	$aramesh_path = ARAMESH_DIR . '/' . $aramesh_module;
	if ( file_exists( $aramesh_path ) ) {
		require_once $aramesh_path;
	}
}
unset( $aramesh_modules, $aramesh_module, $aramesh_path );
