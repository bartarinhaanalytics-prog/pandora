<?php
/**
 * A per-category icon for the homepage category tiles.
 *
 * WordPress has no such field, so this adds a picker to the category screens.
 * Until someone chooses one, the icon is guessed from the category slug — a
 * category called "mobile" gets the phone, "car" gets the car — so the grid
 * looks right on day one without anyone touching a setting.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Term meta key holding the chosen icon key.
 */
const TECHRATO_TERM_ICON_KEY = 'techrato_term_icon';

/**
 * The icon set. Each entry is the inner markup of a 24x24 outline icon.
 *
 * @return array key => array( label, svg )
 */
function techrato_category_icons() {
	return array(
		'mobile'   => array( __( 'موبایل', 'techrato' ), '<rect x="7" y="3" width="10" height="18" rx="2.4"/><path d="M10 6h4M11 18h2"/>' ),
		'laptop'   => array( __( 'لپ‌تاپ و کامپیوتر', 'techrato' ), '<rect x="4" y="5" width="16" height="11" rx="2"/><path d="M2 19h20"/>' ),
		'ai'       => array( __( 'هوش مصنوعی', 'techrato' ), '<circle cx="12" cy="12" r="3.2"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>' ),
		'car'      => array( __( 'خودرو', 'techrato' ), '<path d="M4 16v-3l2-5h12l2 5v3"/><path d="M3 16h18"/><circle cx="7.5" cy="17.5" r="1.6"/><circle cx="16.5" cy="17.5" r="1.6"/>' ),
		'gadget'   => array( __( 'گجت', 'techrato' ), '<rect x="8" y="2" width="8" height="20" rx="4"/><path d="M12 7v4"/>' ),
		'camera'   => array( __( 'عکاسی و دوربین', 'techrato' ), '<rect x="3" y="7" width="18" height="13" rx="2.5"/><circle cx="12" cy="13.5" r="3.4"/><path d="M9 7l1.5-3h3L15 7"/>' ),
		'game'     => array( __( 'بازی', 'techrato' ), '<rect x="2" y="7" width="20" height="10" rx="5"/><path d="M7 12h3M8.5 10.5v3"/><circle cx="16" cy="11" r="1"/><circle cx="18" cy="13.5" r="1"/>' ),
		'app'      => array( __( 'اپلیکیشن و نرم‌افزار', 'techrato' ), '<rect x="3" y="3" width="7.5" height="7.5" rx="2"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="2"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="2"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="2"/>' ),
		'security' => array( __( 'امنیت', 'techrato' ), '<path d="M12 3l7 3v6c0 4.2-2.9 7.6-7 9-4.1-1.4-7-4.8-7-9V6l7-3Z"/><path d="M9.5 12l1.8 1.8 3.4-3.6"/>' ),
		'crypto'   => array( __( 'ارز دیجیتال', 'techrato' ), '<circle cx="12" cy="12" r="9"/><path d="M9.5 8.5h4.2a2.2 2.2 0 0 1 0 4.4H9.5m0 0h4.6a2.2 2.2 0 0 1 0 4.4H9.5m0-8.8V17M11 6.5v2M11 17v2"/>' ),
		'internet' => array( __( 'اینترنت و شبکه', 'techrato' ), '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9S14.5 18.4 12 21c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3Z"/>' ),
		'science'  => array( __( 'علمی و فضا', 'techrato' ), '<circle cx="12" cy="12" r="3"/><ellipse cx="12" cy="12" rx="10" ry="4.2" transform="rotate(-30 12 12)"/>' ),
		'business' => array( __( 'کسب و کار', 'techrato' ), '<rect x="3" y="8" width="18" height="12" rx="2.4"/><path d="M9 8V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 13h18"/>' ),
		'video'    => array( __( 'ویدیو', 'techrato' ), '<rect x="3" y="6" width="13" height="12" rx="2.4"/><path d="M16 10.5l5-2.6v8.2l-5-2.6Z"/>' ),
		'health'   => array( __( 'سلامت', 'techrato' ), '<path d="M20.8 6.6a4.9 4.9 0 0 0-7 0L12 8.4l-1.8-1.8a4.9 4.9 0 1 0-7 7L12 21l8.8-7.4a4.9 4.9 0 0 0 0-7Z"/>' ),
		'tv'       => array( __( 'سینما و تلویزیون', 'techrato' ), '<rect x="2.5" y="6.5" width="19" height="12" rx="2.4"/><path d="M8 3.5l4 3 4-3M9 21.5h6"/>' ),
		'shopping' => array( __( 'راهنمای خرید', 'techrato' ), '<path d="M4.5 8h15l-1.2 11.2a2 2 0 0 1-2 1.8H7.7a2 2 0 0 1-2-1.8Z"/><path d="M9 8V6.2a3 3 0 0 1 6 0V8"/>' ),
		'iran'     => array( __( 'فناوری ایران', 'techrato' ), '<path d="M12 21s7-5.1 7-10.4A7 7 0 0 0 5 10.6C5 15.9 12 21 12 21Z"/><circle cx="12" cy="10.4" r="2.6"/>' ),
		'tablet'   => array( __( 'تبلت', 'techrato' ), '<rect x="4" y="2.5" width="16" height="19" rx="2.6"/><path d="M10.5 18.5h3"/>' ),
		'headphone' => array( __( 'هدفون و صوتی', 'techrato' ), '<path d="M4 15v-2.5a8 8 0 0 1 16 0V15"/><rect x="2.5" y="14" width="4.5" height="6.5" rx="2"/><rect x="17" y="14" width="4.5" height="6.5" rx="2"/>' ),
		'news'     => array( __( 'اخبار', 'techrato' ), '<rect x="3" y="5" width="14.5" height="14" rx="2.2"/><path d="M17.5 9H21v8a2 2 0 0 1-2 2h-1.5ZM6.5 9h7M6.5 12.5h7M6.5 16h4"/>' ),
		'star'     => array( __( 'عمومی', 'techrato' ), '<path d="M12 3.5l2.6 5.4 5.9.8-4.3 4.1 1.1 5.8-5.3-2.9-5.3 2.9 1.1-5.8L3.5 9.7l5.9-.8Z"/>' ),
	);
}

