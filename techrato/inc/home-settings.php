<?php
/**
 * Homepage settings.
 *
 * Every block on the front page is described once in techrato_home_blocks()
 * and the Customizer panel is generated from that description, so adding a
 * setting means editing one array rather than three files.
 *
 * Values are read with techrato_home_option( 'block', 'field' ).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Every homepage block and the settings it exposes.
 *
 * Field types: 'toggle', 'text', 'number', 'category'.
 *
 * @return array
 */
function techrato_home_blocks() {
	$blocks = array(

		'quick' => array(
			'label'  => __( 'ردیف دسته‌های سریع (فقط موبایل)', 'techrato' ),
			'fields' => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
			),
		),

		'hero' => array(
			'label'       => __( 'تیتر اصلی و نوشته‌های شاخص', 'techrato' ),
			'description' => __( 'مطلب بزرگ از تیک «تیتر اصلی صفحه نخست» و کارت‌های کناری از تیک «نوشته شاخص» خوانده می‌شوند.', 'techrato' ),
			'fields'      => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد کارت‌های کناری', 'techrato' ), 'default' => 4, 'min' => 1, 'max' => 8 ),
			),
		),

		'editors' => array(
			'label'       => __( 'پیشنهادهای سردبیر', 'techrato' ),
			'description' => __( 'از تیک «پیشنهاد سردبیر» در صفحه ویرایش مطلب خوانده می‌شود.', 'techrato' ),
			'fields'      => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title'   => array( 'type' => 'text', 'label' => __( 'عنوان', 'techrato' ), 'default' => 'پیشنهادهای سردبیر' ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب', 'techrato' ), 'default' => 6, 'min' => 1, 'max' => 12 ),
			),
		),

		'latest' => array(
			'label'       => __( 'باکس «آخرین اخبار»', 'techrato' ),
			'description' => __( 'جدیدترین مطالب کل سایت، بدون فیلتر دسته.', 'techrato' ),
			'fields'      => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title'   => array( 'type' => 'text', 'label' => __( 'عنوان', 'techrato' ), 'default' => 'آخرین اخبار' ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب در هر بار', 'techrato' ), 'default' => 4, 'min' => 1, 'max' => 20 ),
			),
		),

		'sidebar' => array(
			'label'       => __( 'باکس نوار کناری', 'techrato' ),
			'description' => __( 'اگر عنوان را خالی بگذارید، نام همان دسته استفاده می‌شود.', 'techrato' ),
			'fields'      => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title'   => array( 'type' => 'text', 'label' => __( 'عنوان', 'techrato' ), 'default' => '' ),
				'cat'     => array( 'type' => 'category', 'label' => __( 'دسته', 'techrato' ), 'default' => 0 ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب در هر بار', 'techrato' ), 'default' => 3, 'min' => 1, 'max' => 12 ),
			),
		),

		'follow' => array(
			'label'  => __( 'باکس «ما را دنبال کنید» در نوار کناری', 'techrato' ),
			'fields' => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
			),
		),

		'popular' => array(
			'label'       => __( 'پربازدیدترین مطالب', 'techrato' ),
			'description' => __( 'بر اساس تعداد بازدید واقعی. تا وقتی آمار جمع نشده، موقتاً بر اساس تعداد دیدگاه مرتب می‌شود.', 'techrato' ),
			'fields'      => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title'   => array( 'type' => 'text', 'label' => __( 'عنوان', 'techrato' ), 'default' => 'پربازدید ترین مطالب' ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب', 'techrato' ), 'default' => 3, 'min' => 1, 'max' => 12 ),
				'days'    => array( 'type' => 'number', 'label' => __( 'از چند روز اخیر', 'techrato' ), 'default' => 7, 'min' => 1, 'max' => 365 ),
			),
		),

		'banner' => array(
			'label'  => __( 'بنر شبکه‌های اجتماعی', 'techrato' ),
			'fields' => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
			),
		),

		'iran' => array(
			'label'  => __( 'بخش «آخرین اخبار فناوری ایران»', 'techrato' ),
			'fields' => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title'   => array( 'type' => 'text', 'label' => __( 'عنوان', 'techrato' ), 'default' => 'آخرین اخبار فناوری ایران' ),
				'cat'     => array( 'type' => 'category', 'label' => __( 'دسته', 'techrato' ), 'default' => 0 ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب فهرست کناری', 'techrato' ), 'default' => 3, 'min' => 1, 'max' => 8 ),
			),
		),

		'columns' => array(
			'label'       => __( 'سه ستون اخبار مرتبط', 'techrato' ),
			'description' => __( 'سه ستون پایین صفحه. برای هر ستون یک عنوان و یک دسته.', 'techrato' ),
			'fields'      => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title1'  => array( 'type' => 'text', 'label' => __( 'عنوان ستون ۱', 'techrato' ), 'default' => 'اخبار مرتبط با کسب و کار' ),
				'cat1'    => array( 'type' => 'category', 'label' => __( 'دسته ستون ۱', 'techrato' ), 'default' => 0 ),
				'title2'  => array( 'type' => 'text', 'label' => __( 'عنوان ستون ۲', 'techrato' ), 'default' => 'اخبار مرتبط با خودرو' ),
				'cat2'    => array( 'type' => 'category', 'label' => __( 'دسته ستون ۲', 'techrato' ), 'default' => 0 ),
				'title3'  => array( 'type' => 'text', 'label' => __( 'عنوان ستون ۳', 'techrato' ), 'default' => 'اخبار مرتبط با هوش مصنوعی' ),
				'cat3'    => array( 'type' => 'category', 'label' => __( 'دسته ستون ۳', 'techrato' ), 'default' => 0 ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب هر ستون', 'techrato' ), 'default' => 4, 'min' => 2, 'max' => 8 ),
			),
		),

		'apps' => array(
			'label'  => __( 'معرفی نرم‌افزار و اپلیکیشن', 'techrato' ),
			'fields' => array(
				'enabled' => array( 'type' => 'toggle', 'label' => __( 'نمایش این بخش', 'techrato' ), 'default' => 1 ),
				'title'   => array( 'type' => 'text', 'label' => __( 'عنوان', 'techrato' ), 'default' => 'معرفی نرم افزار و اپلیکیشن' ),
				'cat'     => array( 'type' => 'category', 'label' => __( 'دسته', 'techrato' ), 'default' => 0 ),
				'count'   => array( 'type' => 'number', 'label' => __( 'تعداد مطالب', 'techrato' ), 'default' => 4, 'min' => 2, 'max' => 12 ),
			),
		),
	);

	return apply_filters( 'techrato_home_blocks', $blocks );
}

