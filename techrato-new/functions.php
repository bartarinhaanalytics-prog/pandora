<?php
/**
 * Techrato theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TECHRATO_VERSION', '2.1.0' );
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

	// Show the site's own Persian typography inside the editor, so writers see
	// roughly what readers will. Works for the classic editor and the block
	// editor alike.
	add_theme_support( 'editor-styles' );
	add_editor_style( array(
		'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700&display=swap',
		'assets/css/editor-style.css',
	) );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary'          => __( 'منوی اصلی', 'techrato' ),
		'trending'         => __( 'نوار پرطرفدارها', 'techrato' ),
		'footer-techrato'  => __( 'فوتر - تکراتو', 'techrato' ),
		'footer-categories' => __( 'فوتر - دسته‌بندی‌ها', 'techrato' ),
		'social'            => __( 'فوتر - شبکه‌های اجتماعی', 'techrato' ),
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
		'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'techrato-style', get_stylesheet_uri(), array(), TECHRATO_VERSION );
	wp_enqueue_script( 'techrato-main', TECHRATO_URI . '/assets/js/main.js', array(), TECHRATO_VERSION, true );
	wp_localize_script( 'techrato-main', 'techratoData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'techrato_like' ),
		'postId'  => is_singular( 'post' ) ? get_queried_object_id() : 0,
	) );

	if ( is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'techrato_assets' );

/**
 * WP Rocket compatibility: the theme's own script drives immediate UI
 * (menu, search, theme toggle, like/save buttons) and must run right away.
 * WP Rocket's "Delay JavaScript Execution" / "Combine JS" features hold
 * scripts back until the visitor's first click/scroll/touch, which makes
 * every one of those controls look broken (or only "wake up" after an
 * unrelated interaction) until then — so this file is excluded from both.
 */
add_filter( 'rocket_delay_js_exclusions', function ( $exclusions ) {
	$exclusions[] = 'main.js';
	return $exclusions;
} );
add_filter( 'rocket_exclude_js', function ( $exclude_list ) {
	$exclude_list[] = 'main.js';
	return $exclude_list;
} );
add_filter( 'rocket_exclude_defer_js', function ( $exclude_list ) {
	$exclude_list[] = 'main.js';
	return $exclude_list;
} );

/**
 * Belt-and-suspenders: WP Rocket (and similar optimizers like Autoptimize
 * or LiteSpeed Cache) also recognize a `data-no-optimize="1"` attribute
 * directly on the <script> tag itself, which works even if the admin-panel
 * exclusion list above is misconfigured or cleared later.
 */
add_filter( 'script_loader_tag', function ( $tag, $handle ) {
	if ( 'techrato-main' === $handle ) {
		$tag = str_replace( ' src=', ' data-no-optimize="1" data-cfasync="false" data-rocket-defer-exclude data-pagespeed-no-defer src=', $tag );
	}
	return $tag;
}, 10, 2 );

/**
 * Load the Google Fonts stylesheet without blocking the initial render —
 * it swaps to the real font once it arrives (font-display:swap is already
 * set on the URL), so there's no layout shift, just a faster first paint.
 */
function techrato_async_font_style( $html, $handle ) {
	if ( 'techrato-fonts' === $handle ) {
		$html = str_replace( "rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $html );
	}
	return $html;
}
add_filter( 'style_loader_tag', 'techrato_async_font_style', 10, 2 );

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

/**
 * Admin performance tweaks for a very large site.
 *
 * Measured with Query Monitor on the posts list screen: 9.34s total, 4.46s of
 * it in the database across 128 queries (25 flagged slow). The fixes below
 * target the most expensive ones.
 */

/**
 * Drop the "all dates" month filter above the posts list.
 *
 * It runs SELECT DISTINCT YEAR(post_date), MONTH(post_date) FROM wp_posts,
 * which cannot use an index (the date functions defeat it) and so scans every
 * row. On 317k posts that single query measured ~1 second on every load of
 * every list screen.
 */
add_filter( 'disable_months_dropdown', '__return_true' );

/**
 * Skip the "Right Now"/activity dashboard widgets, which run their own
 * counting queries across posts and comments on every dashboard load.
 */
function techrato_trim_dashboard_widgets() {
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );      // WordPress news (external HTTP).
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );    // Other news (external HTTP).
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );   // Recent posts + comments queries.
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'techrato_trim_dashboard_widgets' );

/**
 * Trim the admin bar's comment bubble query. WordPress counts approved and
 * trashed comments on every admin page load; on a 94k-row comment table those
 * two COUNT(*) queries measured ~0.45s combined. The moderation count still
 * appears on the Comments screen itself.
 */
function techrato_remove_comment_bubble( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'comments' );
}
add_action( 'admin_bar_menu', 'techrato_remove_comment_bubble', 999 );

/* -------------------------------------------------------------------------
 * Outbound HTTP requests from wp-admin
 *
 * Every admin page makes blocking calls to external APIs (update checks,
 * plugin licence servers, feeds). When the server cannot reach a host, each
 * call sits waiting for its timeout — WordPress defaults to 5s but plugins
 * routinely ask for 30s — and the page cannot finish until it gives up. That
 * is the same cost on every screen, which matches "everything is slow".
 * ---------------------------------------------------------------------- */

