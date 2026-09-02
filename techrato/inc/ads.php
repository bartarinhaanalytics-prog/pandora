<?php
/**
 * Banner and native ad slots.
 *
 * Five fixed positions, all filled from Appearance > تبلیغات بنری و نیتیو.
 * Each slot knows its own size and shape, so the person selling the space only
 * has to upload a picture and paste a link — the theme decides where it goes
 * and how it behaves on mobile.
 *
 * A slot with nothing in it prints nothing at all: an empty box in the middle
 * of the page looks like a bug to a visitor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const TECHRATO_ADS_OPTION = 'techrato_ads';

/**
 * The five positions, described once and used by both the admin screen and the
 * templates.
 *
 * 'fields' controls which inputs the admin screen shows for that slot, so a
 * banner is not asked for a description it will never print.
 *
 * @return array
 */
function techrato_ads_slots() {
	return array(

		/* ---- صفحه نخست ---- */
		'home_top' => array(
			'group'  => 'home',
			'label'  => __( 'زیر اسلایدشو', 'techrato' ),
			'type'   => 'banner',
			'size'   => '728',
			'count'  => 2,
			'fields' => array( 'image', 'url', 'rel', 'code' ),
			'note'   => __( 'دو بنر ۷۲۸×۹۰ کنار هم. در موبایل به دو بنر ۴۶۸×۶۰ زیر هم تبدیل می‌شوند.', 'techrato' ),
		),
		'latest_native' => array(
			'group'     => 'home',
			'label'     => __( 'داخل باکس آخرین اخبار — تبلیغ نیتیو', 'techrato' ),
			'type'      => 'native-row',
			'count'     => 2,
			'positions' => array( 2, 5 ),
			'fields'    => array( 'image', 'title', 'desc', 'url', 'button', 'rel' ),
			'note'      => __( 'تبلیغ اول در جایگاه دوم و تبلیغ دوم در جایگاه پنجم فهرست نمایش داده می‌شود.', 'techrato' ),
		),
		'sidebar' => array(
			'group'  => 'home',
			'label'  => __( 'ستون کناری — زیر باکس موبایل', 'techrato' ),
			'type'   => 'banner',
			'size'   => '300',
			'count'  => 2,
			'fields' => array( 'image', 'url', 'rel', 'code' ),
			'note'   => __( 'دو بنر ۳۰۰×۱۲۰ زیر هم. در موبایل هم در همان جایگاه نمایش داده می‌شوند.', 'techrato' ),
		),

		/* ---- صفحه دسته‌بندی ---- */
		'cat_top' => array(
			'group'  => 'category',
			'label'  => __( 'بالای باکس آخرین مطالب', 'techrato' ),
			'type'   => 'banner',
			'size'   => '728',
			'count'  => 2,
			'fields' => array( 'image', 'url', 'rel', 'code' ),
			'note'   => __( 'دو بنر ۷۲۸×۹۰ کنار هم. در موبایل به دو بنر ۴۶۸×۶۰ زیر هم تبدیل می‌شوند.', 'techrato' ),
		),
		'cat_native' => array(
			'group'     => 'category',
			'label'     => __( 'داخل لیست مطالب — تبلیغ نیتیو', 'techrato' ),
			'type'      => 'native-row',
			'count'     => 2,
			'positions' => array( 2, 5 ),
			'fields'    => array( 'image', 'title', 'desc', 'url', 'button', 'rel' ),
			'note'      => __( 'تبلیغ اول در جایگاه دوم و تبلیغ دوم در جایگاه پنجم فهرست نمایش داده می‌شود.', 'techrato' ),
		),

		/* ---- صفحه مطلب ---- */
		'single_top' => array(
			'group'  => 'single',
			'label'  => __( 'بالای مطلب — زیر لایک و دیدگاه', 'techrato' ),
			'type'   => 'banner',
			'size'   => '728',
			'count'  => 1,
			'fields' => array( 'image', 'url', 'rel', 'code' ),
			'note'   => __( 'یک بنر ۷۲۸×۹۰ که در موبایل ۴۶۸×۶۰ می‌شود.', 'techrato' ),
		),
		'single_bottom' => array(
			'group'  => 'single',
			'label'  => __( 'انتهای مطلب — زیر تگ‌ها', 'techrato' ),
			'type'   => 'banner',
			'size'   => '728',
			'count'  => 2,
			'fields' => array( 'image', 'url', 'rel', 'code' ),
			'note'   => __( 'دو بنر ۷۲۸×۹۰ کنار هم. در موبایل به دو بنر ۴۶۸×۶۰ زیر هم تبدیل می‌شوند.', 'techrato' ),
		),

		/* ---- همه‌ی صفحات ---- */
		'apps_native' => array(
			'group'  => 'global',
			'label'  => __( 'بالای باکس معرفی نرم‌افزار — نیتیو', 'techrato' ),
			'type'   => 'native-grid',
			'count'  => 6,
			'fields' => array( 'image', 'title', 'url', 'rel' ),
			'note'   => __( 'شش تبلیغ: در دسکتاپ دو ردیف سه‌تایی و در موبایل سه ردیف دوتایی.', 'techrato' ),
		),
		'footer_banner' => array(
			'group'  => 'global',
			'label'  => __( 'زیر تبلیغات لینکی', 'techrato' ),
			'type'   => 'banner',
			'size'   => '728',
			'count'  => 2,
			'fields' => array( 'image', 'url', 'rel', 'code' ),
			'note'   => __( 'دو بنر ۷۲۸×۹۰ کنار هم. در موبایل به دو بنر ۴۶۸×۶۰ زیر هم تبدیل می‌شوند.', 'techrato' ),
		),
	);
}

