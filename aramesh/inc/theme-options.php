<?php
/**
 * تنظیمات قالب از طریق Customizer.
 * هیچ اطلاعات تماس/شماره/آدرسی در کد hard-code نمی‌شود؛ همه از اینجا خوانده می‌شود.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * خواندن یک گزینه قالب با مقدار پیش‌فرض.
 *
 * @param string $key     کلید.
 * @param string $default مقدار پیش‌فرض.
 * @return string
 */
function aramesh_option( $key, $default = '' ) {
	return get_theme_mod( 'aramesh_' . $key, $default );
}

/**
 * ثبت گزینه‌ها در Customizer.
 */
function aramesh_customize_register( $wp_customize ) {

	// ============ پنل مشخصات کسب‌وکار ============
	$wp_customize->add_section(
		'aramesh_identity',
		array(
			'title'    => __( 'مشخصات دکتر و برند', 'aramesh' ),
			'priority' => 30,
		)
	);

	$identity_fields = array(
		'doctor_name'     => array( __( 'نام دکتر', 'aramesh' ), 'دکتر مرتضی توکلی' ),
		'doctor_title'    => array( __( 'عنوان زیر نام', 'aramesh' ), 'دکترای روانشناسی عمومی' ),
		'doctor_tagline'  => array( __( 'شعار کوتاه', 'aramesh' ), 'همراه شما در مسیر شناخت خود و حال بهتر' ),
		'doctor_bio'      => array( __( 'بیوگرافی کوتاه (فوتر/درباره)', 'aramesh' ), 'دکترای روانشناسی عمومی؛ با ارائه هزاران مشاوره فردی و خانوادگی و برگزاری بیش از ۳۰ عنوان کارگاه درمان ماهانه به‌صورت آنلاین و حضوری.' ),
		'doctor_experience' => array( __( 'سال‌های تجربه (عدد)', 'aramesh' ), '15' ),
	);
	foreach ( $identity_fields as $key => $data ) {
		$is_textarea = ( 'doctor_bio' === $key );
		$wp_customize->add_setting( 'aramesh_' . $key, array( 'default' => $data[1], 'sanitize_callback' => $is_textarea ? 'sanitize_textarea_field' : 'sanitize_text_field' ) );
		$wp_customize->add_control( 'aramesh_' . $key, array( 'label' => $data[0], 'section' => 'aramesh_identity', 'type' => $is_textarea ? 'textarea' : 'text' ) );
	}

	// عکس دکتر برای هیرو صفحه اصلی و صفحه درباره (آپلود از همین‌جا).
	$wp_customize->add_setting( 'aramesh_hero_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aramesh_hero_image',
			array(
				'label'       => __( 'عکس دکتر — هیرو صفحه اصلی', 'aramesh' ),
				'description' => __( 'عکس شما در بخش بالای صفحه اصلی نمایش داده می‌شود.', 'aramesh' ),
				'section'     => 'aramesh_identity',
			)
		)
	);

	$wp_customize->add_setting( 'aramesh_about_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'aramesh_about_image',
			array(
				'label'       => __( 'عکس دکتر — بخش «درباره من»', 'aramesh' ),
				'description' => __( 'اگر خالی بماند، از همان عکس هیرو استفاده می‌شود.', 'aramesh' ),
				'section'     => 'aramesh_identity',
			)
		)
	);

	// لوگو متنی/تصویری از قابلیت custom-logo هم پشتیبانی می‌شود.
	add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 200, 'flex-width' => true, 'flex-height' => true ) );

	// ============ اطلاعات تماس ============
	$wp_customize->add_section(
		'aramesh_contact',
		array(
			'title'    => __( 'اطلاعات تماس و شبکه‌ها', 'aramesh' ),
			'priority' => 31,
		)
	);

	$contact_fields = array(
		'phone'     => array( __( 'شماره تماس', 'aramesh' ), 'sanitize_text_field' ),
		'email'     => array( __( 'ایمیل', 'aramesh' ), 'sanitize_email' ),
		'address'   => array( __( 'آدرس', 'aramesh' ), 'sanitize_text_field' ),
		'hours'     => array( __( 'ساعات پاسخگویی', 'aramesh' ), 'sanitize_text_field' ),
		'telegram'  => array( __( 'لینک تلگرام (پشتیبانی/منشی)', 'aramesh' ), 'esc_url_raw' ),
		'instagram' => array( __( 'اینستاگرام', 'aramesh' ), 'esc_url_raw' ),
		'youtube'   => array( __( 'یوتیوب', 'aramesh' ), 'esc_url_raw' ),
		'whatsapp'  => array( __( 'واتساپ', 'aramesh' ), 'esc_url_raw' ),
		'map_embed' => array( __( 'کد embed نقشه (اختیاری)', 'aramesh' ), 'wp_kses_post' ),
	);
	foreach ( $contact_fields as $key => $data ) {
		$type = ( 'map_embed' === $key ) ? 'textarea' : 'text';
		$wp_customize->add_setting( 'aramesh_' . $key, array( 'default' => '', 'sanitize_callback' => $data[1] ) );
		$wp_customize->add_control( 'aramesh_' . $key, array( 'label' => $data[0], 'section' => 'aramesh_contact', 'type' => $type ) );
	}

	// ============ یکپارچه‌سازی OTP و پرداخت ============
	$wp_customize->add_section(
		'aramesh_integrations',
		array(
			'title'    => __( 'یکپارچه‌سازی OTP و پرداخت', 'aramesh' ),
			'priority' => 32,
		)
	);

	$wp_customize->add_setting( 'aramesh_otp_provider', array( 'default' => 'log', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'aramesh_otp_provider',
		array(
			'label'       => __( 'ارائه‌دهنده پیامک OTP', 'aramesh' ),
			'description' => __( 'در حالت log کد فقط در error_log ثبت می‌شود (برای توسعه). برای تولید، افزونه/فیلتر aramesh_send_otp_sms را پیاده کنید.', 'aramesh' ),
			'section'     => 'aramesh_integrations',
			'type'        => 'select',
			'choices'     => array(
				'log'      => __( 'ثبت در لاگ (توسعه)', 'aramesh' ),
				'provider' => __( 'ارائه‌دهنده واقعی (از طریق فیلتر)', 'aramesh' ),
			),
		)
	);

	$wp_customize->add_setting( 'aramesh_payment_mode', array( 'default' => 'manual', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'aramesh_payment_mode',
		array(
			'label'       => __( 'حالت پرداخت داخل ایران', 'aramesh' ),
			'description' => __( 'درگاه واقعی از طریق فیلتر aramesh_payment_gateway_url متصل می‌شود.', 'aramesh' ),
			'section'     => 'aramesh_integrations',
			'type'        => 'select',
			'choices'     => array(
				'manual'  => __( 'دستی/آزمایشی (بدون درگاه)', 'aramesh' ),
				'gateway' => __( 'درگاه ریالی (از طریق فیلتر)', 'aramesh' ),
			),
		)
	);

	// ============ متن‌های مسیر خارج ایران ============
	$wp_customize->add_section( 'aramesh_international', array( 'title' => __( 'مسیر کاربران خارج ایران', 'aramesh' ), 'priority' => 33 ) );
	$wp_customize->add_setting( 'aramesh_intl_intro', array( 'default' => 'اگر خارج از ایران هستید، ثبت‌نام و پرداخت به‌صورت دستی و با پشتیبانی منشی از طریق تلگرام انجام می‌شود.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( 'aramesh_intl_intro', array( 'label' => __( 'متن معرفی مسیر خارج ایران', 'aramesh' ), 'section' => 'aramesh_international', 'type' => 'textarea' ) );
}
add_action( 'customize_register', 'aramesh_customize_register' );
