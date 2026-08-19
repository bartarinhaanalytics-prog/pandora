<?php
/**
 * Techrato theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TECHRATO_VERSION', '1.31.0' );
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
 * Diagnostic for the editorial checkboxes ("انتخاب‌های تحریریه").
 *
 * Visible only to administrators, and only when ?techrato_debug=1 is on the
 * URL. Prints what is actually stored in the database for those flags so we
 * can tell a "nothing is saved" problem apart from a "saved under a different
 * key/value" one. Safe to leave in place; remove once the flags are confirmed.
 */
function techrato_debug_editorial_flags() {
	if ( ! isset( $_GET['techrato_debug'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $wpdb;
	$keys = array( '_featured_one_post_tc', '_featured_post_tc', '_editor_suggestion_tc', '_top_year_tc' );

	echo '<pre style="background:#111;color:#0f0;padding:16px;margin:0;direction:ltr;text-align:left;font-size:13px;overflow:auto;z-index:99999;position:relative;">';
	echo "=== TECHRATO EDITORIAL FLAG DIAGNOSTIC ===\n\n";

	// The plugin writes a row for every post — '' when the box is unchecked and
	// '1' when it is checked. Only the checked ones matter, so count and list
	// those rather than the first N rows of mostly-empty values.
	foreach ( $keys as $key ) {
		$checked = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value != '' AND meta_value != '0' ORDER BY post_id DESC LIMIT 20",
			$key
		) );
		$total = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value != '' AND meta_value != '0'",
			$key
		) );

		printf( "%-24s : %d post(s) ticked\n", $key, $total );

		foreach ( $checked as $post_id ) {
			$status = get_post_status( $post_id );
			printf(
				"    post %-8s status=%-8s value='%s'  %s\n",
				$post_id,
				$status ? $status : 'MISSING',
				get_post_meta( $post_id, $key, true ),
				get_the_title( $post_id )
			);
		}
	}

	// The four lookups above hit the meta_key index and are cheap. Scanning
	// for unknown keys needs a leading-wildcard LIKE, which is a full scan of
	// postmeta and can time out on a large site — so it only runs on demand.
	if ( isset( $_GET['techrato_debug_scan'] ) ) {
		echo "\n--- meta keys ending in _tc (slow scan) ---\n";
		$found = $wpdb->get_col( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE meta_key LIKE '%\_tc' LIMIT 50" );
		echo $found ? implode( "\n", $found ) : '(none)';
	} else {
		echo "\n(add &techrato_debug_scan=1 to also scan for unknown keys — slow)\n";
	}

	// --- Why is wp-admin slow? The usual suspects, measured rather than guessed.
	echo "\n--- PERFORMANCE ---\n";

	// Autoloaded options load on EVERY request. Healthy is under ~800KB.
	$auto = $wpdb->get_row( "SELECT COUNT(*) AS cnt, SUM(LENGTH(option_value)) AS sz FROM {$wpdb->options} WHERE autoload = 'yes'" );
	printf( "autoloaded options  : %d options, %s KB\n", $auto->cnt, number_format( $auto->sz / 1024, 1 ) );

	$big = $wpdb->get_results( "SELECT option_name, LENGTH(option_value) AS sz FROM {$wpdb->options} WHERE autoload = 'yes' ORDER BY sz DESC LIMIT 8" );
	foreach ( $big as $opt ) {
		printf( "    %-46s %s KB\n", $opt->option_name, number_format( $opt->sz / 1024, 1 ) );
	}

	$revisions = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'revision'" );
	printf( "\npost revisions      : %s\n", number_format( $revisions ) );

	$sizes = $wpdb->get_results( "SELECT table_name, table_rows, ROUND((data_length + index_length)/1048576, 1) AS mb FROM information_schema.TABLES WHERE table_schema = DATABASE() ORDER BY (data_length + index_length) DESC LIMIT 6" );
	echo "\nlargest tables      :\n";
	foreach ( $sizes as $t ) {
		printf( "    %-30s ~%s rows, %s MB\n", $t->table_name, number_format( $t->table_rows ), $t->mb );
	}

	printf( "\nactive plugins      : %d\n", count( (array) get_option( 'active_plugins', array() ) ) );
	printf( "object cache        : %s\n", wp_using_ext_object_cache() ? 'yes' : 'no (every query hits the DB)' );

	// Which caching backends this server could actually use. OPcache matters
	// most: without it PHP recompiles every file on every request, which is
	// pure overhead independent of any database work.
	$opcache = function_exists( 'opcache_get_status' ) ? @opcache_get_status( false ) : false;
	printf(
		"OPcache             : %s\n",
		( $opcache && ! empty( $opcache['opcache_enabled'] ) ) ? 'ENABLED' : 'OFF  <-- recompiles all PHP every request'
	);
	printf( "APCu available      : %s\n", function_exists( 'apcu_fetch' ) ? 'yes' : 'no' );
	printf( "Memcached available : %s\n", class_exists( 'Memcached' ) ? 'yes' : 'no' );
	printf( "Redis available     : %s\n", class_exists( 'Redis' ) ? 'yes' : 'no' );
	printf( "PHP version         : %s\n", phpversion() );
	printf( "memory limit        : %s\n", ini_get( 'memory_limit' ) );

	echo "\n</pre>";
}
add_action( 'wp_body_open', 'techrato_debug_editorial_flags' );

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
 * Record each outbound request and how long it took, so we can see which
 * hosts are actually costing time before deciding what to block.
 */