/**
 * The panel is grouped by the page each slot belongs to, so nobody has to
 * remember which of nine positions lives where.
 *
 * @return array group key => heading
 */
function techrato_ads_groups() {
	return array(
		'home'     => __( 'صفحه نخست', 'techrato' ),
		'category' => __( 'صفحه دسته‌بندی', 'techrato' ),
		'single'   => __( 'صفحه مطلب', 'techrato' ),
		'global'   => __( 'همه‌ی صفحات', 'techrato' ),
	);
}

/**
 * Everything stored, with defaults filled in and each slot present.
 *
 * @return array
 */
function techrato_ads_settings() {
	$saved = get_option( TECHRATO_ADS_OPTION, array() );
	$saved = is_array( $saved ) ? $saved : array();

	$settings = array(
		'enabled' => isset( $saved['enabled'] ) ? (int) (bool) $saved['enabled'] : 1,
		'tag'     => isset( $saved['tag'] ) ? (string) $saved['tag'] : __( 'تبلیغات', 'techrato' ),
		'slots'   => array(),
	);

	$stored = isset( $saved['slots'] ) && is_array( $saved['slots'] ) ? $saved['slots'] : array();

	foreach ( techrato_ads_slots() as $key => $slot ) {
		$one   = isset( $stored[ $key ] ) && is_array( $stored[ $key ] ) ? $stored[ $key ] : array();
		$items = isset( $one['items'] ) && is_array( $one['items'] ) ? array_values( $one['items'] ) : array();

		$settings['slots'][ $key ] = array(
			'enabled' => isset( $one['enabled'] ) ? (int) (bool) $one['enabled'] : 1,
			'items'   => array_slice( $items, 0, $slot['count'] ),
		);
	}

	return $settings;
}

/**
 * The ready-to-show items of one slot.
 *
 * A row with neither a picture nor an ad code is a half-filled form, not an ad,
 * so it never reaches the page.
 *
 * @param string $key Slot key.
 * @return array
 */
function techrato_ads_items( $key ) {
	$slots = techrato_ads_slots();
	if ( ! isset( $slots[ $key ] ) ) {
		return array();
	}

	$settings = techrato_ads_settings();
	if ( ! $settings['enabled'] || empty( $settings['slots'][ $key ]['enabled'] ) ) {
		return array();
	}

	$ready = array();
	foreach ( $settings['slots'][ $key ]['items'] as $item ) {
		$item = is_array( $item ) ? $item : array();

		$item = wp_parse_args( $item, array(
			'image'  => 0,
			'url'    => '',
			'title'  => '',
			'desc'   => '',
			'button' => '',
			'rel'    => 'sponsored',
			'code'   => '',
		) );

		$item['image'] = (int) $item['image'];
		$item['code']  = in_array( 'code', $slots[ $key ]['fields'], true ) ? trim( (string) $item['code'] ) : '';

		if ( ! $item['image'] && '' === $item['code'] ) {
			continue;
		}

		$ready[] = $item;
	}

	return array_slice( $ready, 0, $slots[ $key ]['count'] );
}

