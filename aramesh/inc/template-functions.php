<?php
/**
 * توابع کمکی نمایش و راه‌اندازی صفحات.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * جلسه‌های یک دوره به‌ترتیب.
 *
 * @param int $course_id
 * @return WP_Post[]
 */
function aramesh_get_course_lessons( $course_id ) {
	static $cache = array();
	$course_id = (int) $course_id;
	if ( isset( $cache[ $course_id ] ) ) {
		return $cache[ $course_id ];
	}
	$lessons = get_posts(
		array(
			'post_type'      => 'lesson',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'meta_key'       => '_aramesh_course_id',
			'meta_value'     => $course_id,
		)
	);
	$cache[ $course_id ] = $lessons;
	return $lessons;
}

/**
 * دوره یک جلسه.
 */
function aramesh_lesson_course_id( $lesson_id ) {
	return (int) get_post_meta( $lesson_id, '_aramesh_course_id', true );
}

/**
 * تبدیل متن چندخطی به آرایه آیتم‌ها.
 *
 * @return string[]
 */
function aramesh_lines_to_array( $text ) {
	$text  = (string) $text;
	$lines = preg_split( '/\r\n|\r|\n/', $text );
	$out   = array();
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}
	return $out;
}

/**
 * پارس FAQ به آرایه‌ای از {q, a}.
 *
 * @return array<int,array{q:string,a:string}>
 */
