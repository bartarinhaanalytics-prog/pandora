<?php
/**
 * بارگذار محتوای نمونه (اختیاری) — از پیشخوان: ابزارها » محتوای نمونه Aramesh.
 * محتوای placeholder و قابل‌جایگزین ایجاد می‌کند تا جریان‌ها قابل تست باشند.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * افزودن صفحه ابزار.
 */
function aramesh_demo_menu() {
	add_management_page(
		__( 'محتوای نمونه Aramesh', 'aramesh' ),
		__( 'محتوای نمونه Aramesh', 'aramesh' ),
		'manage_options',
		'aramesh-demo',
		'aramesh_demo_page'
	);
}
add_action( 'admin_menu', 'aramesh_demo_menu' );

/**
 * صفحه ابزار.
 */
function aramesh_demo_page() {
	if ( isset( $_POST['aramesh_demo_seed'] ) && check_admin_referer( 'aramesh_demo', 'aramesh_demo_nonce' ) ) {
		$result = aramesh_seed_demo_content();
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( __( 'محتوای نمونه ساخته شد: %d دوره، %d جلسه، %d مقاله، %d نظر.', 'aramesh' ), $result['courses'], $result['lessons'], $result['articles'], $result['testimonials'] ) ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'محتوای نمونه Aramesh', 'aramesh' ); ?></h1>
		<p><?php esc_html_e( 'با این ابزار چند دوره، جلسه، مقاله و نظر نمونه ساخته می‌شود تا صفحات و جریان‌ها را تست کنید. محتوا idempotent است و دوباره‌سازی نمی‌کند.', 'aramesh' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'aramesh_demo', 'aramesh_demo_nonce' ); ?>
			<p><button type="submit" name="aramesh_demo_seed" value="1" class="button button-primary"><?php esc_html_e( 'ساخت / به‌روزرسانی محتوای نمونه', 'aramesh' ); ?></button></p>
		</form>
	</div>
	<?php
}

/**
 * یافتن پست بر اساس عنوان دقیق و نوع (جایگزین get_page_by_title منسوخ).
 *
 * @return WP_Post|null
 */
function aramesh_find_by_title( $title, $post_type ) {
	$q = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $title,
			'post_status'            => array( 'publish', 'draft', 'pending' ),
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	return $q->have_posts() ? $q->posts[0] : null;
}

/**
 * ساخت محتوای نمونه.
 *
 * @return array
 */
