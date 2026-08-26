<?php
/**
 * انواع محتوای سفارشی: دوره، جلسه، نظر (تستیمونیال).
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * ثبت CPTها.
 */
function aramesh_register_post_types() {

	// ---------- دوره ----------
	register_post_type(
		'course',
		array(
			'labels'       => array(
				'name'               => __( 'دوره‌ها', 'aramesh' ),
				'singular_name'      => __( 'دوره', 'aramesh' ),
				'add_new'            => __( 'دوره جدید', 'aramesh' ),
				'add_new_item'       => __( 'افزودن دوره جدید', 'aramesh' ),
				'edit_item'          => __( 'ویرایش دوره', 'aramesh' ),
				'new_item'           => __( 'دوره جدید', 'aramesh' ),
				'view_item'          => __( 'مشاهده دوره', 'aramesh' ),
				'search_items'       => __( 'جستجوی دوره', 'aramesh' ),
				'not_found'          => __( 'دوره‌ای یافت نشد.', 'aramesh' ),
				'all_items'          => __( 'همه دوره‌ها', 'aramesh' ),
				'menu_name'          => __( 'دوره‌ها', 'aramesh' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-welcome-learn-more',
			'menu_position'=> 5,
			'rewrite'      => array( 'slug' => 'courses', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author', 'comments' ),
			'show_in_rest' => true,
		)
	);

	// ---------- جلسه ----------
	register_post_type(
		'lesson',
		array(
			'labels'       => array(
				'name'          => __( 'جلسه‌ها', 'aramesh' ),
				'singular_name' => __( 'جلسه', 'aramesh' ),
				'add_new_item'  => __( 'افزودن جلسه جدید', 'aramesh' ),
				'edit_item'     => __( 'ویرایش جلسه', 'aramesh' ),
				'all_items'     => __( 'همه جلسه‌ها', 'aramesh' ),
				'menu_name'     => __( 'جلسه‌ها', 'aramesh' ),
				'not_found'     => __( 'جلسه‌ای یافت نشد.', 'aramesh' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-video-alt3',
			'menu_position'=> 6,
			// URL آموزشی: /learn/{course}/{lesson}
			'rewrite'      => array( 'slug' => 'learn', 'with_front' => false ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'show_in_rest' => true,
		)
	);

	// ---------- نظر مشتری (اختیاری) ----------
	register_post_type(
		'testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'نظرات', 'aramesh' ),
				'singular_name' => __( 'نظر', 'aramesh' ),
				'add_new_item'  => __( 'افزودن نظر جدید', 'aramesh' ),
				'menu_name'     => __( 'نظرات', 'aramesh' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-format-quote',
			'menu_position'=> 7,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'aramesh_register_post_types' );

/**
 * افزودن قاعده بازنویسی برای مسیر /learn/{course}/{lesson}.
 * جلسه به دوره از طریق متا _aramesh_course_id متصل است.
 */
function aramesh_lesson_rewrite() {
	add_rewrite_rule(
		'^learn/([^/]+)/([^/]+)/?$',
		'index.php?lesson=$matches[2]&aramesh_course=$matches[1]',
		'top'
	);
}
add_action( 'init', 'aramesh_lesson_rewrite' );

/**
 * ثبت query var کمکی.
 */
function aramesh_query_vars( $vars ) {
	$vars[] = 'aramesh_course';
	return $vars;
}
add_filter( 'query_vars', 'aramesh_query_vars' );

/**
 * ساخت permalink صحیح جلسه: /learn/{course-slug}/{lesson-slug}
 */
function aramesh_lesson_permalink( $permalink, $post ) {
	if ( 'lesson' !== $post->post_type ) {
		return $permalink;
	}
	$course_id = (int) get_post_meta( $post->ID, '_aramesh_course_id', true );
	$course    = $course_id ? get_post( $course_id ) : null;
	$course_slug = $course ? $course->post_name : 'course';
	return home_url( user_trailingslashit( 'learn/' . $course_slug . '/' . $post->post_name ) );
}
add_filter( 'post_type_link', 'aramesh_lesson_permalink', 10, 2 );
