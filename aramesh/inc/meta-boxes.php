<?php
/**
 * فیلدهای سفارشی بومی (بدون وابستگی به ACF/Page Builder).
 *
 * از nonce و sanitize کامل استفاده می‌شود. همه فیلدها با کلید _aramesh_* ذخیره
 * می‌شوند تا در query و template به‌سادگی قابل خواندن باشند.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * ثبت متاباکس‌ها.
 */
function aramesh_register_meta_boxes() {
	add_meta_box( 'aramesh_course_details', __( 'مشخصات دوره', 'aramesh' ), 'aramesh_course_meta_box', 'course', 'normal', 'high' );
	add_meta_box( 'aramesh_lesson_details', __( 'مشخصات جلسه', 'aramesh' ), 'aramesh_lesson_meta_box', 'lesson', 'normal', 'high' );
	add_meta_box( 'aramesh_testimonial_details', __( 'مشخصات نظر', 'aramesh' ), 'aramesh_testimonial_meta_box', 'testimonial', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'aramesh_register_meta_boxes' );

/**
 * ورودی متن ساده.
 */
function aramesh_field_text( $post_id, $key, $label, $type = 'text', $placeholder = '' ) {
	$value = get_post_meta( $post_id, $key, true );
	printf(
		'<p class="aramesh-field"><label for="%1$s"><strong>%2$s</strong></label><br>
		<input type="%4$s" id="%1$s" name="%1$s" value="%3$s" placeholder="%5$s" class="widefat" style="max-width:520px"></p>',
		esc_attr( $key ),
		esc_html( $label ),
		esc_attr( $value ),
		esc_attr( $type ),
		esc_attr( $placeholder )
	);
}

/**
 * ناحیه متن چندخطی.
 */
function aramesh_field_textarea( $post_id, $key, $label, $rows = 4, $hint = '' ) {
	$value = get_post_meta( $post_id, $key, true );
	printf(
		'<p class="aramesh-field"><label for="%1$s"><strong>%2$s</strong></label><br>
		<textarea id="%1$s" name="%1$s" rows="%4$d" class="widefat">%3$s</textarea>
		<span class="description">%5$s</span></p>',
		esc_attr( $key ),
		esc_html( $label ),
		esc_textarea( $value ),
		(int) $rows,
		esc_html( $hint )
	);
}

/**
 * چک‌باکس.
 */
function aramesh_field_checkbox( $post_id, $key, $label ) {
	$value = get_post_meta( $post_id, $key, true );
	printf(
		'<p class="aramesh-field"><label><input type="checkbox" name="%1$s" value="1" %3$s> %2$s</label></p>',
		esc_attr( $key ),
		esc_html( $label ),
		checked( $value, '1', false )
	);
}

/**
 * متاباکس دوره.
 */
function aramesh_course_meta_box( $post ) {
	wp_nonce_field( 'aramesh_save_course', 'aramesh_course_nonce' );
	echo '<div class="aramesh-meta-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0 24px">';

	echo '<div>';
	aramesh_field_textarea( $post->ID, '_aramesh_short_desc', __( 'توضیح کوتاه (زیرعنوان دوره)', 'aramesh' ), 3 );
	aramesh_field_text( $post->ID, '_aramesh_price', __( 'قیمت (تومان)', 'aramesh' ), 'number', '2890000' );
	aramesh_field_text( $post->ID, '_aramesh_sale_price', __( 'قیمت با تخفیف (تومان، اختیاری)', 'aramesh' ), 'number' );
	aramesh_field_text( $post->ID, '_aramesh_teacher', __( 'مدرس', 'aramesh' ), 'text', 'دکتر سارا احمدی' );
	aramesh_field_text( $post->ID, '_aramesh_duration', __( 'مدت کل (مثلا ۸ ساعت)', 'aramesh' ), 'text' );
	aramesh_field_text( $post->ID, '_aramesh_lesson_count', __( 'تعداد جلسات', 'aramesh' ), 'number' );
	aramesh_field_text( $post->ID, '_aramesh_level', __( 'سطح (مثلا همه سطوح)', 'aramesh' ), 'text' );
	echo '</div>';

	echo '<div>';
	aramesh_field_text( $post->ID, '_aramesh_trailer', __( 'آدرس ویدیوی معرفی (تریلر)', 'aramesh' ), 'url', 'https://…' );
	aramesh_field_checkbox( $post->ID, '_aramesh_featured', __( 'دوره منتخب (Featured)', 'aramesh' ) );
	aramesh_field_checkbox( $post->ID, '_aramesh_bestseller', __( 'پرفروش (Bestseller)', 'aramesh' ) );
	aramesh_field_textarea( $post->ID, '_aramesh_outcomes', __( 'آنچه یاد می‌گیرید — هر مورد در یک خط', 'aramesh' ), 6, __( 'هر خط یک آیتم می‌شود.', 'aramesh' ) );
	aramesh_field_textarea( $post->ID, '_aramesh_suitable', __( 'مناسب برای — هر مورد در یک خط', 'aramesh' ), 5 );
	aramesh_field_textarea( $post->ID, '_aramesh_prerequisites', __( 'پیش‌نیازها — هر مورد در یک خط', 'aramesh' ), 3 );
	echo '</div>';

	echo '</div>';

	echo '<hr><p><strong>' . esc_html__( 'سوالات متداول دوره', 'aramesh' ) . '</strong> — ' . esc_html__( 'هر خط: پرسش | پاسخ', 'aramesh' ) . '</p>';
	aramesh_field_textarea( $post->ID, '_aramesh_faq', '', 6, __( 'نمونه: بعد از خرید چطور دسترسی پیدا می‌کنم؟ | بلافاصله پس از پرداخت در «دوره‌های من».', 'aramesh' ) );
}

/**
 * متاباکس جلسه.
 */
function aramesh_lesson_meta_box( $post ) {
	wp_nonce_field( 'aramesh_save_lesson', 'aramesh_lesson_nonce' );

	// انتخاب دوره والد.
	$course_id = (int) get_post_meta( $post->ID, '_aramesh_course_id', true );
	$courses   = get_posts( array( 'post_type' => 'course', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
	echo '<p class="aramesh-field"><label for="_aramesh_course_id"><strong>' . esc_html__( 'دوره مربوطه', 'aramesh' ) . '</strong></label><br>';
	echo '<select id="_aramesh_course_id" name="_aramesh_course_id" class="widefat" style="max-width:520px">';
	echo '<option value="0">' . esc_html__( '— انتخاب دوره —', 'aramesh' ) . '</option>';
	foreach ( $courses as $c ) {
		printf( '<option value="%d" %s>%s</option>', $c->ID, selected( $course_id, $c->ID, false ), esc_html( $c->post_title ) );
	}
	echo '</select></p>';

	aramesh_field_text( $post->ID, '_aramesh_chapter', __( 'فصل / سرفصل', 'aramesh' ), 'text' );
	aramesh_field_text( $post->ID, '_aramesh_lesson_duration', __( 'مدت جلسه (مثلا ۱۲:۳۰)', 'aramesh' ), 'text' );
	echo '<p class="description">' . esc_html__( 'ترتیب جلسه از فیلد «ترتیب» (page attributes) خوانده می‌شود.', 'aramesh' ) . '</p>';

	echo '<hr><p><strong>' . esc_html__( 'ویدیوی محافظت‌شده', 'aramesh' ) . '</strong></p>';
	aramesh_field_text( $post->ID, '_aramesh_video_provider', __( 'ارائه‌دهنده (hls/dash/provider slug)', 'aramesh' ), 'text', 'hls' );
	aramesh_field_text( $post->ID, '_aramesh_video_id', __( 'شناسه/مسیر امن ویدیو (بدون URL مستقیم عمومی)', 'aramesh' ), 'text' );
	echo '<p class="description">' . esc_html__( 'هرگز URL مستقیم MP4 عمومی قرار ندهید؛ فقط شناسه‌ای که سرویس امن با آن URL موقت می‌سازد.', 'aramesh' ) . '</p>';

	aramesh_field_checkbox( $post->ID, '_aramesh_is_preview', __( 'این جلسه پیش‌نمایش رایگان است', 'aramesh' ) );

	aramesh_field_textarea( $post->ID, '_aramesh_exercises', __( 'تمرین‌ها', 'aramesh' ), 4 );
	aramesh_field_textarea( $post->ID, '_aramesh_resources', __( 'منابع قابل دانلود — هر خط: عنوان | آدرس', 'aramesh' ), 3, __( 'PDF/worksheet مجاز است؛ ویدیو خیر.', 'aramesh' ) );
}

/**
 * متاباکس نظر.
 */
function aramesh_testimonial_meta_box( $post ) {
	wp_nonce_field( 'aramesh_save_testimonial', 'aramesh_testimonial_nonce' );
	aramesh_field_text( $post->ID, '_aramesh_person_role', __( 'نقش/عنوان', 'aramesh' ), 'text', 'شرکت‌کننده دوره' );
	aramesh_field_text( $post->ID, '_aramesh_rating', __( 'امتیاز (۱ تا ۵)', 'aramesh' ), 'number', '5' );
}

/**
 * ذخیره متاها.
 */
function aramesh_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$type = get_post_type( $post_id );

	// تعریف فیلدها بر اساس نوع.
	$text_fields   = array();
	$check_fields  = array();
	$nonce_action  = '';
	$nonce_field   = '';

	if ( 'course' === $type ) {
		$nonce_action = 'aramesh_save_course';
		$nonce_field  = 'aramesh_course_nonce';
		$text_fields  = array( '_aramesh_short_desc', '_aramesh_price', '_aramesh_sale_price', '_aramesh_teacher', '_aramesh_duration', '_aramesh_lesson_count', '_aramesh_level', '_aramesh_trailer', '_aramesh_outcomes', '_aramesh_suitable', '_aramesh_prerequisites', '_aramesh_faq' );
		$check_fields = array( '_aramesh_featured', '_aramesh_bestseller' );
	} elseif ( 'lesson' === $type ) {
		$nonce_action = 'aramesh_save_lesson';
		$nonce_field  = 'aramesh_lesson_nonce';
		$text_fields  = array( '_aramesh_course_id', '_aramesh_chapter', '_aramesh_lesson_duration', '_aramesh_video_provider', '_aramesh_video_id', '_aramesh_exercises', '_aramesh_resources' );
		$check_fields = array( '_aramesh_is_preview' );
	} elseif ( 'testimonial' === $type ) {
		$nonce_action = 'aramesh_save_testimonial';
		$nonce_field  = 'aramesh_testimonial_nonce';
		$text_fields  = array( '_aramesh_person_role', '_aramesh_rating' );
	} else {
		return;
	}

	if ( ! isset( $_POST[ $nonce_field ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), $nonce_action ) ) {
		return;
	}

	$multiline = array( '_aramesh_short_desc', '_aramesh_outcomes', '_aramesh_suitable', '_aramesh_prerequisites', '_aramesh_faq', '_aramesh_exercises', '_aramesh_resources' );
	$urls      = array( '_aramesh_trailer' );

	foreach ( $text_fields as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		if ( in_array( $key, $urls, true ) ) {
			$val = esc_url_raw( $raw );
		} elseif ( in_array( $key, $multiline, true ) ) {
			$val = sanitize_textarea_field( $raw );
		} elseif ( '_aramesh_course_id' === $key ) {
			$val = (int) $raw;
		} else {
			$val = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $val );
	}

	foreach ( $check_fields as $key ) {
		update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '' );
	}
}
add_action( 'save_post', 'aramesh_save_meta' );