function aramesh_parse_faq( $text ) {
	$out = array();
	foreach ( aramesh_lines_to_array( $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( ! empty( $parts[0] ) ) {
			$out[] = array( 'q' => $parts[0], 'a' => isset( $parts[1] ) ? $parts[1] : '' );
		}
	}
	return $out;
}

/**
 * پارس منابع به آرایه‌ای از {label, url}.
 */
function aramesh_parse_resources( $text ) {
	$out = array();
	foreach ( aramesh_lines_to_array( $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( ! empty( $parts[0] ) ) {
			$out[] = array( 'label' => $parts[0], 'url' => isset( $parts[1] ) ? esc_url( $parts[1] ) : '' );
		}
	}
	return $out;
}

/**
 * قالب‌بندی قیمت تومان.
 */
function aramesh_format_toman( $amount ) {
	$amount = (int) $amount;
	return number_format_i18n( $amount );
}

/**
 * قیمت مؤثر دوره (با احتساب تخفیف).
 *
 * @return array{price:int,sale:int,has_sale:bool,effective:int,is_free:bool}
 */
function aramesh_course_price( $course_id ) {
	$price = (int) get_post_meta( $course_id, '_aramesh_price', true );
	$sale  = (int) get_post_meta( $course_id, '_aramesh_sale_price', true );
	$has   = $sale > 0 && $sale < $price;
	return array(
		'price'     => $price,
		'sale'      => $sale,
		'has_sale'  => $has,
		'effective' => $has ? $sale : $price,
		'is_free'   => 0 === $price,
	);
}

/**
 * نمایش HTML قیمت.
 */
function aramesh_price_html( $course_id ) {
	$p = aramesh_course_price( $course_id );
	if ( $p['is_free'] ) {
		return '<span class="course-price course-price--free">' . esc_html__( 'رایگان', 'aramesh' ) . '</span>';
	}
	$html = '';
	if ( $p['has_sale'] ) {
		$html .= '<del class="course-price__old">' . esc_html( aramesh_format_toman( $p['price'] ) ) . '</del> ';
	}
	$html .= '<span class="course-price__amount">' . esc_html( aramesh_format_toman( $p['effective'] ) ) . '</span> ';
	$html .= '<span class="course-price__unit">' . esc_html__( 'تومان', 'aramesh' ) . '</span>';
	return $html;
}

/**
 * ستاره امتیاز.
 */
function aramesh_stars( $rating ) {
	$rating = max( 0, min( 5, (int) $rating ) );
	$out    = '<span class="stars" aria-label="' . esc_attr( sprintf( __( 'امتیاز %d از ۵', 'aramesh' ), $rating ) ) . '">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$out .= $i <= $rating ? '★' : '☆';
	}
	$out .= '</span>';
	return $out;
}

/**
 * آدرس صفحات کلیدی (بر اساس ذخیره در option هنگام فعال‌سازی، با fallback به مسیر).
 */
function aramesh_page_url( $key ) {
	$map = get_option( 'aramesh_pages', array() );
	if ( ! empty( $map[ $key ] ) ) {
		$url = get_permalink( (int) $map[ $key ] );
		if ( $url ) {
			return $url;
		}
	}
	// fallback بر اساس مسیرهای پیش‌فرض.
	$paths = array(
		'about'          => '/about',
		'login'          => '/login',
		'register_path'  => '/register-path',
		'register_iran'  => '/register-iran',
		'register_intl'  => '/register-international',
		'account'        => '/account',
		'my_courses'     => '/account/courses',
		'contact'        => '/contact',
		'faq'            => '/faq',
		'legal'          => '/terms',
		'blog'           => '/blog',
	);
	return isset( $paths[ $key ] ) ? home_url( $paths[ $key ] ) : home_url( '/' );
}

/**
 * آدرس آرشیو دوره‌ها.
 */
function aramesh_courses_url() {
	return get_post_type_archive_link( 'course' );
}

/**
 * نام دکتر/برند.
 */
function aramesh_brand_name() {
	$name = aramesh_option( 'doctor_name', get_bloginfo( 'name' ) );
	return $name ? $name : get_bloginfo( 'name' );
}

/**
 * رندر کارت دوره (داخل یا خارج از loop).
 *
 * @param int $course_id
 */
function aramesh_render_course_card( $course_id ) {
	$course_id = (int) $course_id;
	set_query_var( 'aramesh_card_id', $course_id );
	get_template_part( 'template-parts/course', 'card' );
}

/**
 * رندر کارت مقاله.
 */
function aramesh_render_article_card( $post_id ) {
	set_query_var( 'aramesh_card_id', (int) $post_id );
	get_template_part( 'template-parts/article', 'card' );
}

/**
 * Breadcrumb ساده و قابل‌استفاده در schema.
 *
 * @return array<int,array{name:string,url:string}>
 */
function aramesh_breadcrumb_items() {
	$items = array( array( 'name' => __( 'خانه', 'aramesh' ), 'url' => home_url( '/' ) ) );

	if ( is_singular( 'course' ) ) {
		$items[] = array( 'name' => __( 'دوره‌ها', 'aramesh' ), 'url' => aramesh_courses_url() );
		$items[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_post_type_archive( 'course' ) ) {
		$items[] = array( 'name' => __( 'دوره‌ها', 'aramesh' ), 'url' => aramesh_courses_url() );
	} elseif ( is_singular( 'lesson' ) ) {
		$cid = aramesh_lesson_course_id( get_the_ID() );
		$items[] = array( 'name' => __( 'دوره‌ها', 'aramesh' ), 'url' => aramesh_courses_url() );
		if ( $cid ) {
			$items[] = array( 'name' => get_the_title( $cid ), 'url' => get_permalink( $cid ) );
		}
		$items[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_singular( 'post' ) ) {
		$items[] = array( 'name' => __( 'مجله', 'aramesh' ), 'url' => aramesh_page_url( 'blog' ) );
		$cats = get_the_category();
		if ( $cats ) {
			$items[] = array( 'name' => $cats[0]->name, 'url' => get_category_link( $cats[0]->term_id ) );
		}
		$items[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_category() ) {
		$items[] = array( 'name' => __( 'مجله', 'aramesh' ), 'url' => aramesh_page_url( 'blog' ) );
		$items[] = array( 'name' => single_cat_title( '', false ), 'url' => '' );
	} elseif ( is_page() ) {
		$items[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_search() ) {
		$items[] = array( 'name' => __( 'جستجو', 'aramesh' ), 'url' => '' );
	}
	return $items;
}

/**
 * چاپ breadcrumb HTML.
 */
function aramesh_breadcrumb() {
	$items = aramesh_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}
	echo '<nav class="aramesh-breadcrumb" aria-label="' . esc_attr__( 'مسیر', 'aramesh' ) . '"><ol class="breadcrumb-list">';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		if ( $i === $last || empty( $item['url'] ) ) {
			echo '<li class="is-current" aria-current="page">' . esc_html( $item['name'] ) . '</li>';
		} else {
			echo '<li><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['name'] ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/**
 * منوی اصلی fallback وقتی هنوز منویی ساخته نشده.
 */
function aramesh_primary_menu_fallback() {
	$links = array(
		array( home_url( '/' ), __( 'خانه', 'aramesh' ) ),
		array( aramesh_courses_url(), __( 'دوره‌ها', 'aramesh' ) ),
		array( aramesh_page_url( 'blog' ), __( 'مقالات', 'aramesh' ) ),
		array( aramesh_page_url( 'about' ), __( 'درباره من', 'aramesh' ) ),
		array( aramesh_page_url( 'contact' ), __( 'تماس با ما', 'aramesh' ) ),
	);
	echo '<ul id="primary-menu" class="navbar-nav gap-lg-1 align-items-lg-center">';
	foreach ( $links as $link ) {
		echo '<li class="nav-item"><a class="nav-link" href="' . esc_url( $link[0] ) . '">' . esc_html( $link[1] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * ایجاد صفحات پیش‌فرض و منو هنگام فعال‌سازی.
 */
function aramesh_ensure_pages() {
	$defs = array(
		'home'          => array( 'title' => 'صفحه اصلی', 'slug' => 'home', 'tpl' => '', 'parent' => 0 ),
		'about'         => array( 'title' => 'درباره من', 'slug' => 'about', 'tpl' => 'page-about.php', 'parent' => 0 ),
		'login'         => array( 'title' => 'ورود و عضویت', 'slug' => 'login', 'tpl' => 'page-login.php', 'parent' => 0 ),
		'register_path' => array( 'title' => 'انتخاب مسیر ثبت‌نام', 'slug' => 'register-path', 'tpl' => 'page-registration-path.php', 'parent' => 0 ),
		'register_iran' => array( 'title' => 'ثبت‌نام داخل ایران', 'slug' => 'register-iran', 'tpl' => 'page-register-iran.php', 'parent' => 0 ),
		'register_intl' => array( 'title' => 'ثبت‌نام خارج از ایران', 'slug' => 'register-international', 'tpl' => 'page-register-international.php', 'parent' => 0 ),
		'account'       => array( 'title' => 'حساب کاربری', 'slug' => 'account', 'tpl' => 'page-dashboard.php', 'parent' => 0 ),
		'my_courses'    => array( 'title' => 'دوره‌های من', 'slug' => 'courses', 'tpl' => 'page-my-courses.php', 'parent' => 'account' ),
		'contact'       => array( 'title' => 'تماس با ما', 'slug' => 'contact', 'tpl' => 'page-contact.php', 'parent' => 0 ),
		'faq'           => array( 'title' => 'سوالات متداول', 'slug' => 'faq', 'tpl' => 'page-faq.php', 'parent' => 0 ),
		'legal'         => array( 'title' => 'قوانین و حریم خصوصی', 'slug' => 'terms', 'tpl' => 'page-legal.php', 'parent' => 0 ),
		'blog'          => array( 'title' => 'مجله', 'slug' => 'blog', 'tpl' => '', 'parent' => 0 ),
	);

	$map = get_option( 'aramesh_pages', array() );
	$ids = array();

	// مرحله اول: صفحات بدون والد.
	foreach ( $defs as $key => $def ) {
		if ( ! empty( $def['parent'] ) ) {
			continue;
		}
		$ids[ $key ] = aramesh_upsert_page( $def['title'], $def['slug'], $def['tpl'], 0, $map[ $key ] ?? 0 );
	}
	// مرحله دوم: صفحات دارای والد.
	foreach ( $defs as $key => $def ) {
		if ( empty( $def['parent'] ) ) {
			continue;
		}
		$parent_id = $ids[ $def['parent'] ] ?? 0;
		$ids[ $key ] = aramesh_upsert_page( $def['title'], $def['slug'], $def['tpl'], $parent_id, $map[ $key ] ?? 0 );
	}

	update_option( 'aramesh_pages', $ids );

	// صفحه اصلی و صفحه مطالب.
	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $ids['home'] );
	}
	if ( ! empty( $ids['blog'] ) ) {
		update_option( 'page_for_posts', (int) $ids['blog'] );
	}

	aramesh_ensure_primary_menu( $ids );
}

/**
 * ایجاد یا به‌روزرسانی یک صفحه.
 *
 * @return int page ID.
 */
function aramesh_upsert_page( $title, $slug, $template, $parent = 0, $known_id = 0 ) {
	// اگر قبلاً ساخته شده و هنوز هست.
	if ( $known_id && get_post( $known_id ) ) {
		if ( $template ) {
			update_post_meta( $known_id, '_wp_page_template', $template );
		}
		return (int) $known_id;
	}
	// جستجو بر اساس slug.
	$existing = get_page_by_path( $parent ? ( get_post_field( 'post_name', $parent ) . '/' . $slug ) : $slug );
	if ( $existing ) {
		if ( $template ) {
			update_post_meta( $existing->ID, '_wp_page_template', $template );
		}
		return (int) $existing->ID;
	}
	$page_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_parent'  => (int) $parent,
			'post_content' => '',
		)
	);
	if ( $page_id && ! is_wp_error( $page_id ) && $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}
	return (int) $page_id;
}

/**
 * ساخت منوی اصلی و اتصال به محل primary در صورت نبود.
 */
function aramesh_ensure_primary_menu( $ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! empty( $locations['primary'] ) && wp_get_nav_menu_object( $locations['primary'] ) ) {
		return; // قبلاً تنظیم شده.
	}
	$menu_name = __( 'منوی اصلی', 'aramesh' );
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? $menu->term_id : wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}
	// اگر منو خالی است، آیتم‌ها را اضافه کن.
	$items = wp_get_nav_menu_items( $menu_id );
	if ( empty( $items ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'خانه', 'aramesh' ), 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish' ) );
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'دوره‌ها', 'aramesh' ), 'menu-item-url' => aramesh_courses_url(), 'menu-item-status' => 'publish' ) );
		if ( ! empty( $ids['blog'] ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'مقالات', 'aramesh' ), 'menu-item-object' => 'page', 'menu-item-object-id' => (int) $ids['blog'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		if ( ! empty( $ids['about'] ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'درباره من', 'aramesh' ), 'menu-item-object' => 'page', 'menu-item-object-id' => (int) $ids['about'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		if ( ! empty( $ids['contact'] ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'تماس با ما', 'aramesh' ), 'menu-item-object' => 'page', 'menu-item-object-id' => (int) $ids['contact'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
	}
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * فیلتر و مرتب‌سازی آرشیو دوره‌ها از طریق پارامترهای URL.
 * ?ccat=slug  &  ?sort=newest|price-asc|price-desc|popular
 */
function aramesh_course_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! $query->is_post_type_archive( 'course' ) && ! $query->is_tax( 'course_category' ) && ! $query->is_tax( 'topic' ) ) {
		return;
	}
	$query->set( 'posts_per_page', 9 );

	// فیلتر دسته.
	$ccat = isset( $_GET['ccat'] ) ? sanitize_title( wp_unslash( $_GET['ccat'] ) ) : '';
	if ( $ccat && $query->is_post_type_archive( 'course' ) ) {
		$query->set(
			'tax_query',
			array(
				array( 'taxonomy' => 'course_category', 'field' => 'slug', 'terms' => $ccat ),
			)
		);
	}

	// مرتب‌سازی.
	$sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : '';
	switch ( $sort ) {
		case 'price-asc':
			$query->set( 'meta_key', '_aramesh_price' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'ASC' );
			break;
		case 'price-desc':
			$query->set( 'meta_key', '_aramesh_price' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );
			break;
		case 'popular':
			$query->set( 'meta_key', '_aramesh_bestseller' );
			$query->set( 'orderby', array( 'meta_value' => 'DESC', 'date' => 'DESC' ) );
			break;
		case 'newest':
		default:
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
			break;
	}
}
add_action( 'pre_get_posts', 'aramesh_course_archive_query' );

/**
 * صفحه‌بندی با ظاهر قالب.
 */
function aramesh_pagination( $query = null ) {
	$args = array(
		'mid_size'  => 1,
		'prev_text' => aramesh_icon( 'chevron', 18 ),
		'next_text' => aramesh_icon( 'arrow-left', 18 ),
		'type'      => 'array',
	);
	if ( $query instanceof WP_Query ) {
		$args['total']   = $query->max_num_pages;
		$args['current'] = max( 1, (int) $query->get( 'paged' ) );
	}
	$links = paginate_links( $args );
	if ( empty( $links ) ) {
		return;
	}
	echo '<nav class="aramesh-pagination" aria-label="' . esc_attr__( 'صفحه‌بندی', 'aramesh' ) . '">';
	foreach ( $links as $link ) {
		echo wp_kses_post( $link );
	}
	echo '</nav>';
}

/**
 * شورت‌کد فرم دریافت شماره (Lead capture / خبرنامه پیامکی).
 * [aramesh_lead button="عضویت" placeholder="شماره موبایل"]
 */
function aramesh_lead_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'button'      => __( 'عضویت', 'aramesh' ),
			'placeholder' => __( 'شماره موبایل خود را وارد کنید', 'aramesh' ),
		),
		$atts,
		'aramesh_lead'
	);
	ob_start();
	?>
	<form class="aramesh-lead d-flex flex-column flex-sm-row gap-2" data-lead-form>
		<label class="visually-hidden" for="lead-mobile"><?php echo esc_html( $atts['placeholder'] ); ?></label>
		<input type="tel" id="lead-mobile" name="mobile" class="form-control" placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>" inputmode="numeric" required>
		<button type="submit" class="btn btn-primary flex-shrink-0" data-lead-submit><?php echo esc_html( $atts['button'] ); ?></button>
	</form>
	<div class="form-message mt-2" data-lead-message role="status"></div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'aramesh_lead', 'aramesh_lead_shortcode' );

/**
 * AJAX: ثبت lead.
 */
function aramesh_ajax_lead() {
	check_ajax_referer( 'aramesh_nonce', 'nonce' );
	$mobile = aramesh_normalize_mobile( isset( $_POST['mobile'] ) ? wp_unslash( $_POST['mobile'] ) : '' );
	if ( ! $mobile ) {
		wp_send_json_error( array( 'message' => __( 'شماره موبایل معتبر نیست.', 'aramesh' ) ), 400 );
	}
	$leads = get_option( 'aramesh_leads', array() );
	if ( ! in_array( $mobile, $leads, true ) ) {
		$leads[] = $mobile;
		update_option( 'aramesh_leads', array_slice( $leads, -5000 ) );
	}
	/**
	 * برای اتصال به سرویس پیامک/خبرنامه.
	 */
	do_action( 'aramesh_new_lead', $mobile );
	wp_send_json_success( array( 'message' => __( 'ثبت شد! به‌زودی با شما در ارتباط خواهیم بود.', 'aramesh' ) ) );
}
add_action( 'wp_ajax_aramesh_lead', 'aramesh_ajax_lead' );
add_action( 'wp_ajax_nopriv_aramesh_lead', 'aramesh_ajax_lead' );

/**
 * AJAX: فرم تماس.
 */
function aramesh_ajax_contact() {
	check_ajax_referer( 'aramesh_nonce', 'nonce' );
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );

	if ( ! $name || ! $message || ( ! $email && ! $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'لطفاً نام، پیام و یک راه ارتباطی را کامل کنید.', 'aramesh' ) ), 400 );
	}
	if ( $email && ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'ایمیل معتبر نیست.', 'aramesh' ) ), 400 );
	}

	$to      = aramesh_option( 'email', get_option( 'admin_email' ) );
	$subject = sprintf( __( 'پیام تماس جدید از %s', 'aramesh' ), get_bloginfo( 'name' ) );
	$body    = sprintf( "نام: %s\nایمیل: %s\nتلفن: %s\n\nپیام:\n%s", $name, $email, $phone, $message );
	$headers = array();
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}
	$sent = wp_mail( $to, $subject, $body, $headers );

	/**
	 * برای اتصال به CRM/سرویس دیگر.
	 */
	do_action( 'aramesh_contact_submitted', compact( 'name', 'email', 'phone', 'message', 'sent' ) );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => __( 'پیام شما ارسال شد. به‌زودی پاسخ می‌دهیم.', 'aramesh' ) ) );
	}
	// حتی اگر wp_mail پیکربندی نشده باشد، پیام را ذخیره و موفق اعلام می‌کنیم.
	$store   = get_option( 'aramesh_messages', array() );
	$store[] = array( 'name' => $name, 'email' => $email, 'phone' => $phone, 'message' => $message, 'time' => current_time( 'mysql' ) );
	update_option( 'aramesh_messages', array_slice( $store, -1000 ) );
	wp_send_json_success( array( 'message' => __( 'پیام شما ثبت شد. به‌زودی با شما در ارتباط خواهیم بود.', 'aramesh' ) ) );
}
add_action( 'wp_ajax_aramesh_contact', 'aramesh_ajax_contact' );
add_action( 'wp_ajax_nopriv_aramesh_contact', 'aramesh_ajax_contact' );