function aramesh_seed_demo_content() {
	$counts = array( 'courses' => 0, 'lessons' => 0, 'articles' => 0, 'testimonials' => 0 );

	// دسته‌بندی دوره‌ها و موضوعات.
	$cats = array( 'اضطراب و نگرانی', 'اعتمادبه‌نفس', 'روابط عاطفی', 'رشد فردی', 'شناخت خود' );
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c, 'course_category' ) ) {
			wp_insert_term( $c, 'course_category' );
		}
		if ( ! term_exists( $c, 'topic' ) ) {
			wp_insert_term( $c, 'topic' );
		}
	}

	$courses = array(
		array(
			'title'    => 'مدیریت اضطراب و ذهن ناآرام',
			'short'    => 'راهکارهای علمی و کاربردی برای شناخت و کاهش اضطراب در زندگی روزمره.',
			'price'    => 2890000,
			'sale'     => 0,
			'cat'      => 'اضطراب و نگرانی',
			'featured' => '1',
			'best'     => '1',
			'level'    => 'همه سطوح',
			'duration' => '۸ ساعت',
			'outcomes' => "شناخت انواع اضطراب و علل بروز آن\nتکنیک‌های موثر برای کاهش استرس و آرام‌سازی ذهن\nمدیریت افکار منفی و نگرانی‌های مداوم\nبهبود تمرکز و افزایش بهره‌وری\nتقویت اعتماد به نفس و احساس امنیت درونی",
			'suitable' => "کسانی که اضطراب و نگرانی‌های مداوم دارند\nافرادی که دچار استرس روزمره و فشار ذهنی هستند\nکسانی که به‌دنبال آرامش ذهنی و کنترل بهتر هیجانات هستند",
			'faq'      => "بعد از خرید چگونه به دوره دسترسی دارم؟ | بلافاصله پس از پرداخت در «دوره‌های من».\nآیا امکان دانلود ویدیوها وجود دارد؟ | خیر، ویدیوها فقط از حساب کاربری و محافظت‌شده قابل مشاهده‌اند.\nمدت زمان دسترسی به دوره چقدر است؟ | دسترسی دائمی است.",
		),
		array(
			'title'    => 'اعتماد به‌نفس و عزت نفس',
			'short'    => 'تقویت خودباوری و ساختن ذهنیت مثبت از خودتان.',
			'price'    => 2490000,
			'sale'     => 1990000,
			'cat'      => 'اعتمادبه‌نفس',
			'featured' => '1',
			'best'     => '',
			'level'    => 'مقدماتی',
			'duration' => '۵.۵ ساعت',
			'outcomes' => "شناخت ریشه‌های کمبود اعتماد به نفس\nتمرین‌های عملی خودباوری\nمدیریت مقایسه اجتماعی\nگفت‌وگوی درونی سالم",
			'suitable' => "کسانی که در تصمیم‌گیری تردید دارند\nافرادی که خود را دست‌کم می‌گیرند",
			'faq'      => "آیا این دوره جایگزین روان‌درمانی است؟ | خیر، آموزشی و مکمل است.",
		),
		array(
			'title'    => 'روابط عاطفی سالم',
			'short'    => 'یادگیری مهارت‌های ارتباطی و ایجاد روابط عمیق و پایدار.',
			'price'    => 3490000,
			'sale'     => 0,
			'cat'      => 'روابط عاطفی',
			'featured' => '',
			'best'     => '1',
			'level'    => 'همه سطوح',
			'duration' => '۱۰ ساعت',
			'outcomes' => "مهارت گفت‌وگوی موثر\nمدیریت تعارض\nمرزهای سالم\nهمدلی و گوش دادن فعال",
			'suitable' => "زوج‌ها و افرادی که به‌دنبال بهبود روابط هستند",
			'faq'      => "برای شرکت نیاز به همراه دارم؟ | خیر، به‌تنهایی هم قابل استفاده است.",
		),
	);

	foreach ( $courses as $cdata ) {
		$existing = aramesh_find_by_title( $cdata["title"], "course" );
		if ( $existing ) {
			$course_id = $existing->ID;
		} else {
			$course_id = wp_insert_post(
				array(
					'post_type'    => 'course',
					'post_status'  => 'publish',
					'post_title'   => $cdata['title'],
					'post_excerpt' => $cdata['short'],
					'post_content' => '<p>' . esc_html( $cdata['short'] ) . '</p><p>در این دوره با رویکردی علمی و کاربردی، گام‌به‌گام مسیر تغییر را طی می‌کنید.</p>',
				)
			);
			$counts['courses']++;
		}
		update_post_meta( $course_id, '_aramesh_short_desc', $cdata['short'] );
		update_post_meta( $course_id, '_aramesh_price', $cdata['price'] );
		update_post_meta( $course_id, '_aramesh_sale_price', $cdata['sale'] );
		update_post_meta( $course_id, '_aramesh_teacher', 'دکتر سارا احمدی' );
		update_post_meta( $course_id, '_aramesh_duration', $cdata['duration'] );
		update_post_meta( $course_id, '_aramesh_level', $cdata['level'] );
		update_post_meta( $course_id, '_aramesh_featured', $cdata['featured'] );
		update_post_meta( $course_id, '_aramesh_bestseller', $cdata['best'] );
		update_post_meta( $course_id, '_aramesh_outcomes', $cdata['outcomes'] );
		update_post_meta( $course_id, '_aramesh_suitable', $cdata['suitable'] );
		update_post_meta( $course_id, '_aramesh_faq', $cdata['faq'] );
		wp_set_object_terms( $course_id, $cdata['cat'], 'course_category' );
		wp_set_object_terms( $course_id, $cdata['cat'], 'topic' );

		// جلسه‌ها.
		$lessons = array(
			array( 'مقدمه و آشنایی با دوره', 'فصل ۱: شروع', '۰۸:۳۰', '1' ),
			array( 'شناخت ریشه‌ها', 'فصل ۱: شروع', '۱۴:۱۰', '' ),
			array( 'تکنیک‌های عملی', 'فصل ۲: مهارت‌ها', '۱۹:۴۵', '' ),
			array( 'تمرین و جمع‌بندی', 'فصل ۲: مهارت‌ها', '۱۱:۲۰', '' ),
		);
		$order = 0;
		$lesson_count = 0;
		foreach ( $lessons as $ldata ) {
			$order++;
			$lesson_title = $cdata['title'] . ' — ' . $ldata[0];
			$existing_l   = aramesh_find_by_title( $lesson_title, "lesson" );
			if ( $existing_l ) {
				$lesson_id = $existing_l->ID;
			} else {
				$lesson_id = wp_insert_post(
					array(
						'post_type'   => 'lesson',
						'post_status' => 'publish',
						'post_title'  => $lesson_title,
						'menu_order'  => $order,
						'post_content'=> '<p>خلاصه این جلسه در اینجا قرار می‌گیرد.</p>',
						'post_excerpt'=> 'خلاصه کوتاه جلسه ' . $ldata[0],
					)
				);
				$counts['lessons']++;
			}
			update_post_meta( $lesson_id, '_aramesh_course_id', $course_id );
			update_post_meta( $lesson_id, '_aramesh_chapter', $ldata[1] );
			update_post_meta( $lesson_id, '_aramesh_lesson_duration', $ldata[2] );
			update_post_meta( $lesson_id, '_aramesh_is_preview', $ldata[3] );
			wp_update_post( array( 'ID' => $lesson_id, 'menu_order' => $order ) );
			$lesson_count++;
		}
		update_post_meta( $course_id, '_aramesh_lesson_count', $lesson_count );
	}

	// مقالات مجله.
	$articles = array(
		array( 'چگونه روابط عاطفی سالم و پایدار داشته باشیم', 'روابط عاطفی' ),
		array( '۱۰ راهکار عملی برای مدیریت اضطراب روزانه', 'اضطراب و نگرانی' ),
		array( 'چطور عادت‌های مثبت را در زندگی شکل دهیم', 'رشد فردی' ),
	);
	foreach ( $articles as $adata ) {
		$existing = aramesh_find_by_title( $adata[0], "post" );
		if ( $existing ) {
			continue;
		}
		if ( ! term_exists( $adata[1], 'category' ) ) {
			wp_insert_term( $adata[1], 'category' );
		}
		$cat = get_term_by( 'name', $adata[1], 'category' );
		$pid = wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => $adata[0],
				'post_excerpt' => 'خلاصه‌ای کوتاه و جذاب درباره ' . $adata[0] . '.',
				'post_content' => "<h2>مقدمه</h2><p>متن نمونه مقاله برای تست صفحه مقاله و مجله. این متن قابل جایگزینی است.</p><h2>بخش اول</h2><p>محتوای بیشتر…</p><h2>جمع‌بندی</h2><p>نتیجه‌گیری کوتاه.</p>",
				'post_category'=> $cat ? array( $cat->term_id ) : array(),
			)
		);
		if ( $pid && ! is_wp_error( $pid ) ) {
			$counts['articles']++;
		}
	}

	// نظرات.
	$testimonials = array(
		array( 'نیلوفر اکبری', 'شرکت‌کننده دوره', 'بعد از شرکت در دوره مدیریت استرس، آرامش بیشتری در زندگی روزمره‌ام تجربه کردم.', 5 ),
		array( 'امیرحسین رضایی', 'شرکت‌کننده دوره', 'از نحوه بیان دکتر احمدی و تمرین‌های عملی دوره واقعاً راضی هستم.', 5 ),
		array( 'سارا محمدی', 'شرکت‌کننده دوره', 'رویکرد علمی و در عین حال صمیمی دکتر، مفاهیم را قابل‌فهم می‌کند.', 5 ),
	);
	foreach ( $testimonials as $t ) {
		$existing = aramesh_find_by_title( $t[0], "testimonial" );
		if ( $existing ) {
			continue;
		}
		$tid = wp_insert_post(
			array(
				'post_type'   => 'testimonial',
				'post_status' => 'publish',
				'post_title'  => $t[0],
				'post_content'=> $t[2],
			)
		);
		if ( $tid && ! is_wp_error( $tid ) ) {
			update_post_meta( $tid, '_aramesh_person_role', $t[1] );
			update_post_meta( $tid, '_aramesh_rating', $t[3] );
			$counts['testimonials']++;
		}
	}

	return $counts;
}
