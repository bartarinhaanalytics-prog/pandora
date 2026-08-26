<?php
/**
 * راه‌اندازی قالب: پشتیبانی‌ها، منوها، اندازه تصاویر.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * ثبت قابلیت‌های قالب.
 */
function aramesh_setup() {
	// ترجمه‌پذیری.
	load_theme_textdomain( 'aramesh', ARAMESH_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'post-thumbnails',
		array( 'post', 'course', 'lesson', 'page' )
	);

	// اندازه‌های تصویر متناسب با کارت‌ها و کاورها.
	add_image_size( 'aramesh-card', 640, 420, true );      // کارت دوره/مقاله
	add_image_size( 'aramesh-cover', 1280, 720, true );    // کاور 16:9
	add_image_size( 'aramesh-avatar', 240, 240, true );    // چهره مدرس/کاربر
	add_image_size( 'aramesh-wide', 1600, 900, true );     // هیرو

	// منوها.
	register_nav_menus(
		array(
			'primary'      => __( 'منوی اصلی', 'aramesh' ),
			'footer_links' => __( 'دسترسی سریع (فوتر)', 'aramesh' ),
			'footer_topics'=> __( 'موضوعات اصلی (فوتر)', 'aramesh' ),
			'account'      => __( 'منوی حساب کاربری', 'aramesh' ),
		)
	);
}
add_action( 'after_setup_theme', 'aramesh_setup' );

/**
 * Walker منوی اصلی سازگار با Bootstrap (nav-item / nav-link + dropdown).
 */
class Aramesh_Nav_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="dropdown-menu">';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_child = in_array( 'menu-item-has-children', $classes, true );

		$li_classes = array( 'nav-item' );
		if ( $has_child ) {
			$li_classes[] = 'dropdown';
		}
		if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true ) ) {
			$li_classes[] = 'current-menu-item';
		}

		$output .= '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';

		$link_class = ( 0 === $depth ) ? 'nav-link' : 'dropdown-item';
		if ( $has_child && 0 === $depth ) {
			$link_class .= ' dropdown-toggle';
		}

		$atts  = ' class="' . esc_attr( $link_class ) . '"';
		$atts .= $item->url ? ' href="' . esc_url( $item->url ) . '"' : ' href="#"';
		if ( $has_child && 0 === $depth ) {
			$atts .= ' data-bs-toggle="dropdown" aria-expanded="false"';
		}
		$title  = apply_filters( 'the_title', $item->title, $item->ID );
		$output .= '<a' . $atts . '>' . esc_html( $title ) . '</a>';
	}
}

/**
 * عرض محتوا برای embedها.
 */
function aramesh_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'aramesh_content_width', 1280 );
}
add_action( 'after_setup_theme', 'aramesh_content_width', 0 );

/**
 * ناحیه‌های ابزارک.
 */
function aramesh_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'کناره مقاله', 'aramesh' ),
			'id'            => 'sidebar-article',
			'description'   => __( 'در صفحه مقاله و آرشیو مجله نمایش داده می‌شود.', 'aramesh' ),
			'before_widget' => '<section id="%1$s" class="widget card-soft p-4 mb-4 %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title h5 mb-3">',
			'after_title'   => '</h3>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'فوتر — ستون قابل ویرایش', 'aramesh' ),
			'id'            => 'footer-extra',
			'description'   => __( 'ستون اختیاری در فوتر.', 'aramesh' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-col-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'aramesh_widgets_init' );

/**
 * افزودن کلاس‌های کمکی به body.
 */
function aramesh_body_classes( $classes ) {
	$classes[] = 'aramesh';
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	if ( is_page_template( 'single-lesson.php' ) || is_singular( 'lesson' ) ) {
		$classes[] = 'is-player';
	}
	return $classes;
}
add_filter( 'body_class', 'aramesh_body_classes' );

/**
 * pingback header.
 */
function aramesh_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aramesh_pingback_header' );

/**
 * فعال‌سازی قالب: ساخت جداول و صفحات و flush.
 */
function aramesh_after_switch_theme() {
	if ( function_exists( 'aramesh_install_tables' ) ) {
		aramesh_install_tables();
	}
	if ( function_exists( 'aramesh_register_post_types' ) ) {
		aramesh_register_post_types();
	}
	if ( function_exists( 'aramesh_register_taxonomies' ) ) {
		aramesh_register_taxonomies();
	}
	if ( function_exists( 'aramesh_ensure_pages' ) ) {
		aramesh_ensure_pages();
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'aramesh_after_switch_theme' );
