<?php
/**
 * Classic editor toolbar.
 *
 * WordPress ships a deliberately small toolbar and hides a second row behind
 * a toggle. Everything added here comes with TinyMCE already — no plugin
 * needed. Tables are the one common request that genuinely does need one,
 * since WordPress does not bundle the TinyMCE table plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * First toolbar row: the buttons writers reach for constantly.
 */
function techrato_mce_buttons( $buttons ) {
	// Drop the "show/hide second row" toggle — the second row is always on.
	$buttons = array_values( array_diff( $buttons, array( 'wp_adv' ) ) );

	$extra = array( 'underline', 'alignjustify', 'styleselect' );
	foreach ( $extra as $button ) {
		if ( ! in_array( $button, $buttons, true ) ) {
			$buttons[] = $button;
		}
	}

	return $buttons;
}
add_filter( 'mce_buttons', 'techrato_mce_buttons' );

/**
 * Second toolbar row: formatting, colours and clean-up tools.
 */
function techrato_mce_buttons_2( $buttons ) {
	$extra = array(
		'fontsizeselect', // text size
		'forecolor',      // text colour
		'backcolor',      // highlight
		'superscript',
		'subscript',
		'charmap',        // symbols: ° © ½ …
		'ltr',            // flip a paragraph to left-to-right for English or code
	);

	foreach ( $extra as $button ) {
		if ( ! in_array( $button, $buttons, true ) ) {
			$buttons[] = $button;
		}
	}

	return $buttons;
}
add_filter( 'mce_buttons_2', 'techrato_mce_buttons_2' );

/**
 * Editor configuration: keep the second row open, offer sensible font sizes,
 * and put the theme's own named styles in the "قالب‌ها" dropdown so writers
 * apply house styles by name instead of hand-formatting each time.
 */
function techrato_tiny_mce_before_init( $init ) {
	$init['wordpress_adv_hidden'] = false;
	$init['fontsize_formats']     = '12px 13px 14px 15px 16px 18px 20px 24px 30px';

	// Paragraph, heading and preformatted only — no "Address" or "Div", which
	// only ever get picked by accident.
	$init['block_formats'] = 'پاراگراف=p;تیتر ۲=h2;تیتر ۳=h3;تیتر ۴=h4;کد=pre';

	$init['style_formats'] = wp_json_encode( array(
		array(
			'title'   => __( 'لید مطلب', 'techrato' ),
			'block'   => 'p',
			'classes' => 'is-lead',
		),
		array(
			'title'   => __( 'جعبه نکته', 'techrato' ),
			'block'   => 'div',
			'classes' => 'is-note',
			'wrapper' => true,
		),
		array(
			'title'   => __( 'نقل‌قول برجسته', 'techrato' ),
			'block'   => 'blockquote',
			'classes' => 'is-pull-quote',
		),
		array(
			'title'   => __( 'متن کوچک', 'techrato' ),
			'inline'  => 'span',
			'classes' => 'is-small',
		),
		array(
			'title'   => __( 'متن انگلیسی (چپ‌چین)', 'techrato' ),
			'inline'  => 'span',
			'classes' => 'is-ltr',
		),
	) );

	return $init;
}
add_filter( 'tiny_mce_before_init', 'techrato_tiny_mce_before_init' );