/**
 * Whether a slot has anything to print.
 *
 * @param string $key Slot key.
 * @return bool
 */
function techrato_ads_has( $key ) {
	return (bool) techrato_ads_items( $key );
}

/**
 * The label printed above each block, e.g. "تبلیغات".
 *
 * @return string
 */
function techrato_ads_tag() {
	$settings = techrato_ads_settings();
	return $settings['tag'];
}

/**
 * The rel attribute for an ad link.
 *
 * Paid placements get "sponsored"; noopener is always added because every ad
 * opens in a new tab.
 *
 * @param array $item Ad row.
 * @return string
 */
function techrato_ads_rel( $item ) {
	$rel = function_exists( 'techrato_link_ads_rel' )
		? techrato_link_ads_rel( isset( $item['rel'] ) ? $item['rel'] : 'sponsored' )
		: 'sponsored nofollow';

	$rel = trim( $rel . ' noopener' );

	return $rel;
}

/**
 * The picture of an ad row.
 *
 * @param array  $item Ad row.
 * @param string $size Image size to request.
 * @return string
 */
function techrato_ads_image( $item, $size = 'full' ) {
	$id = isset( $item['image'] ) ? (int) $item['image'] : 0;
	if ( ! $id ) {
		return '';
	}

	$alt = isset( $item['title'] ) && '' !== $item['title'] ? $item['title'] : techrato_ads_tag();

	return wp_get_attachment_image( $id, $size, false, array(
		'loading' => 'lazy',
		'alt'     => $alt,
	) );
}

/**
 * One banner: either the pasted ad code, or a picture wrapped in its link.
 *
 * @param array  $item Ad row.
 * @param string $size Image size to request.
 * @return string
 */
function techrato_ads_banner_unit( $item, $size = 'full' ) {
	if ( '' !== $item['code'] ) {
		// Ad networks hand out a snippet; it has to go through untouched or it
		// stops working, and only an administrator can put one here.
		return '<div class="ad-unit ad-unit--code">' . $item['code'] . '</div>';
	}

	$image = techrato_ads_image( $item, $size );
	if ( '' === $image ) {
		return '';
	}

	if ( '' === $item['url'] ) {
		return '<div class="ad-unit">' . $image . '</div>';
	}

	return sprintf(
		'<a class="ad-unit" href="%s" rel="%s" target="_blank">%s</a>',
		esc_url( $item['url'] ),
		esc_attr( techrato_ads_rel( $item ) ),
		$image
	);
}

/**
 * A row of banners with its "تبلیغات" label.
 *
 * @param string $key   Slot key.
 * @param string $extra Extra class on the wrapper.
 */