/**
 * محدودسازی طول excerpt و «...».
 */
function aramesh_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'aramesh_excerpt_length' );

function aramesh_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'aramesh_excerpt_more' );

/**
 * مجموعه آیکون‌های خطی سبک (SVG inline). بدون وابستگی خارجی.
 *
 * @param string $name نام آیکون.
 * @param int    $size اندازه px.
 * @return string
 */
function aramesh_icon( $name, $size = 24 ) {
	$paths = array(
		'leaf'      => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6"/>',
		'arrow-left'=> '<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>',
		'arrow-down'=> '<line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>',
		'play'      => '<polygon points="6 3 20 12 6 21 6 3"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/>',
		'video'     => '<rect x="2" y="5" width="14" height="14" rx="3"/><path d="m22 8-6 4 6 4V8Z"/>',
		'users'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'heart'     => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/>',
		'brain'     => '<path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08A3 3 0 0 1 2.5 12a3 3 0 0 1 1.6-4.4A2.5 2.5 0 0 1 6.5 3.5 2.5 2.5 0 0 1 9.5 2Z"/>',
		'sprout'    => '<path d="M7 20h10"/><path d="M12 20v-8"/><path d="M12 12c0-3-2-5-5-5 0 3 2 5 5 5Z"/><path d="M12 10c0-2 2-4 5-4 0 3-2 4-5 4Z"/>',
		'shield'    => '<path d="M12 2 4 5v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V5Z"/><path d="m9 12 2 2 4-4"/>',
		'check'     => '<polyline points="20 6 9 17 4 12"/>',
		'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.4-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2Z"/>',
		'mail'      => '<rect x="2" y="4" width="20" height="16" rx="3"/><path d="m22 6-10 7L2 6"/>',
		'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'send'      => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
		'telegram'  => '<path d="M22 3 2 11l6 2 2 6 3-4 5 4Z"/>',
		'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/>',
		'youtube'   => '<rect x="2" y="5" width="20" height="14" rx="4"/><polygon points="10 9 15 12 10 15 10 9"/>',
		'search'    => '<circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
		'chevron'   => '<polyline points="9 6 15 12 9 18"/>',
		'book'      => '<path d="M4 19V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2Z"/><path d="M4 19a2 2 0 0 0 2 2h13"/>',
		'infinity'  => '<path d="M6 8a4 4 0 1 0 0 8c3 0 4-4 6-4s3 4 6 4a4 4 0 1 0 0-8c-3 0-4 4-6 4s-3-4-6-4Z"/>',
		'star'      => '<polygon points="12 2 15 9 22 9.3 16.5 13.9 18.5 21 12 17 5.5 21 7.5 13.9 2 9.3 9 9 12 2"/>',
		'quote'     => '<path d="M6 11H3v-1a4 4 0 0 1 4-4"/><path d="M15 11h-3v-1a4 4 0 0 1 4-4"/><path d="M3 11v3a3 3 0 0 0 3 3"/><path d="M12 11v3a3 3 0 0 0 3 3"/>',
		'award'     => '<circle cx="12" cy="8" r="6"/><path d="m8.2 13-1.2 8 5-3 5 3-1.2-8"/>',
		'calendar'  => '<rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4"/>',
		'layers'    => '<polygon points="12 2 22 8 12 14 2 8 12 2"/><polyline points="2 16 12 22 22 16"/>',
		'headset'   => '<path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="2" y="14" width="5" height="7" rx="2"/><rect x="17" y="14" width="5" height="7" rx="2"/>',
	);
	$inner = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['check'];
	return sprintf(
		'<svg class="a-ico a-ico--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$inner
	);
}

