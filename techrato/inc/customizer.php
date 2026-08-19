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
		'social_link_1' => array( __( 'لینک توییتر (X)', 'techrato' ), 'https://twitter.com/Techratocom' ),
		'social_link_2' => array( __( 'لینک اینستاگرام', 'techrato' ), 'https://www.instagram.com/techratocom' ),
		'social_link_3' => array( __( 'لینک تلگرام', 'techrato' ), 'https://t.me/techrato_com' ),
	);
	foreach ( $socials as $id => $social ) {
		list( $label, $default ) = $social;
		$wp_customize->add_setting( $id, array(
			'default'           => $default,
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

	/* "مشاهده مطالب بیشتر" link targets */
	$wp_customize->add_section( 'techrato_more_links', array(
		'title'       => __( 'لینک‌های «مشاهده مطالب بیشتر»', 'techrato' ),
		'description' => __( 'برای هر بخش صفحه نخست تعیین کنید دکمه «مشاهده مطالب بیشتر» به کدام دسته برود. اگر «خودکار» بماند، دکمه به دسته‌ای می‌رود که بیشتر مطالب همان بخش در آن قرار دارند.', 'techrato' ),
		'panel'       => 'techrato_options',
	) );

	$choices = array( 0 => __( 'خودکار', 'techrato' ) );
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$choices[ $category->term_id ] = $category->name;
	}

	$more_links = array(
		'more_link_latest'   => __( 'بخش «جدیدترین اخبار تکنولوژی»', 'techrato' ),
		'more_link_learning' => __( 'باکس «مقالات آموزشی تکراتو»', 'techrato' ),
		'more_link_popular'  => __( 'بخش «پربازدید ترین مطالب»', 'techrato' ),
		'more_link_iran'     => __( 'بخش «آخرین اخبار فناوری ایران»', 'techrato' ),
	);
	foreach ( $more_links as $id => $label ) {
		$wp_customize->add_setting( $id, array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $label,
			'section' => 'techrato_more_links',
			'type'    => 'select',
			'choices' => $choices,
		) );
	}
}
add_action( 'customize_register', 'techrato_customize_register' );
