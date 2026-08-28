<?php
/**
 * بارگذار محتوای سایت — از پیشخوان: ابزارها » محتوای سایت (Aramesh).
 * اطلاعات دکتر، صفحه درباره، و فهرست کارگاه‌های برگزارشده را می‌سازد.
 * عملیات idempotent است و محتوای تکراری نمی‌سازد.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * افزودن صفحه ابزار.
 */
function aramesh_demo_menu() {
	add_management_page(
		__( 'محتوای سایت (Aramesh)', 'aramesh' ),
		__( 'محتوای سایت (Aramesh)', 'aramesh' ),
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
		echo '<div class="notice notice-success"><p>' . esc_html( sprintf( __( 'انجام شد: %d کارگاه ساخته/به‌روزرسانی شد و اطلاعات دکتر و صفحه درباره تنظیم شد.', 'aramesh' ), $result['courses'] ) ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'محتوای سایت (Aramesh)', 'aramesh' ); ?></h1>
		<p><?php esc_html_e( 'با این دکمه، اطلاعات دکتر مرتضی توکلی، صفحه «درباره»، و فهرست کارگاه‌های برگزارشده ساخته می‌شود تا سایت آماده نمایش باشد. این عملیات idempotent است.', 'aramesh' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'aramesh_demo', 'aramesh_demo_nonce' ); ?>
			<p><button type="submit" name="aramesh_demo_seed" value="1" class="button button-primary"><?php esc_html_e( 'ساخت / به‌روزرسانی محتوای سایت', 'aramesh' ); ?></button></p>
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
 * تنظیم هویت دکتر و صفحه درباره.
 */
function aramesh_seed_identity() {
	// عنوان و توضیح سایت.
	update_option( 'blogname', 'دکتر مرتضی توکلی' );
	update_option( 'blogdescription', 'دکترای روانشناسی عمومی' );

	// theme_modهای هویت.
	set_theme_mod( 'aramesh_doctor_name', 'دکتر مرتضی توکلی' );
	set_theme_mod( 'aramesh_doctor_title', 'دکترای روانشناسی عمومی' );
	set_theme_mod( 'aramesh_doctor_tagline', 'همراه شما در مسیر شناخت خود و حال بهتر' );
	set_theme_mod( 'aramesh_doctor_experience', '15' );
	set_theme_mod( 'aramesh_doctor_bio', 'دکترای روانشناسی عمومی؛ با ارائه هزاران مشاوره فردی و خانوادگی و برگزاری بیش از ۳۰ عنوان کارگاه درمان ماهانه به‌صورت آنلاین و حضوری.' );

	// عکس دکتر (از مدیا لایبرری همین سایت). قابل تغییر در سفارشی‌سازی.
	$uploads = wp_upload_dir();
	$photo   = trailingslashit( $uploads['baseurl'] ) . '2026/08/ChatGPT-Image-Aug-13-2026-04_12_55-PM.png';
	if ( ! get_theme_mod( 'aramesh_hero_image' ) ) {
		set_theme_mod( 'aramesh_hero_image', $photo );
	}
	if ( ! get_theme_mod( 'aramesh_about_image' ) ) {
		set_theme_mod( 'aramesh_about_image', $photo );
	}

	// محتوای صفحه درباره.
	$pages = get_option( 'aramesh_pages', array() );
	if ( ! empty( $pages['about'] ) && get_post( $pages['about'] ) ) {
		$about_content =
			'<h2>درباره دکتر مرتضی توکلی</h2>' .
			'<p>دکترای روانشناسی عمومی، متولد خرداد ۱۳۵۹.</p>' .
			'<ul>' .
			'<li>ارائه هزاران مشاوره فردی و خانوادگی طی سال‌های گذشته</li>' .
			'<li>برگزاری بیش از ۳۰ عنوان کارگاه درمان ماهانه به‌صورت آنلاین و حضوری</li>' .
			'<li>برگزاری جلسات آموزش رایگان فرزندپروری در شهرستان‌های سراسر ایران</li>' .
			'<li>هزاران مشاوره فردی و خانوادگی تخصصی با مهاجران ایرانی سراسر دنیا طی ده سال گذشته</li>' .
			'<li>ریاست فرهنگسرای ارسباران، سال ۹۱–۹۲</li>' .
			'<li>مدیرمسئول و سردبیر سایت نی‌نی‌بان از ۱۳۹۲</li>' .
			'<li>سردبیر مجله فرزندپروری شهرزاد از سال ۱۳۸۸</li>' .
			'</ul>';
		wp_update_post( array( 'ID' => (int) $pages['about'], 'post_content' => $about_content ) );
	}
}

/**
 * توضیح مشترک نحوه ارائه کارگاه‌ها.
 */
function aramesh_workshop_delivery_note() {
	return 'پس از خرید، ویدیوهای این دوره به‌صورت محافظت‌شده در بخش «دوره‌های من» و پنل کاربری شما قابل تماشاست ' .
		'و امکان دانلود مستقیم ندارد. کاربران داخل ایران با شماره موبایل ثبت‌نام و پرداخت ریالی انجام می‌دهند؛ ' .
		'کاربران خارج از کشور از طریق تلگرام هماهنگ می‌شوند. هر دوره حداقل ۴ جلسه‌ی دوساعته (مجموعاً ۸ ساعت) است.';
}

/**
 * ورود تصویر کاور نمونه به کتابخانه رسانه و بازگرداندن شناسه attachment.
 * برای پرهیز از تکرار، نگاشت در option ذخیره می‌شود.
 *
 * @param string $filename مثل cover-1.jpg در assets/samples/.
 * @return int attachment ID یا 0.
 */
function aramesh_import_cover( $filename ) {
	$src = ARAMESH_DIR . '/assets/samples/' . $filename;
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$map = get_option( 'aramesh_sample_media', array() );
	if ( ! empty( $map[ $filename ] ) && get_post( $map[ $filename ] ) ) {
		return (int) $map[ $filename ];
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$upload = wp_upload_dir();
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$dest = trailingslashit( $upload['path'] ) . $filename;
	if ( ! copy( $src, $dest ) ) {
		return 0;
	}
	$filetype = wp_check_filetype( $filename, null );
	$attach   = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => 'aramesh-' . preg_replace( '/\.[^.]+$/', '', $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$id = wp_insert_attachment( $attach, $dest );
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	$meta = wp_generate_attachment_metadata( $id, $dest );
	wp_update_attachment_metadata( $id, $meta );

	$map[ $filename ] = $id;
	update_option( 'aramesh_sample_media', $map );
	return (int) $id;
}

/**
 * ساخت محتوای سایت (کارگاه‌ها + هویت + درباره).
 *
 * @return array
 */
function aramesh_seed_demo_content() {
	$counts = array( 'courses' => 0 );

	aramesh_seed_identity();

	// دسته‌بندی‌ها و موضوعات.
	$cats = array( 'هیجانات', 'روابط', 'خودشناسی و رشد' );
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c, 'course_category' ) ) {
			wp_insert_term( $c, 'course_category' );
		}
		if ( ! term_exists( $c, 'topic' ) ) {
			wp_insert_term( $c, 'topic' );
		}
	}

	// فهرست کارگاه‌های برگزارشده.
	// [ عنوان, دسته, تاریخ برگزاری, توضیح کوتاه, featured, bestseller ]
	$workshops = array(
		array( 'کارگاه سوگ', 'هیجانات', 'اسفند ۱۴۰۳', 'همراهی با سوگ و عبور آگاهانه از فقدان و اندوه.', '', '1' ),
		array( 'کارگاه مهرطلبی', 'روابط', 'آذر ۱۴۰۴', 'رهایی از الگوی مهرطلبی و «نه» گفتن سالم.', '1', '1' ),
		array( 'کارگاه تنهایی باشکوه', 'خودشناسی و رشد', 'بهمن ۱۴۰۳', 'تبدیل تنهایی به فرصتی برای رشد و آرامش.', '', '' ),
		array( 'کارگاه با افسردگی', 'هیجانات', 'فروردین ۱۴۰۴', 'شناخت افسردگی و راهکارهای عملی برای حال بهتر.', '', '' ),
		array( 'کارگاه مرزبندی‌های مهم', 'روابط', 'مهر ۱۴۰۴', 'یادگیری مرزبندی سالم در روابط.', '', '1' ),
		array( 'کارگاه تقویت حافظه', 'خودشناسی و رشد', 'شهریور ۱۴۰۳', 'تکنیک‌های عملی برای تقویت حافظه و تمرکز.', '', '' ),
		array( 'کارگاه با خشمم چه کنم', 'هیجانات', 'بهمن ۱۴۰۴', 'مدیریت خشم و تبدیل آن به انرژی سازنده.', '', '1' ),
		array( 'کارگاه جعبه ابزار آرامش', 'خودشناسی و رشد', 'آبان ۱۴۰۴', 'ابزارهای کاربردی برای آرام‌سازی ذهن.', '', '' ),
		array( 'کارگاه فرمول رابطه پایدار', 'روابط', 'تیر ۱۴۰۵', 'اصول ساختن رابطه‌ای عمیق و پایدار.', '1', '' ),
		array( 'کارگاه ترمیم اعتماد به‌نفس', 'خودشناسی و رشد', 'مرداد ۱۴۰۴', 'بازسازی خودباوری و اعتماد به نفس.', '', '1' ),
		array( 'کارگاه شناخت من ارزشمند', 'خودشناسی و رشد', 'اسفند ۱۴۰۴', 'کشف ارزش‌های درونی و پذیرش خود.', '1', '' ),
		array( 'کارگاه ما اهمالکار نیستیم!', 'خودشناسی و رشد', 'شهریور ۱۴۰۴', 'ریشه‌یابی و درمان اهمال‌کاری.', '', '' ),
		array( 'کارگاه درمان دردهای زندگی', 'هیجانات', 'دی ۱۴۰۳', 'همراهی با دردهای زندگی و التیام آن‌ها.', '', '1' ),
		array( 'کارگاه مهربون باش با خودت', 'خودشناسی و رشد', 'آذر ۱۴۰۳', 'شفقت به خود و گفت‌وگوی درونی مهربان.', '', '' ),
		array( 'کارگاه سبک دلبستگی من چیه', 'روابط', 'دی ۱۴۰۴', 'شناخت سبک دلبستگی و تأثیر آن بر روابط.', '', '' ),
		array( 'کارگاه نمی‌خوام از فردا بترسم', 'هیجانات', 'مرداد ۱۴۰۵', 'مدیریت اضطراب آینده و ترس از فردا.', '1', '' ),
		array( 'کارگاه خشم، اضطراب، افسردگی', 'هیجانات', 'آبان ۱۴۰۳', 'شناخت و مدیریت سه‌گانه‌ی هیجانی خشم، اضطراب و افسردگی.', '', '' ),
		array( 'کارگاه چرا حالم خوش نیست', 'هیجانات', 'اردیبهشت ۱۴۰۴', 'ریشه‌یابی حال ناخوش و مسیر بهبود.', '', '' ),
		array( 'کارگاه چرا دستکاری روانی می‌شم', 'روابط', 'خرداد ۱۴۰۴', 'شناخت دستکاری روانی و محافظت از خود.', '', '' ),
	);

	$delivery     = aramesh_workshop_delivery_note();
	$sample_video = function_exists( 'aramesh_sample_video_url' ) ? aramesh_sample_video_url() : '';
	$i            = 0;

	foreach ( $workshops as $w ) {
		list( $title, $cat, $date, $short, $featured, $best ) = $w;
		$price = 890000 + ( $i % 5 ) * 200000;              // ۸۹۰٬۰۰۰ تا ۱٬۶۹۰٬۰۰۰
		$sale  = ( 0 === $i % 3 ) ? $price - 200000 : 0;    // بعضی با تخفیف

		$existing = aramesh_find_by_title( $title, 'course' );
		if ( $existing ) {
			$course_id = $existing->ID;
		} else {
			$course_id = wp_insert_post(
				array(
					'post_type'    => 'course',
					'post_status'  => 'publish',
					'post_title'   => $title,
					'post_excerpt' => $short,
					'post_content' => '<p>' . esc_html( $short ) . '</p>' .
						'<p class="text-secondary">برگزارشده: ' . esc_html( $date ) . '</p>' .
						'<p>' . esc_html( $delivery ) . '</p>',
				)
			);
			if ( $course_id && ! is_wp_error( $course_id ) ) {
				$counts['courses']++;
			}
		}
		if ( ! $course_id || is_wp_error( $course_id ) ) {
			$i++;
			continue;
		}

		update_post_meta( $course_id, '_aramesh_short_desc', $short );
		update_post_meta( $course_id, '_aramesh_price', $price );
		update_post_meta( $course_id, '_aramesh_sale_price', $sale );
		update_post_meta( $course_id, '_aramesh_teacher', 'دکتر مرتضی توکلی' );
		update_post_meta( $course_id, '_aramesh_duration', '۸ ساعت (۴ جلسه دوساعته)' );
		update_post_meta( $course_id, '_aramesh_lesson_count', 4 );
		update_post_meta( $course_id, '_aramesh_level', 'همه سطوح' );
		update_post_meta( $course_id, '_aramesh_featured', $featured );
		update_post_meta( $course_id, '_aramesh_bestseller', $best );
		update_post_meta( $course_id, '_aramesh_workshop_date', $date );
		if ( $sample_video ) {
			update_post_meta( $course_id, '_aramesh_trailer', $sample_video );
		}
		update_post_meta(
			$course_id,
			'_aramesh_faq',
			'بعد از خرید چطور دوره را ببینم؟ | ویدیوها در بخش «دوره‌های من» و پنل شما به‌صورت محافظت‌شده قابل تماشاست.' . "\n" .
			'آیا امکان دانلود ویدیو هست؟ | خیر، برای حفظ حقوق آموزشی دانلود مستقیم فعال نیست.' . "\n" .
			'خارج از کشور هستم، چطور ثبت‌نام کنم؟ | از طریق تلگرام هماهنگ می‌شوید.'
		);

		// کاور دوره (featured image) به‌صورت round-robin از تصاویر نمونه.
		$cover_id = aramesh_import_cover( 'cover-' . ( ( $i % 8 ) + 1 ) . '.jpg' );
		if ( $cover_id ) {
			set_post_thumbnail( $course_id, $cover_id );
		}

		wp_set_object_terms( $course_id, $cat, 'course_category' );
		wp_set_object_terms( $course_id, $cat, 'topic' );

		// جلسه‌های نمونه (اولی پیش‌نمایش رایگان) با ویدیوی محافظت‌شدهٔ نمونه.
		$lessons = array(
			array( 'مقدمه و آشنایی', 'فصل ۱', '۰۸:۳۰', '1' ),
			array( 'مفاهیم پایه', 'فصل ۱', '۱۴:۱۰', '' ),
			array( 'تکنیک‌های عملی', 'فصل ۲', '۱۹:۴۵', '' ),
			array( 'تمرین و جمع‌بندی', 'فصل ۲', '۱۱:۲۰', '' ),
		);
		$order = 0;
		foreach ( $lessons as $ld ) {
			$order++;
			$ltitle = $title . ' — ' . $ld[0];
			$lex    = aramesh_find_by_title( $ltitle, 'lesson' );
			if ( $lex ) {
				$lid = $lex->ID;
			} else {
				$lid = wp_insert_post(
					array(
						'post_type'    => 'lesson',
						'post_status'  => 'publish',
						'post_title'   => $ltitle,
						'menu_order'   => $order,
						'post_content' => '<p>خلاصهٔ این جلسه در اینجا قرار می‌گیرد. این یک محتوای نمونه است.</p>',
						'post_excerpt' => 'خلاصهٔ جلسه ' . $ld[0],
					)
				);
			}
			if ( $lid && ! is_wp_error( $lid ) ) {
				update_post_meta( $lid, '_aramesh_course_id', $course_id );
				update_post_meta( $lid, '_aramesh_chapter', $ld[1] );
				update_post_meta( $lid, '_aramesh_lesson_duration', $ld[2] );
				update_post_meta( $lid, '_aramesh_is_preview', $ld[3] );
				update_post_meta( $lid, '_aramesh_video_provider', 'sample' );
				update_post_meta( $lid, '_aramesh_video_id', 'sample' );
				wp_update_post( array( 'ID' => $lid, 'menu_order' => $order ) );
			}
		}

		$i++;
	}

	// ۱۰ مقالهٔ نمونه با کاور.
	$articles = array(
		array( 'چگونه روابط عاطفی سالم و پایدار داشته باشیم', 'روابط' ),
		array( '۱۰ راهکار عملی برای مدیریت اضطراب روزانه', 'هیجانات' ),
		array( 'چطور عادت‌های مثبت را در زندگی شکل دهیم', 'خودشناسی و رشد' ),
		array( 'نشانه‌های فرسودگی ذهنی و راه‌های بازیابی انرژی', 'هیجانات' ),
		array( 'مرزبندی سالم؛ چطور بدون احساس گناه «نه» بگوییم', 'روابط' ),
		array( 'شفقت به خود؛ مهربانی با خود در روزهای سخت', 'خودشناسی و رشد' ),
		array( 'ریشه‌های کمبود اعتمادبه‌نفس و مسیر ترمیم آن', 'خودشناسی و رشد' ),
		array( 'سبک‌های دلبستگی و تأثیر آن‌ها بر روابط ما', 'روابط' ),
		array( 'چرا اهمال‌کاری می‌کنیم و چطور از آن رها شویم', 'خودشناسی و رشد' ),
		array( 'سوگ و فقدان؛ چطور با اندوه سالم کنار بیاییم', 'هیجانات' ),
	);
	$counts['articles'] = 0;
	$ai                 = 0;
	foreach ( $articles as $a ) {
		list( $atitle, $acat ) = $a;
		if ( aramesh_find_by_title( $atitle, 'post' ) ) {
			$ai++;
			continue;
		}
		if ( ! term_exists( $acat, 'category' ) ) {
			wp_insert_term( $acat, 'category' );
		}
		$term = get_term_by( 'name', $acat, 'category' );
		$pid  = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $atitle,
				'post_excerpt'  => 'خلاصه‌ای کوتاه و کاربردی دربارهٔ ' . $atitle . '.',
				'post_content'  => '<h2>مقدمه</h2><p>این یک متن نمونه برای صفحهٔ مجله و مقاله است و قابل جایگزینی با محتوای واقعی می‌باشد.</p><h2>نکات کلیدی</h2><p>محتوای بیشتر در این بخش قرار می‌گیرد.</p><h2>جمع‌بندی</h2><p>نتیجه‌گیری کوتاه و کاربردی.</p>',
				'post_category' => $term ? array( $term->term_id ) : array(),
			)
		);
		if ( $pid && ! is_wp_error( $pid ) ) {
			$cover_id = aramesh_import_cover( 'cover-' . ( ( $ai % 8 ) + 1 ) . '.jpg' );
			if ( $cover_id ) {
				set_post_thumbnail( $pid, $cover_id );
			}
			$counts['articles']++;
		}
		$ai++;
	}

	return $counts;
}
