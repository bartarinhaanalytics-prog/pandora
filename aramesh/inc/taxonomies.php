<?php
/**
 * دسته‌بندی دوره‌ها.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * ثبت taxonomy دسته‌بندی دوره.
 */
function aramesh_register_taxonomies() {
	register_taxonomy(
		'course_category',
		array( 'course' ),
		array(
			'labels'            => array(
				'name'          => __( 'دسته‌بندی دوره‌ها', 'aramesh' ),
				'singular_name' => __( 'دسته دوره', 'aramesh' ),
				'search_items'  => __( 'جستجوی دسته', 'aramesh' ),
				'all_items'     => __( 'همه دسته‌ها', 'aramesh' ),
				'edit_item'     => __( 'ویرایش دسته', 'aramesh' ),
				'add_new_item'  => __( 'افزودن دسته جدید', 'aramesh' ),
				'menu_name'     => __( 'دسته دوره', 'aramesh' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'course-category', 'with_front' => false ),
		)
	);

	// موضوعات محتوایی مشترک (برای «انتخاب مسئله/موضوع» در صفحه اصلی).
	register_taxonomy(
		'topic',
		array( 'course', 'post' ),
		array(
			'labels'            => array(
				'name'          => __( 'موضوعات', 'aramesh' ),
				'singular_name' => __( 'موضوع', 'aramesh' ),
				'menu_name'     => __( 'موضوعات', 'aramesh' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'topic', 'with_front' => false ),
		)
	);
}
add_action( 'init', 'aramesh_register_taxonomies' );
