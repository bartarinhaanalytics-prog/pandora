<?php
/**
 * Customizer settings — social links & newsletter box copy.
 * Appearance > Customize > تنظیمات تکراتو
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function techrato_customize_register( $wp_customize ) {

	$wp_customize->add_panel( 'techrato_options', array(
		'title'    => __( 'تنظیمات تکراتو', 'techrato' ),
		'priority' => 30,
	) );

	/* Social links section */
	$wp_customize->add_section( 'techrato_social', array(
		'title' => __( 'شبکه‌های اجتماعی', 'techrato' ),
		'panel' => 'techrato_options',
	) );

	$socials = array(
		'social_link_1' => __( 'لینک شبکه اجتماعی اول (مثلا تلگرام)', 'techrato' ),
		'social_link_2' => __( 'لینک شبکه اجتماعی دوم (مثلا اینستاگرام)', 'techrato' ),
		'social_link_3' => __( 'لینک شبکه اجتماعی سوم (مثلا آپارات)', 'techrato' ),
	);
	foreach ( $socials as $id => $label ) {
		$wp_customize->add_setting( $id, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $label,
			'section' => 'techrato_social',
			'type'    => 'url',
		) );
	}

	/* Newsletter section */
	$wp_customize->add_section( 'techrato_newsletter', array(
		'title' => __( 'باکس خبرنامه فوتر', 'techrato' ),
		'panel' => 'techrato_options',
	) );

	$wp_customize->add_setting( 'newsletter_title', array(
		'default'           => __( 'با تکراتو همراه باشید', 'techrato' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'newsletter_title', array(
		'label'   => __( 'عنوان', 'techrato' ),
		'section' => 'techrato_newsletter',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'newsletter_text', array(
		'default'           => __( 'برای دریافت آخرین اخبار و مقالات، ایمیل خود را وارد کنید.', 'techrato' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'newsletter_text', array(
		'label'   => __( 'توضیح کوتاه', 'techrato' ),
		'section' => 'techrato_newsletter',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'newsletter_action_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'newsletter_action_url', array(
		'label'       => __( 'آدرس اکشن فرم (سرویس خبرنامه، اختیاری)', 'techrato' ),
		'description' => __( 'اگر از سرویسی مثل میلرلایت/میلچیمپ استفاده می‌کنید، آدرس فرم را اینجا وارد کنید.', 'techrato' ),
		'section'     => 'techrato_newsletter',
		'type'        => 'url',
	) );

	/* Social banner (follow us) section */
	$wp_customize->add_section( 'techrato_follow_banner', array(
		'title' => __( 'بنر «مارا دنبال کنید»', 'techrato' ),
		'panel' => 'techrato_options',
	) );

	$wp_customize->add_setting( 'follow_banner_title', array(
		'default'           => __( 'مارا در شبکه‌های اجتماعی دنبال کنید', 'techrato' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'follow_banner_title', array(
		'label'   => __( 'عنوان بنر', 'techrato' ),
		'section' => 'techrato_follow_banner',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'follow_banner_text', array(
		'default'           => __( 'همچنین می‌توانید جهت آگاهی از آخرین اخبار در حوزه تکنولوژی و اطلاع از به‌روزرسانی مارا تکراتو در شبکه‌های اجتماعی زیر نیز دنبال کنید. خوشحالیم که همراه ما هستید.', 'techrato' ),
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'follow_banner_text', array(
		'label'   => __( 'متن بنر', 'techrato' ),
		'section' => 'techrato_follow_banner',
		'type'    => 'textarea',
	) );
}
add_action( 'customize_register', 'techrato_customize_register' );
