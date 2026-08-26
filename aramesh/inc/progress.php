<?php
/**
 * ردیابی پیشرفت جلسات.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * ثبت/به‌روزرسانی پیشرفت یک جلسه.
 *
 * @param int  $lesson_id
 * @param int  $position  ثانیه آخرین موقعیت پخش.
 * @param bool $completed تکمیل شده؟
 */
function aramesh_update_progress( $lesson_id, $position = 0, $completed = null ) {
	$user_id = get_current_user_id();
	if ( ! $user_id ) {
		return false;
	}
	$lesson_id = (int) $lesson_id;
	$course_id = (int) get_post_meta( $lesson_id, '_aramesh_course_id', true );

	global $wpdb;
	$table   = aramesh_progress_table();
	$existing = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE user_id=%d AND lesson_id=%d", $user_id, $lesson_id ) );

	$done = 0;
	if ( null !== $completed ) {
		$done = $completed ? 1 : 0;
	} elseif ( $existing ) {
		$done = (int) $existing->completed;
	}

	$data = array(
		'user_id'   => $user_id,
		'course_id' => $course_id,
		'lesson_id' => $lesson_id,
		'position'  => max( 0, (int) $position ),
		'completed' => $done,
		'last_seen' => current_time( 'mysql' ),
	);

	if ( $existing ) {
		$wpdb->update( $table, $data, array( 'id' => $existing->id ) );
	} else {
		$wpdb->insert( $table, $data );
	}
	return true;
}

/**
 * وضعیت پیشرفت یک جلسه برای کاربر فعلی.
 *
 * @return array{completed:bool,position:int}
 */
function aramesh_get_lesson_progress( $lesson_id, $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	$out     = array( 'completed' => false, 'position' => 0 );
	if ( ! $user_id ) {
		return $out;
	}
	global $wpdb;
	$table = aramesh_progress_table();
	$row   = $wpdb->get_row( $wpdb->prepare( "SELECT completed, position FROM {$table} WHERE user_id=%d AND lesson_id=%d", $user_id, (int) $lesson_id ) );
	if ( $row ) {
		$out['completed'] = (bool) $row->completed;
		$out['position']  = (int) $row->position;
	}
	return $out;
}

/**
 * درصد پیشرفت یک دوره برای کاربر.
 *
 * @return int 0..100
 */
function aramesh_course_progress_percent( $course_id, $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	$lessons = aramesh_get_course_lessons( $course_id );
	$total   = count( $lessons );
	if ( ! $total || ! $user_id ) {
		return 0;
	}
	global $wpdb;
	$table = aramesh_progress_table();
	$done  = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE user_id=%d AND course_id=%d AND completed=1", $user_id, (int) $course_id ) );
	return (int) round( ( $done / $total ) * 100 );
}

/**
 * آخرین جلسه ناتمام برای «ادامه یادگیری».
 *
 * @return int|0 lesson_id
 */
function aramesh_continue_lesson_id( $course_id, $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	$lessons = aramesh_get_course_lessons( $course_id );
	if ( empty( $lessons ) ) {
		return 0;
	}
	foreach ( $lessons as $lesson ) {
		$p = aramesh_get_lesson_progress( $lesson->ID, $user_id );
		if ( ! $p['completed'] ) {
			return $lesson->ID;
		}
	}
	// همه تمام شده — به اولین برگرد.
	return $lessons[0]->ID;
}

/**
 * مجموع زمان تماشا (تخمینی از موقعیت‌ها) — برای داشبورد.
 * برگردانده‌شده بر حسب دقیقه.
 */
function aramesh_total_watch_minutes( $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	if ( ! $user_id ) {
		return 0;
	}
	global $wpdb;
	$table = aramesh_progress_table();
	$secs  = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COALESCE(SUM(position),0) FROM {$table} WHERE user_id=%d", $user_id ) );
	return (int) round( $secs / 60 );
}

/**
 * تعداد دوره‌های تکمیل‌شده کاربر.
 */
function aramesh_completed_courses_count( $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	$count   = 0;
	foreach ( aramesh_get_user_course_ids( $user_id ) as $cid ) {
		if ( aramesh_course_progress_percent( $cid, $user_id ) >= 100 ) {
			$count++;
		}
	}
	return $count;
}

/**
 * REST endpoint برای ذخیره پیشرفت از پخش‌کننده.
 */
function aramesh_register_progress_route() {
	register_rest_route(
		'aramesh/v1',
		'/progress',
		array(
			'methods'             => 'POST',
			'permission_callback' => function () {
				return is_user_logged_in();
			},
			'callback'            => 'aramesh_rest_save_progress',
			'args'                => array(
				'lesson_id' => array( 'required' => true, 'sanitize_callback' => 'absint' ),
				'position'  => array( 'required' => false, 'sanitize_callback' => 'absint' ),
				'completed' => array( 'required' => false ),
			),
		)
	);
}
add_action( 'rest_api_init', 'aramesh_register_progress_route' );

/**
 * هندلر REST.
 */
function aramesh_rest_save_progress( WP_REST_Request $request ) {
	$lesson_id = (int) $request->get_param( 'lesson_id' );
	$course_id = (int) get_post_meta( $lesson_id, '_aramesh_course_id', true );

	// فقط اگر کاربر مالک دوره است.
	if ( ! aramesh_user_has_course( $course_id ) ) {
		return new WP_REST_Response( array( 'ok' => false, 'message' => 'no_access' ), 403 );
	}
	$position  = (int) $request->get_param( 'position' );
	$completed = $request->get_param( 'completed' );
	$completed = ( null === $completed ) ? null : ( 'true' === $completed || '1' === (string) $completed || true === $completed );

	aramesh_update_progress( $lesson_id, $position, $completed );

	return new WP_REST_Response(
		array(
			'ok'      => true,
			'percent' => aramesh_course_progress_percent( $course_id ),
		),
		200
	);
}