/**
 * چاپ لوگو (تصویری در صورت وجود، در غیر این‌صورت متنی با نشان برگ).
 */
function aramesh_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="brand" href="%1$s"><span class="brand__mark">%2$s</span><span class="brand__text"><span class="brand__name">%3$s</span><span class="brand__sub">%4$s</span></span></a>',
		esc_url( home_url( '/' ) ),
		aramesh_icon( 'leaf', 24 ),
		esc_html( aramesh_brand_name() ),
		esc_html( aramesh_option( 'doctor_title', 'روانشناس و درمانگر' ) )
	);
}

/**
 * افزودن id به سرتیترها و ساخت فهرست مطالب (TOC) از محتوای مقاله.
 *
 * @param string $html محتوای پردازش‌شده (بعد از the_content فیلترها).
 * @return array{toc:string,html:string}
 */
function aramesh_toc_and_content( $html ) {
	if ( ! $html || ! class_exists( 'DOMDocument' ) ) {
		return array( 'toc' => '', 'html' => $html );
	}
	$headings = array();
	// یافتن h2ها.
	if ( ! preg_match_all( '/<h2\b[^>]*>(.*?)<\/h2>/is', $html, $m ) ) {
		return array( 'toc' => '', 'html' => $html );
	}
	$i = 0;
	$html = preg_replace_callback(
		'/<h2\b([^>]*)>(.*?)<\/h2>/is',
		function ( $match ) use ( &$headings, &$i ) {
			$i++;
			$text = trim( wp_strip_all_tags( $match[2] ) );
			$id   = 'sec-' . $i;
			$headings[] = array( 'id' => $id, 'text' => $text );
			$attrs = $match[1];
			if ( false === strpos( $attrs, 'id=' ) ) {
				$attrs .= ' id="' . $id . '"';
			}
			return '<h2' . $attrs . '>' . $match[2] . '</h2>';
		},
		$html
	);
	if ( count( $headings ) < 2 ) {
		return array( 'toc' => '', 'html' => $html );
	}
	$toc  = '<nav class="toc" aria-label="' . esc_attr__( 'فهرست مطالب', 'aramesh' ) . '"><div class="fw-bold mb-2">' . esc_html__( 'فهرست مطالب', 'aramesh' ) . '</div><ol>';
	foreach ( $headings as $h ) {
		$toc .= '<li><a href="#' . esc_attr( $h['id'] ) . '">' . esc_html( $h['text'] ) . '</a></li>';
	}
	$toc .= '</ol></nav>';
	return array( 'toc' => $toc, 'html' => $html );
}

/**
 * زمان مطالعه تخمینی مقاله (دقیقه).
 */
function aramesh_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = max( 1, mb_strlen( wp_strip_all_tags( $content ), 'UTF-8' ) / 6 ); // تقریب برای فارسی.
	return max( 1, (int) round( $words / 200 ) );
}