/**
 * Guess an icon from the slug when nobody has chosen one.
 *
 * @param WP_Term|int $term Term or term ID.
 * @return string Icon key.
 */
function techrato_guess_category_icon( $term ) {
	$term = is_numeric( $term ) ? get_term( (int) $term, 'category' ) : $term;
	if ( ! $term instanceof WP_Term ) {
		return 'star';
	}

	$haystack = strtolower( $term->slug . ' ' . $term->name );

	// Order matters: the specific words are tried before the generic ones, so
	// "اخبار سینما و تلویزیون" lands on the television icon rather than on the
	// plain news icon.
	$map = array(
		'headphone' => array( 'headphone', 'earbud', 'audio', 'speaker', 'هدفون', 'ایرباد', 'هندزفری', 'صوتی', 'اسپیکر' ),
		'mobile'    => array( 'mobile', 'smartphone', 'phone', 'iphone', 'android', 'galaxy', 'موبایل', 'گوشی', 'آیفون', 'اندروید' ),
		'tablet'    => array( 'tablet', 'ipad', 'تبلت', 'آیپد' ),
		'laptop'    => array( 'laptop', 'computer', 'desktop', 'pc', 'hardware', 'cpu', 'gpu', 'لپ', 'کامپیوتر', 'رایانه', 'سخت‌افزار', 'سخت افزار', 'پردازنده', 'کارت گرافیک' ),
		'ai'        => array( 'ai', 'artificial', 'chatgpt', 'machine-learning', 'هوش مصنوعی', 'هوش' ),
		'car'       => array( 'car', 'khodro', 'auto', 'vehicle', 'ev', 'خودرو', 'ماشین', 'موتورسیکلت' ),
		'camera'    => array( 'camera', 'photo', 'photography', 'دوربین', 'عکاسی' ),
		'game'      => array( 'game', 'gaming', 'console', 'playstation', 'xbox', 'بازی', 'کنسول', 'پلی استیشن' ),
		'tv'        => array( 'tv', 'television', 'cinema', 'movie', 'series', 'film', 'تلویزیون', 'سینما', 'سریال', 'فیلم' ),
		'video'     => array( 'video', 'youtube', 'stream', 'ویدیو', 'یوتیوب', 'پخش زنده' ),
		'app'       => array( 'app', 'software', 'application', 'windows', 'اپلیکیشن', 'اپ', 'نرم‌افزار', 'نرم افزار', 'ویندوز' ),
		'security'  => array( 'security', 'privacy', 'hack', 'malware', 'امنیت', 'حریم', 'هک', 'بدافزار' ),
		'crypto'    => array( 'crypto', 'bitcoin', 'blockchain', 'nft', 'ارز دیجیتال', 'رمزارز', 'بیت کوین', 'بلاک' ),
		'internet'  => array( 'internet', 'network', 'web', 'wifi', '5g', 'اینترنت', 'شبکه', 'وب', 'فیلترینگ' ),
		'science'   => array( 'science', 'space', 'astronomy', 'nasa', 'علمی', 'علم', 'فضا', 'نجوم' ),
		'health'    => array( 'health', 'medical', 'fitness', 'سلامت', 'پزشکی', 'تندرستی' ),
		'shopping'  => array( 'shop', 'buy', 'buying-guide', 'price', 'store', 'market', 'خرید', 'قیمت', 'فروشگاه', 'بازار' ),
		'business'  => array( 'business', 'economy', 'startup', 'company', 'کسب', 'اقتصاد', 'استارتاپ', 'شرکت' ),
		'gadget'    => array( 'gadget', 'wearable', 'smartwatch', 'drone', 'گجت', 'پوشیدنی', 'ساعت هوشمند', 'پهپاد' ),
		'iran'      => array( 'iran', 'ایران', 'داخلی', 'بومی' ),
		'news'      => array( 'news', 'اخبار', 'خبر', 'فناوری', 'تکنولوژی', 'تک' ),
	);

	foreach ( $map as $icon => $needles ) {
		foreach ( $needles as $needle ) {
			if ( false !== strpos( $haystack, $needle ) ) {
				return $icon;
			}
		}
	}

	return 'star';
}