$GLOBALS['techrato_http_log']   = array();
$GLOBALS['techrato_http_start'] = array();

function techrato_http_start( $pre, $args, $url ) {
	$GLOBALS['techrato_http_start'][ $url ] = microtime( true );
	return $pre;
}
add_filter( 'pre_http_request', 'techrato_http_start', 1, 3 );

function techrato_http_finish( $response, $context, $class, $args, $url ) {
	$started  = isset( $GLOBALS['techrato_http_start'][ $url ] ) ? $GLOBALS['techrato_http_start'][ $url ] : microtime( true );
	$duration = microtime( true ) - $started;

	$GLOBALS['techrato_http_log'][] = array(
		'url'      => $url,
		'seconds'  => $duration,
		'error'    => is_wp_error( $response ) ? $response->get_error_message() : '',
	);
}
add_action( 'http_api_debug', 'techrato_http_finish', 10, 5 );

/**
 * Print the log at the bottom of any admin page for administrators. Shown
 * only when there was at least one request, so it stays out of the way once
 * the noisy ones are gone.
 */
function techrato_print_http_log() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Always print, even with zero requests: an empty panel proves the code
	// ran and found nothing, which is a different conclusion from no panel
	// at all (theme not active, or the hook never fired).
	if ( empty( $GLOBALS['techrato_http_log'] ) ) {
		echo '<div style="background:#111;color:#0f0;padding:14px;margin:20px;direction:ltr;text-align:left;font:13px monospace;">';
		echo 'OUTBOUND HTTP ON THIS PAGE: 0 requests (theme v' . esc_html( TECHRATO_VERSION ) . ')';
		echo '</div>';
		return;
	}

	$log   = $GLOBALS['techrato_http_log'];
	$total = 0;
	foreach ( $log as $entry ) {
		$total += $entry['seconds'];
	}

	echo '<div style="background:#111;color:#0f0;padding:14px;margin:20px;direction:ltr;text-align:left;font:13px monospace;white-space:pre;overflow:auto;">';
	printf( "OUTBOUND HTTP ON THIS PAGE: %d request(s), %.2fs total\n\n", count( $log ), $total );

	usort( $log, function ( $a, $b ) {
		return $b['seconds'] <=> $a['seconds'];
	} );

	foreach ( $log as $entry ) {
		printf(
			"%6.2fs  %s%s\n",
			$entry['seconds'],
			esc_html( $entry['url'] ),
			$entry['error'] ? '   [ERROR: ' . esc_html( $entry['error'] ) . ']' : ''
		);
	}
	echo '</div>';
}
add_action( 'admin_footer', 'techrato_print_http_log' );

/**
 * Hosts to refuse outright. Requests to these return instantly instead of
 * waiting for a timeout. Add a host here once the log above shows it is slow
 * and its feature is not needed.
 */
function techrato_blocked_http_hosts() {
	return array(
		// 'api.wordpress.org',  // update checks — uncomment only if it proves slow
	);
}

function techrato_block_slow_hosts( $pre, $args, $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( $host && in_array( strtolower( $host ), techrato_blocked_http_hosts(), true ) ) {
		return new WP_Error( 'techrato_blocked', 'Blocked by theme: ' . $host );
	}
	return $pre;
}
add_filter( 'pre_http_request', 'techrato_block_slow_hosts', 5, 3 );

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
