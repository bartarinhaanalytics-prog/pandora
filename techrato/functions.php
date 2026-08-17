<?php
/**
 * Techrato theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TECHRATO_VERSION', '1.13.0' );
define( 'TECHRATO_DIR', get_template_directory() );
define( 'TECHRATO_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function techrato_setup() {
	load_theme_textdomain( 'techrato', TECHRATO_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary'          => __( 'منوی اصلی', 'techrato' ),
		'trending'         => __( 'نوار پرطرفدارها', 'techrato' ),
		'footer-techrato'  => __( 'فوتر - تکراتو', 'techrato' ),
		'footer-categories' => __( 'فوتر - دسته‌بندی‌ها', 'techrato' ),
		'search-suggestions' => __( 'کلمات پرجستجو (پنل جستجو)', 'techrato' ),
	) );

	add_image_size( 'techrato-hero', 900, 560, true );
	add_image_size( 'techrato-card', 480, 360, true );
	add_image_size( 'techrato-list', 260, 176, true );
	add_image_size( 'techrato-thumb', 168, 168, true );
	add_image_size( 'techrato-square', 88, 88, true );
}
add_action( 'after_setup_theme', 'techrato_setup' );

/**
 * Enqueue styles and scripts.
 */
function techrato_assets() {
	wp_enqueue_style(
		'techrato-fonts',
		'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'techrato-style', get_stylesheet_uri(), array(), TECHRATO_VERSION );
	wp_enqueue_script( 'techrato-main', TECHRATO_URI . '/assets/js/main.js', array(), TECHRATO_VERSION, true );
	wp_localize_script( 'techrato-main', 'techratoData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'techrato_like' ),
	) );

	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'techrato_assets' );

/**
 * AJAX like toggle for single posts. One like per browser, tracked with a
 * cookie (no account system in this theme, so there's no per-user identity
 * to key off instead).
 */
function techrato_ajax_toggle_like() {
	check_ajax_referer( 'techrato_like', 'nonce' );

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( ! $post_id || 'publish' !== get_post_status( $post_id ) ) {
		wp_send_json_error();
	}

	$cookie_name = 'techrato_liked_' . $post_id;
	$likes       = (int) get_post_meta( $post_id, 'techrato_likes', true );
	$already     = ! empty( $_COOKIE[ $cookie_name ] );

	if ( $already ) {
		$likes = max( 0, $likes - 1 );
		setcookie( $cookie_name, '', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	} else {
		$likes++;
		setcookie( $cookie_name, '1', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	update_post_meta( $post_id, 'techrato_likes', $likes );

	wp_send_json_success( array(
		'count' => $likes,
		'liked' => ! $already,
	) );
}
add_action( 'wp_ajax_techrato_toggle_like', 'techrato_ajax_toggle_like' );
add_action( 'wp_ajax_nopriv_techrato_toggle_like', 'techrato_ajax_toggle_like' );

/**
 * Content width for embeds/oEmbed.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}

/**
 * Register widget areas (footer columns can be edited from Appearance > Menus,
 * but we still expose a sidebar in case a widget is preferred over a menu).
 */
function techrato_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'سایدبار مقالات', 'techrato' ),
		'id'            => 'sidebar-article',
		'description'   => __( 'ابزارک‌های نمایش داده‌شده در کنار مقالات و صفحات دسته‌بندی', 'techrato' ),
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'techrato_widgets_init' );

/**
 * Includes.
 */
require TECHRATO_DIR . '/inc/template-tags.php';
require TECHRATO_DIR . '/inc/customizer.php';

/**
 * Fallback menu for the primary nav when no menu has been assigned yet,
 * so the header never looks broken on a fresh install.
 */
function techrato_fallback_primary_menu( $class = 'main-nav' ) {
	$items = array(
		'تکراتو'      => home_url( '/' ),
		'کسب و کار'   => home_url( '/' ),
		'علمی'        => home_url( '/' ),
		'راهنمای خرید' => home_url( '/' ),
		'آموزش'       => home_url( '/' ),
		'ویدیو'       => home_url( '/' ),
		'نقد و بررسی' => home_url( '/' ),
		'خودرو'       => home_url( '/' ),
		'تکنولوژی'    => home_url( '/' ),
	);
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $label => $url ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Fallback trending-search keywords shown in the search overlay when no
 * "کلمات پرجستجو" menu has been assigned yet.
 */
function techrato_fallback_search_suggestions() {
	$items = array( 'اپل', 'هوش مصنوعی', 'ویندوز', 'OpenAi', 'موبایل', 'اینترنت' );
	echo '<ul class="suggestions-nav">';
	foreach ( $items as $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/?s=' . rawurlencode( $label ) ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Custom excerpt length/more for card components.
 */
function techrato_excerpt_length( $length ) {
	return 22;
}
add_filter( 'excerpt_length', 'techrato_excerpt_length' );

function techrato_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'techrato_excerpt_more' );

/**
 * Register block editor / theme color palette to match the brand.
 */
function techrato_editor_settings() {
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'فیروزه‌ای تکراتو', 'techrato' ),
			'slug'  => 'accent',
			'color' => '#22d3b5',
		),
		array(
			'name'  => __( 'پس‌زمینه تیره', 'techrato' ),
			'slug'  => 'background',
			'color' => '#0a1613',
		),
		array(
			'name'  => __( 'سفید', 'techrato' ),
			'slug'  => 'text',
			'color' => '#f3f6f5',
		),
	) );
}
add_action( 'after_setup_theme', 'techrato_editor_settings' );