function techrato_ads_render_banners( $key, $extra = '' ) {
	$items = techrato_ads_items( $key );
	if ( ! $items ) {
		return;
	}

	$slots = techrato_ads_slots();
	$size  = isset( $slots[ $key ]['size'] ) ? $slots[ $key ]['size'] : '728';
	$tag   = techrato_ads_tag();

	$units = array();
	foreach ( $items as $item ) {
		$unit = techrato_ads_banner_unit( $item );
		if ( '' !== $unit ) {
			$units[] = $unit;
		}
	}

	if ( ! $units ) {
		return;
	}

	$classes = 'ad-zone ad-zone--banner ad-zone--' . $size . ' ad-zone--' . str_replace( '_', '-', $key );
	if ( $extra ) {
		$classes .= ' ' . $extra;
	}
	?>
	<div class="<?php echo esc_attr( $classes ); ?>">
		<?php if ( $tag ) : ?>
			<span class="ad-tag"><?php echo esc_html( $tag ); ?></span>
		<?php endif; ?>
		<div class="ad-row">
			<?php echo implode( '', $units ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
	<?php
}

/**
 * A native ad shaped like a post row, for the "آخرین اخبار" list.
 *
 * It repeats the markup of card-list-row on purpose: a native ad that does not
 * sit in the rhythm of the list is just a banner in a strange place. The label
 * and the button are what tell the reader it is an ad.
 *
 * @param array $item Ad row.
 */
function techrato_ads_native_row( $item ) {
	$image  = techrato_ads_image( $item, 'techrato-list' );
	$url    = isset( $item['url'] ) ? $item['url'] : '';
	$button = '' !== trim( (string) $item['button'] ) ? $item['button'] : __( 'مشاهده', 'techrato' );
	$rel    = techrato_ads_rel( $item );
	$tag    = techrato_ads_tag();
	?>
	<article class="card-list-row card-ad">
		<div class="thumb-frame">
			<?php if ( $url ) : ?>
				<a class="thumb" href="<?php echo esc_url( $url ); ?>" rel="<?php echo esc_attr( $rel ); ?>" target="_blank"><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php else : ?>
				<span class="thumb"><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php endif; ?>
		</div>
		<div class="body">
			<?php if ( $tag ) : ?>
				<span class="ad-tag ad-tag--inline"><?php echo esc_html( $tag ); ?></span>
			<?php endif; ?>
			<h3>
				<?php if ( $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>" rel="<?php echo esc_attr( $rel ); ?>" target="_blank"><?php echo esc_html( $item['title'] ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $item['title'] ); ?>
				<?php endif; ?>
			</h3>
			<?php if ( '' !== trim( (string) $item['desc'] ) ) : ?>
				<p class="excerpt"><?php echo esc_html( $item['desc'] ); ?></p>
			<?php endif; ?>
			<?php if ( $url ) : ?>
				<a class="ad-cta" href="<?php echo esc_url( $url ); ?>" rel="<?php echo esc_attr( $rel ); ?>" target="_blank"><?php echo esc_html( $button ); ?></a>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/**
 * Which list positions the native ads of a slot take, keyed by position.
 *
 * Position 2 means "shown as the second item", so the ad is inserted before the
 * post that would otherwise be second; no post is lost.
 *
 * @param string $key Slot key of a native-row slot.
 * @return array position => item
 */
function techrato_ads_native_map( $key ) {
	$slots = techrato_ads_slots();
	if ( ! isset( $slots[ $key ]['positions'] ) ) {
		return array();
	}

	$items = techrato_ads_items( $key );
	if ( ! $items ) {
		return array();
	}

	$positions = $slots[ $key ]['positions'];
	$map       = array();

	foreach ( array_values( $items ) as $index => $item ) {
		if ( isset( $positions[ $index ] ) ) {
			$map[ (int) $positions[ $index ] ] = $item;
		}
	}

	return $map;
}

/**
 * The six-cell native block above the app showcase.
 *
 * @param bool $wrap Wrap it in a container of its own.
 */
function techrato_ads_render_apps_native( $wrap = false ) {
	static $done = false;

	// It belongs above the app showcase, and the app showcase is not on every
	// template — the footer prints it on the pages that have no showcase. This
	// flag keeps the pages that have both from showing it twice.
	if ( $done ) {
		return;
	}

	$items = techrato_ads_items( 'apps_native' );
	if ( ! $items ) {
		return;
	}

	$done = true;
	$tag  = techrato_ads_tag();

	if ( $wrap ) {
		echo '<section class="block ad-zone-block"><div class="container">';
	}
	?>
	<div class="ad-zone ad-zone--native-grid">
		<?php if ( $tag ) : ?>
			<span class="ad-tag"><?php echo esc_html( $tag ); ?></span>
		<?php endif; ?>
		<div class="ad-native-grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$image = techrato_ads_image( $item, 'techrato-card' );
				$url   = isset( $item['url'] ) ? $item['url'] : '';
				$cell  = sprintf(
					'<span class="ad-native-thumb">%s</span><span class="ad-native-title">%s</span>',
					$image,
					esc_html( $item['title'] )
				);
				?>
				<?php if ( $url ) : ?>
					<a class="ad-native-cell" href="<?php echo esc_url( $url ); ?>" rel="<?php echo esc_attr( techrato_ads_rel( $item ) ); ?>" target="_blank"><?php echo $cell; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
				<?php else : ?>
					<div class="ad-native-cell"><?php echo $cell; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	if ( $wrap ) {
		echo '</div></section>';
	}
}

/**
 * The banner pair under the text link ads, on every page.
 */
function techrato_ads_render_footer_banners() {
	if ( ! techrato_ads_has( 'footer_banner' ) ) {
		return;
	}
	?>
	<section class="block ad-zone-block">
		<div class="container">
			<?php techrato_ads_render_banners( 'footer_banner' ); ?>
		</div>
	</section>
	<?php
}

/* -------------------------------------------------------------------------
 * Admin screen
 * ---------------------------------------------------------------------- */

/**
 * Add the page under Appearance, next to the text link ads.
 */
function techrato_ads_menu() {
	add_theme_page(
		__( 'تبلیغات بنری و نیتیو', 'techrato' ),
		__( 'تبلیغات بنری و نیتیو', 'techrato' ),
		'manage_options',
		'techrato-ads',
		'techrato_ads_page'
	);
}
add_action( 'admin_menu', 'techrato_ads_menu' );

/**
 * The media library picker is only needed on this screen.
 *
 * @param string $hook Current admin page.
 */
function techrato_ads_admin_assets( $hook ) {
	if ( false !== strpos( $hook, 'techrato-ads' ) ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'techrato_ads_admin_assets' );

/**
 * Register the option.
 */
function techrato_ads_register() {
	register_setting( 'techrato_ads_group', TECHRATO_ADS_OPTION, array(
		'sanitize_callback' => 'techrato_ads_sanitize',
		'default'           => array(),
	) );
}
add_action( 'admin_init', 'techrato_ads_register' );

/**
 * Clean whatever the form posts.
 *
 * @param mixed $input Submitted value.
 * @return array
 */
function techrato_ads_sanitize( $input ) {
	$input = is_array( $input ) ? $input : array();
	$types = function_exists( 'techrato_link_ads_rel_choices' ) ? array_keys( techrato_link_ads_rel_choices() ) : array( 'sponsored' );

	$clean = array(
		'enabled' => empty( $input['enabled'] ) ? 0 : 1,
		'tag'     => isset( $input['tag'] ) ? sanitize_text_field( $input['tag'] ) : '',
		'slots'   => array(),
	);

	$posted = isset( $input['slots'] ) && is_array( $input['slots'] ) ? $input['slots'] : array();

	foreach ( techrato_ads_slots() as $key => $slot ) {
		$one   = isset( $posted[ $key ] ) && is_array( $posted[ $key ] ) ? $posted[ $key ] : array();
		$rows  = isset( $one['items'] ) && is_array( $one['items'] ) ? $one['items'] : array();
		$items = array();

		foreach ( $rows as $row ) {
			$row = is_array( $row ) ? $row : array();

			$item = array(
				'image'  => isset( $row['image'] ) ? absint( $row['image'] ) : 0,
				'url'    => isset( $row['url'] ) ? esc_url_raw( trim( (string) $row['url'] ) ) : '',
				'title'  => isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '',
				'desc'   => isset( $row['desc'] ) ? sanitize_text_field( $row['desc'] ) : '',
				'button' => isset( $row['button'] ) ? sanitize_text_field( $row['button'] ) : '',
				'rel'    => isset( $row['rel'] ) && in_array( $row['rel'], $types, true ) ? $row['rel'] : 'sponsored',
				'code'   => isset( $row['code'] ) ? trim( (string) $row['code'] ) : '',
			);

			// Fields the slot does not use must not linger in the database and
			// reappear if the slot is ever changed.
			foreach ( array( 'image', 'url', 'title', 'desc', 'button', 'code' ) as $field ) {
				if ( ! in_array( $field, $slot['fields'], true ) ) {
					$item[ $field ] = 'image' === $field ? 0 : '';
				}
			}

			$items[] = $item;
		}

		$clean['slots'][ $key ] = array(
			'enabled' => empty( $one['enabled'] ) ? 0 : 1,
			'items'   => array_slice( array_values( $items ), 0, $slot['count'] ),
		);
	}

	return $clean;
}

/**
 * One editable ad row.
 *
 * @param array  $slot  Slot definition.
 * @param string $key   Slot key.
 * @param array  $item  Row values.
 * @param int    $index Row number, starting at 1 for the label.
 */
function techrato_ads_admin_row( $slot, $key, $item, $index ) {
	$name  = TECHRATO_ADS_OPTION . '[slots][' . $key . '][items][' . $index . ']';
	$item  = wp_parse_args( $item, array( 'image' => 0, 'url' => '', 'title' => '', 'desc' => '', 'button' => '', 'rel' => 'sponsored', 'code' => '' ) );
	$image = (int) $item['image'];
	$has   = in_array( 'code', $slot['fields'], true );
	?>
	<div class="techrato-ad-card">
		<h4><?php /* translators: %d: ad number */ printf( esc_html__( 'تبلیغ شماره %d', 'techrato' ), $index + 1 ); ?></h4>

		<?php if ( in_array( 'image', $slot['fields'], true ) ) : ?>
			<div class="techrato-ad-media">
				<input type="hidden" class="techrato-ad-image" name="<?php echo esc_attr( $name ); ?>[image]" value="<?php echo esc_attr( $image ); ?>">
				<div class="techrato-ad-preview"><?php echo $image ? wp_get_attachment_image( $image, 'medium', false, array( 'style' => 'max-width:260px;height:auto;border-radius:8px;' ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<p>
					<button type="button" class="button techrato-ad-pick"><?php esc_html_e( 'انتخاب عکس', 'techrato' ); ?></button>
					<button type="button" class="button-link techrato-ad-clear" style="margin-inline-start:10px;color:#b32d2e;"><?php esc_html_e( 'حذف عکس', 'techrato' ); ?></button>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( in_array( 'title', $slot['fields'], true ) ) : ?>
			<p>
				<label><strong><?php esc_html_e( 'عنوان', 'techrato' ); ?></strong><br>
					<input type="text" class="large-text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $item['title'] ); ?>">
				</label>
			</p>
		<?php endif; ?>

		<?php if ( in_array( 'desc', $slot['fields'], true ) ) : ?>
			<p>
				<label><strong><?php esc_html_e( 'توضیح کوتاه', 'techrato' ); ?></strong><br>
					<input type="text" class="large-text" name="<?php echo esc_attr( $name ); ?>[desc]" value="<?php echo esc_attr( $item['desc'] ); ?>">
				</label>
			</p>
		<?php endif; ?>

		<?php if ( in_array( 'url', $slot['fields'], true ) ) : ?>
			<p>
				<label><strong><?php esc_html_e( 'آدرس مقصد', 'techrato' ); ?></strong><br>
					<input type="url" class="large-text" dir="ltr" name="<?php echo esc_attr( $name ); ?>[url]" value="<?php echo esc_attr( $item['url'] ); ?>" placeholder="https://example.com">
				</label>
			</p>
		<?php endif; ?>

		<?php if ( in_array( 'button', $slot['fields'], true ) ) : ?>
			<p>
				<label><strong><?php esc_html_e( 'متن دکمه', 'techrato' ); ?></strong><br>
					<input type="text" class="regular-text" name="<?php echo esc_attr( $name ); ?>[button]" value="<?php echo esc_attr( $item['button'] ); ?>" placeholder="<?php esc_attr_e( 'مشاهده', 'techrato' ); ?>">
				</label>
			</p>
		<?php endif; ?>

		<?php if ( in_array( 'rel', $slot['fields'], true ) && function_exists( 'techrato_link_ads_rel_choices' ) ) : ?>
			<p>
				<label><strong><?php esc_html_e( 'نوع لینک', 'techrato' ); ?></strong><br>
					<select name="<?php echo esc_attr( $name ); ?>[rel]">
						<?php foreach ( techrato_link_ads_rel_choices() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $item['rel'], $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
		<?php endif; ?>

		<?php if ( $has ) : ?>
			<p>
				<label><strong><?php esc_html_e( 'یا کد تبلیغ', 'techrato' ); ?></strong><br>
					<textarea class="large-text code" dir="ltr" rows="3" name="<?php echo esc_attr( $name ); ?>[code]"><?php echo esc_textarea( $item['code'] ); ?></textarea>
				</label>
				<span class="description"><?php esc_html_e( 'اگر از شبکه‌ی تبلیغاتی کد گرفته‌اید اینجا بگذارید. در این حالت عکس و لینک بالا نادیده گرفته می‌شوند.', 'techrato' ); ?></span>
			</p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * The settings screen.
 */
function techrato_ads_page() {
	$settings = techrato_ads_settings();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'تبلیغات بنری و نیتیو', 'techrato' ); ?></h1>
		<p><?php esc_html_e( 'جایگاه‌ها ثابت هستند؛ فقط کافی است عکس و لینک هر تبلیغ را وارد کنید. هر جایگاهی که خالی بماند، در سایت اصلاً نمایش داده نمی‌شود.', 'techrato' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'techrato_ads_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'نمایش تبلیغات', 'techrato' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( TECHRATO_ADS_OPTION ); ?>[enabled]" value="1" <?php checked( $settings['enabled'], 1 ); ?>>
							<?php esc_html_e( 'کل سیستم تبلیغات روشن باشد', 'techrato' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="techrato-ads-tag"><?php esc_html_e( 'برچسب بالای تبلیغ‌ها', 'techrato' ); ?></label></th>
					<td>
						<input type="text" id="techrato-ads-tag" class="regular-text" name="<?php echo esc_attr( TECHRATO_ADS_OPTION ); ?>[tag]" value="<?php echo esc_attr( $settings['tag'] ); ?>">
						<p class="description"><?php esc_html_e( 'خالی بگذارید تا برچسبی نمایش داده نشود. حذف آن توصیه نمی‌شود؛ نشان‌دادن تبلیغ بدون برچسب هم برای خواننده و هم برای گوگل مشکل‌ساز است.', 'techrato' ); ?></p>
					</td>
				</tr>
			</table>

			<?php $all_slots = techrato_ads_slots(); ?>
			<?php foreach ( techrato_ads_groups() as $group => $group_label ) : ?>
				<h2 class="techrato-ad-group"><?php echo esc_html( $group_label ); ?></h2>

				<?php foreach ( $all_slots as $key => $slot ) : ?>
					<?php
					if ( $group !== $slot['group'] ) {
						continue;
					}
					$stored = $settings['slots'][ $key ];
					?>
					<h3 style="margin-bottom:4px;"><?php echo esc_html( $slot['label'] ); ?></h3>
					<p class="description" style="margin-bottom:8px;"><?php echo esc_html( $slot['note'] ); ?></p>
					<p>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( TECHRATO_ADS_OPTION . '[slots][' . $key . '][enabled]' ); ?>" value="1" <?php checked( $stored['enabled'], 1 ); ?>>
							<?php esc_html_e( 'این جایگاه فعال باشد', 'techrato' ); ?>
						</label>
					</p>
					<div class="techrato-ad-grid">
						<?php for ( $i = 0; $i < $slot['count']; $i++ ) : ?>
							<?php techrato_ads_admin_row( $slot, $key, isset( $stored['items'][ $i ] ) ? (array) $stored['items'][ $i ] : array(), $i ); ?>
						<?php endfor; ?>
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>
	</div>

	<style>
	.techrato-ad-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px; }
	.techrato-ad-card { background: #fff; border: 1px solid #dcdcde; border-radius: 6px; padding: 12px 16px; }
	.techrato-ad-card h4 { margin: 0 0 10px; }
	.techrato-ad-preview:empty { display: none; }
	.techrato-ad-group {
		margin: 34px 0 6px; padding: 8px 14px;
		background: #1d2327; color: #fff; border-radius: 4px; font-size: 15px;
	}
	.techrato-ad-preview { margin-bottom: 8px; }
	</style>

	<script>
	( function () {
		var frame;
		var active;

		document.addEventListener( 'click', function ( e ) {
			var pick = e.target.closest( '.techrato-ad-pick' );
			if ( pick ) {
				e.preventDefault();
				active = pick.closest( '.techrato-ad-media' );
				if ( ! frame ) {
					frame = wp.media( {
						title: <?php echo wp_json_encode( __( 'انتخاب عکس تبلیغ', 'techrato' ) ); ?>,
						button: { text: <?php echo wp_json_encode( __( 'استفاده از این عکس', 'techrato' ) ); ?> },
						library: { type: 'image' },
						multiple: false
					} );
					frame.on( 'select', function () {
						if ( ! active ) { return; }
						var img = frame.state().get( 'selection' ).first().toJSON();
						var src = ( img.sizes && img.sizes.medium ) ? img.sizes.medium.url : img.url;
						active.querySelector( '.techrato-ad-image' ).value = img.id;
						active.querySelector( '.techrato-ad-preview' ).innerHTML =
							'<img src="' + src + '" style="max-width:260px;height:auto;border-radius:8px;" alt="">';
					} );
				}
				frame.open();
				return;
			}

			var clear = e.target.closest( '.techrato-ad-clear' );
			if ( clear ) {
				e.preventDefault();
				var box = clear.closest( '.techrato-ad-media' );
				box.querySelector( '.techrato-ad-image' ).value = '';
				box.querySelector( '.techrato-ad-preview' ).innerHTML = '';
			}
		} );
	} )();
	</script>
	<?php
}