/**
 * Cap every outbound request at 3 seconds. A reachable host answers in well
 * under that; an unreachable one now costs 3s instead of up to 30s.
 */
function techrato_cap_http_timeout( $args ) {
	if ( empty( $args['timeout'] ) || $args['timeout'] > 3 ) {
		$args['timeout'] = 3;
	}
	return $args;
}
add_filter( 'http_request_args', 'techrato_cap_http_timeout', 999 );

/**
 * Return a page of posts for a category, used by the archive tabs and the
 * "مشاهده مطالب بیشتر" button.
 *
 * Deliberately nonce-free: it only reads published posts that are already
 * public, and a nonce baked into a page-cached HTML file goes stale and starts
 * rejecting perfectly ordinary visitors.
 */
function techrato_ajax_load_posts() {
	$term_id = isset( $_POST['term'] ) ? absint( $_POST['term'] ) : 0;
	$paged   = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;
	$paged   = max( 1, $paged );
	$per     = isset( $_POST['per'] ) ? absint( $_POST['per'] ) : 0;
	$days    = isset( $_POST['days'] ) ? absint( $_POST['days'] ) : 0;
	$sort    = isset( $_POST['sort'] ) ? sanitize_key( $_POST['sort'] ) : 'date';

	// Only cards the theme actually ships, so the request cannot ask the
	// server to include an arbitrary file.
	$cards = array( 'news', 'category-post', 'app', 'related', 'side-story', 'editor-mini' );
	$card  = isset( $_POST['card'] ) ? sanitize_key( $_POST['card'] ) : 'news';
	if ( ! in_array( $card, $cards, true ) ) {
		$card = 'news';
	}

	$args = array(
		'post_status'         => 'publish',
		'paged'               => $paged,
		'posts_per_page'      => $per ? $per : (int) get_option( 'posts_per_page', 10 ),
		'ignore_sticky_posts' => true,
	);

	if ( $term_id ) {
		$term = get_term( $term_id, 'category' );
		if ( ! $term instanceof WP_Term ) {
			wp_send_json_error( array( 'message' => __( 'دسته پیدا نشد.', 'techrato' ) ), 404 );
		}
		$args['cat'] = $term_id;
	}

	if ( $days ) {
		$args['date_query'] = array( array( 'after' => $days . ' days ago' ) );
	}

	if ( 'views' === $sort ) {
		$args['meta_key'] = 'techrato_views';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
	}

	$query = new WP_Query( $args );

	ob_start();
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'template-parts/card', $card );
	}
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array(
		'html'     => $html,
		'paged'    => $paged,
		'maxPages' => (int) $query->max_num_pages,
		'found'    => (int) $query->found_posts,
	) );
}
add_action( 'wp_ajax_techrato_load_posts', 'techrato_ajax_load_posts' );
add_action( 'wp_ajax_nopriv_techrato_load_posts', 'techrato_ajax_load_posts' );

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
require TECHRATO_DIR . '/inc/home-settings.php';
require TECHRATO_DIR . '/inc/category-image.php';
require TECHRATO_DIR . '/inc/category-icon.php';
require TECHRATO_DIR . '/inc/mega-menu.php';
require TECHRATO_DIR . '/inc/link-ads.php';
require TECHRATO_DIR . '/inc/ads.php';
require TECHRATO_DIR . '/inc/seo.php';
require TECHRATO_DIR . '/inc/editor.php';

/**
 * Fallback menu for the primary nav when no menu has been assigned yet,
 * so the header never looks broken on a fresh install.
 */
function techrato_fallback_primary_menu() {
	// Real categories beat invented ones: a site with no menu assigned still
	// gets navigation that goes somewhere useful.
	$terms = get_categories( array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 8,
		'parent'     => 0,
		'hide_empty' => true,
	) );

	if ( ! $terms ) {
		return;
	}

	foreach ( $terms as $term ) {
		$children = get_categories( array(
			'parent'     => $term->term_id,
			'hide_empty' => true,
			'number'     => 14,
		) );

		printf(
			'<div class="nav-item%s"><a href="%s">%s%s</a>',
			$children ? ' has-mega' : '',
			esc_url( get_category_link( $term->term_id ) ),
			esc_html( $term->name ),
			$children ? techrato_mega_chevron() : ''
		);

		if ( $children ) {
			$desc = wp_strip_all_tags( term_description( $term->term_id, 'category' ) );

			echo '<div class="mega-menu"><div class="mega-menu__inner">';
			echo '<div class="mega-menu__title">';
			echo '<span>' . esc_html__( 'دسته‌بندی', 'techrato' ) . '</span>';
			echo '<h3>' . esc_html( $term->name ) . '</h3>';
			if ( $desc ) {
				echo '<p>' . esc_html( wp_trim_words( $desc, 24, '…' ) ) . '</p>';
			}
			echo '</div><div class="mega-menu__links">';
			foreach ( $children as $child ) {
				printf( '<a href="%s">%s</a>', esc_url( get_category_link( $child->term_id ) ), esc_html( $child->name ) );
			}
			echo '</div>';
			echo techrato_mega_feature( $term ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo '</div></div>';
		}

		echo '</div>';
	}
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