/**
 * The icon markup for a category.
 *
 * @param int $term_id Category ID.
 * @return string
 */
function techrato_term_icon( $term_id ) {
	$icons = techrato_category_icons();
	$key   = (string) get_term_meta( $term_id, TECHRATO_TERM_ICON_KEY, true );

	if ( '' === $key || ! isset( $icons[ $key ] ) ) {
		$key = techrato_guess_category_icon( $term_id );
	}

	return '<svg viewBox="0 0 24 24" aria-hidden="true">' . $icons[ $key ][1] . '</svg>';
}

/* -------------------------------------------------------------------------
 * Admin field
 * ---------------------------------------------------------------------- */

/**
 * The picker itself, shared by the add and edit forms.
 *
 * @param string $current Currently stored key.
 */
function techrato_term_icon_control( $current = '' ) {
	?>
	<select name="techrato_term_icon" class="techrato-term-icon">
		<option value=""><?php esc_html_e( 'خودکار (بر اساس نام دسته)', 'techrato' ); ?></option>
		<?php foreach ( techrato_category_icons() as $key => $icon ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current, $key ); ?>><?php echo esc_html( $icon[0] ); ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'این آیکون در کاشی دسته‌بندی‌های صفحه نخست نمایش داده می‌شود.', 'techrato' ); ?></p>
	<?php
}

/**
 * Field on the "add new category" form.
 */
function techrato_term_icon_add_field() {
	?>
	<div class="form-field">
		<label><?php esc_html_e( 'آیکون دسته‌بندی', 'techrato' ); ?></label>
		<?php techrato_term_icon_control(); ?>
	</div>
	<?php
}
add_action( 'category_add_form_fields', 'techrato_term_icon_add_field' );

/**
 * Field on the "edit category" form.
 *
 * @param WP_Term $term Term being edited.
 */
function techrato_term_icon_edit_field( $term ) {
	?>
	<tr class="form-field">
		<th scope="row"><label><?php esc_html_e( 'آیکون دسته‌بندی', 'techrato' ); ?></label></th>
		<td><?php techrato_term_icon_control( (string) get_term_meta( $term->term_id, TECHRATO_TERM_ICON_KEY, true ) ); ?></td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'techrato_term_icon_edit_field' );

/**
 * Store the choice.
 *
 * @param int $term_id Term being saved.
 */
function techrato_term_icon_save( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	// The field is absent on quick-edit; leaving the stored value alone is the
	// right move there.
	if ( ! isset( $_POST['techrato_term_icon'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	$key   = sanitize_key( wp_unslash( $_POST['techrato_term_icon'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$icons = techrato_category_icons();

	if ( $key && isset( $icons[ $key ] ) ) {
		update_term_meta( $term_id, TECHRATO_TERM_ICON_KEY, $key );
	} else {
		delete_term_meta( $term_id, TECHRATO_TERM_ICON_KEY );
	}
}
add_action( 'created_category', 'techrato_term_icon_save' );
add_action( 'edited_category', 'techrato_term_icon_save' );
