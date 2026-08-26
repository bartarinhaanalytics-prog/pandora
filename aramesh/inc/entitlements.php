<?php
/**
 * مالکیت دوره (Entitlement) با جدول اختصاصی.
 *
 * به‌جای user_meta سریالایز، از جدول با ایندکس روی user_id/course_id استفاده می‌شود
 * تا در مقیاس بالا کارایی حفظ شود.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * نام جداول.
 */
function aramesh_entitlements_table() {
	global $wpdb;
	return $wpdb->prefix . 'aramesh_entitlements';
}
function aramesh_progress_table() {
	global $wpdb;
	return $wpdb->prefix . 'aramesh_lesson_progress';
}

/**
 * ساخت جداول هنگام فعال‌سازی قالب.
 */
function aramesh_install_tables() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$charset_collate = $wpdb->get_charset_collate();

	$ent = aramesh_entitlements_table();
	$sql1 = "CREATE TABLE {$ent} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		user_id BIGINT UNSIGNED NOT NULL,
		course_id BIGINT UNSIGNED NOT NULL,
		source VARCHAR(40) NOT NULL DEFAULT 'manual',
		order_ref VARCHAR(120) NOT NULL DEFAULT '',
		status VARCHAR(20) NOT NULL DEFAULT 'active',
		created_at DATETIME NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY user_course (user_id, course_id),
		KEY course_id (course_id),
		KEY status (status)
	) {$charset_collate};";
	dbDelta( $sql1 );

	$prog = aramesh_progress_table();
	$sql2 = "CREATE TABLE {$prog} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		user_id BIGINT UNSIGNED NOT NULL,
		course_id BIGINT UNSIGNED NOT NULL,
		lesson_id BIGINT UNSIGNED NOT NULL,
		position INT UNSIGNED NOT NULL DEFAULT 0,
		completed TINYINT(1) NOT NULL DEFAULT 0,
		last_seen DATETIME NOT NULL,
		PRIMARY KEY  (id),
		UNIQUE KEY user_lesson (user_id, lesson_id),
		KEY user_course (user_id, course_id),
		KEY lesson_id (lesson_id)
	) {$charset_collate};";
	dbDelta( $sql2 );

	update_option( 'aramesh_db_version', '1.0.0' );
}

/**
 * بررسی نسخه جدول در هر بار لود (برای نصب دستی/به‌روزرسانی).
 */
function aramesh_maybe_install_tables() {
	if ( get_option( 'aramesh_db_version' ) !== '1.0.0' ) {
		aramesh_install_tables();
	}
}
add_action( 'admin_init', 'aramesh_maybe_install_tables' );

/**
 * آیا کاربر به دوره دسترسی دارد؟ (ادمین همیشه دارد).
 *
 * @param int $course_id
 * @param int $user_id 0 = کاربر فعلی.
 * @return bool
 */
function aramesh_user_has_course( $course_id, $user_id = 0 ) {
	$course_id = (int) $course_id;
	$user_id   = $user_id ? (int) $user_id : get_current_user_id();
	if ( ! $user_id || ! $course_id ) {
		return false;
	}
	if ( user_can( $user_id, 'manage_options' ) ) {
		return true;
	}
	global $wpdb;
	$table = aramesh_entitlements_table();
	$found = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id FROM {$table} WHERE user_id = %d AND course_id = %d AND status = 'active' LIMIT 1",
			$user_id,
			$course_id
		)
	);
	return (bool) $found;
}

/**
 * اعطای دسترسی دوره به کاربر (پس از پرداخت موفق یا توسط منشی).
 *
 * @return bool موفقیت.
 */
function aramesh_grant_course( $user_id, $course_id, $source = 'manual', $order_ref = '' ) {
	$user_id   = (int) $user_id;
	$course_id = (int) $course_id;
	if ( ! $user_id || ! $course_id ) {
		return false;
	}
	global $wpdb;
	$table = aramesh_entitlements_table();
	$exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE user_id=%d AND course_id=%d", $user_id, $course_id ) );
	if ( $exists ) {
		$wpdb->update( $table, array( 'status' => 'active' ), array( 'id' => $exists ) );
	} else {
		$wpdb->insert(
			$table,
			array(
				'user_id'    => $user_id,
				'course_id'  => $course_id,
				'source'     => sanitize_text_field( $source ),
				'order_ref'  => sanitize_text_field( $order_ref ),
				'status'     => 'active',
				'created_at' => current_time( 'mysql' ),
			)
		);
	}
	/**
	 * پس از اعطای دوره.
	 */
	do_action( 'aramesh_course_granted', $user_id, $course_id, $source, $order_ref );
	return true;
}

/**
 * لغو دسترسی.
 */
function aramesh_revoke_course( $user_id, $course_id ) {
	global $wpdb;
	$table = aramesh_entitlements_table();
	return (bool) $wpdb->update( $table, array( 'status' => 'revoked' ), array( 'user_id' => (int) $user_id, 'course_id' => (int) $course_id ) );
}

/**
 * فهرست دوره‌های کاربر (آرایه‌ای از course_id).
 *
 * @return int[]
 */
function aramesh_get_user_course_ids( $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	if ( ! $user_id ) {
		return array();
	}
	global $wpdb;
	$table = aramesh_entitlements_table();
	$ids   = $wpdb->get_col( $wpdb->prepare( "SELECT course_id FROM {$table} WHERE user_id=%d AND status='active' ORDER BY created_at DESC", $user_id ) );
	return array_map( 'intval', (array) $ids );
}

/**
 * تعداد دانشجویان یک دوره.
 */
function aramesh_course_student_count( $course_id ) {
	global $wpdb;
	$table = aramesh_entitlements_table();
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE course_id=%d AND status='active'", (int) $course_id ) );
}