/**
 * Read one homepage setting, falling back to the value declared above.
 *
 * @param string $block Block key.
 * @param string $field Field key.
 * @return mixed
 */
function techrato_home_option( $block, $field ) {
	$blocks = techrato_home_blocks();
	if ( ! isset( $blocks[ $block ]['fields'][ $field ] ) ) {
		return null;
	}

	$spec  = $blocks[ $block ]['fields'][ $field ];
	$value = get_theme_mod( 'home_' . $block . '_' . $field, $spec['default'] );

	switch ( $spec['type'] ) {
		case 'toggle':
			return (bool) $value;
		case 'number':
			$value = (int) $value;
			if ( isset( $spec['min'] ) ) {
				$value = max( (int) $spec['min'], $value );
			}
			if ( isset( $spec['max'] ) ) {
				$value = min( (int) $spec['max'], $value );
			}
			return $value;
		case 'category':
			return (int) $value;
	}

	return (string) $value;
}

/**
 * Whether a homepage block should render at all.
 */
function techrato_home_shows( $block ) {
	return (bool) techrato_home_option( $block, 'enabled' );
}

/**
 * The category a block reads from: the one chosen in the Customizer, else the
 * slugs the block is normally built around.
 *
 * @param string $block     Block key.
 * @param string $field     Field key holding the category ID.
 * @param array  $fallbacks Slugs to try when nothing is chosen.
 * @return WP_Term|null
 */
function techrato_home_term( $block, $field, $fallbacks = array() ) {
	$chosen = (int) techrato_home_option( $block, $field );
	if ( $chosen ) {
		$term = get_term( $chosen, 'category' );
		if ( $term instanceof WP_Term ) {
			return $term;
		}
	}

	foreach ( (array) $fallbacks as $slug ) {
		$term = get_category_by_slug( $slug );
		if ( $term ) {
			return $term;
		}
	}

	return null;
}

/**
 * Build the Customizer panel from the block description.
 */
function techrato_home_customize( $wp_customize ) {
	$wp_customize->add_panel( 'techrato_home', array(
		'title'       => __( 'صفحه نخست تکراتو', 'techrato' ),
		'description' => __( 'هر بخش صفحه اصلی یک قسمت جداگانه دارد: می‌توانید آن را خاموش کنید، عنوانش را عوض کنید، دسته‌اش را انتخاب کنید و تعداد مطالبش را تعیین کنید.', 'techrato' ),
		'priority'    => 29,
	) );

	$categories = array( 0 => __( '— خودکار —', 'techrato' ) );
	foreach ( get_categories( array( 'hide_empty' => false ) ) as $category ) {
		$categories[ $category->term_id ] = $category->name;
	}

	foreach ( techrato_home_blocks() as $key => $block ) {
		$section = 'techrato_home_' . $key;

		$wp_customize->add_section( $section, array(
			'title'       => $block['label'],
			'description' => isset( $block['description'] ) ? $block['description'] : '',
			'panel'       => 'techrato_home',
		) );

		foreach ( $block['fields'] as $field => $spec ) {
			$id = 'home_' . $key . '_' . $field;

			switch ( $spec['type'] ) {
				case 'toggle':
					$sanitize = 'techrato_sanitize_toggle';
					$control  = array( 'type' => 'checkbox' );
					break;
				case 'number':
					$sanitize = 'absint';
					$control  = array(
						'type'        => 'number',
						'input_attrs' => array(
							'min' => isset( $spec['min'] ) ? $spec['min'] : 1,
							'max' => isset( $spec['max'] ) ? $spec['max'] : 50,
						),
					);
					break;
				case 'category':
					$sanitize = 'absint';
					$control  = array( 'type' => 'select', 'choices' => $categories );
					break;
				default:
					$sanitize = 'sanitize_text_field';
					$control  = array( 'type' => 'text' );
			}

			$wp_customize->add_setting( $id, array(
				'default'           => $spec['default'],
				'sanitize_callback' => $sanitize,
			) );

			$wp_customize->add_control( $id, array_merge( $control, array(
				'label'   => $spec['label'],
				'section' => $section,
			) ) );
		}
	}
}
add_action( 'customize_register', 'techrato_home_customize' );

/**
 * Checkbox sanitiser — the Customizer sends '' or '1'.
 */
function techrato_sanitize_toggle( $value ) {
	return $value ? 1 : 0;
}
