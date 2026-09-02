<?php
/**
 * Text link ads shown above the footer on every page.
 *
 * Managed from Appearance > تبلیغات لینکی. Each link chooses its own rel:
 * a paid link that passes ranking signals is what Google penalises sites for,
 * so the choice is explicit rather than assumed.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const TECHRATO_LINK_ADS_OPTION = 'techrato_link_ads';

/**
 * Stored settings, with defaults filled in.
 *
 * @return array
 */
function techrato_link_ads_settings() {
	$saved = get_option( TECHRATO_LINK_ADS_OPTION, array() );
	$saved = is_array( $saved ) ? $saved : array();

	return array(
		'enabled' => isset( $saved['enabled'] ) ? (int) (bool) $saved['enabled'] : 1,
		'title'   => isset( $saved['title'] ) ? (string) $saved['title'] : __( 'تبلیغات لینکی', 'techrato' ),
		'items'   => isset( $saved['items'] ) && is_array( $saved['items'] ) ? $saved['items'] : array(),
	);
}

/**
 * The rel attribute for each link type.
 *
 * @return array
 */
function techrato_link_ads_rel_choices() {
	return array(
		'follow'    => __( 'فالو (اعتبار سئو منتقل می‌شود)', 'techrato' ),
		'nofollow'  => __( 'نوفالو (اعتبار منتقل نمی‌شود)', 'techrato' ),
		'sponsored' => __( 'تبلیغاتی / اسپانسری (پیشنهاد گوگل برای لینک پولی)', 'techrato' ),
	);
}

/**
 * Turn the stored type into a rel value.
 *
 * @param string $type follow|nofollow|sponsored
 * @return string Empty for a plain follow link.
 */
function techrato_link_ads_rel( $type ) {
	switch ( $type ) {
		case 'nofollow':
			return 'nofollow';
		case 'sponsored':
			// Google reads "sponsored" for paid placements; nofollow stays for
			// older crawlers that do not know the newer value.
			return 'sponsored nofollow';
	}

	return '';
}

/**
 * Print the section. Called from footer.php on every page.
 */
function techrato_link_ads_render() {
	$settings = techrato_link_ads_settings();

	if ( ! $settings['enabled'] || ! $settings['items'] ) {
		return;
	}

	$links = array();
	foreach ( $settings['items'] as $item ) {
		$text = isset( $item['text'] ) ? trim( (string) $item['text'] ) : '';
		$url  = isset( $item['url'] ) ? trim( (string) $item['url'] ) : '';
		if ( '' === $text || '' === $url ) {
			continue;
		}
		$links[] = array(
			'text' => $text,
			'url'  => $url,
			'rel'  => techrato_link_ads_rel( isset( $item['rel'] ) ? $item['rel'] : 'nofollow' ),
		);
	}

	if ( ! $links ) {
		return;
	}
	?>
	<section class="block link-ads-wrap">
		<div class="container">
			<div class="link-ads">
				<?php if ( $settings['title'] ) : ?>
					<span class="link-ads-title"><?php echo esc_html( $settings['title'] ); ?></span>
				<?php endif; ?>
				<ul class="link-ads-list">
					<?php foreach ( $links as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : ''; ?>><?php echo esc_html( $link['text'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
	<?php
}

/* -------------------------------------------------------------------------
 * Admin screen
 * ---------------------------------------------------------------------- */

/**
 * Add the page under Appearance.
 */
function techrato_link_ads_menu() {
	add_theme_page(
		__( 'تبلیغات لینکی', 'techrato' ),
		__( 'تبلیغات لینکی', 'techrato' ),
		'manage_options',
		'techrato-link-ads',
		'techrato_link_ads_page'
	);
}
add_action( 'admin_menu', 'techrato_link_ads_menu' );

/**
 * Register the option.
 */
function techrato_link_ads_register() {
	register_setting( 'techrato_link_ads_group', TECHRATO_LINK_ADS_OPTION, array(
		'sanitize_callback' => 'techrato_link_ads_sanitize',
		'default'           => array(),
	) );
}
add_action( 'admin_init', 'techrato_link_ads_register' );

/**
 * Clean whatever the form posts.
 *
 * @param mixed $input Submitted value.
 * @return array
 */
function techrato_link_ads_sanitize( $input ) {
	$input = is_array( $input ) ? $input : array();
	$types = array_keys( techrato_link_ads_rel_choices() );

	$items = array();
	if ( isset( $input['items'] ) && is_array( $input['items'] ) ) {
		foreach ( $input['items'] as $item ) {
			$text = isset( $item['text'] ) ? sanitize_text_field( $item['text'] ) : '';
			$url  = isset( $item['url'] ) ? esc_url_raw( trim( (string) $item['url'] ) ) : '';

			// A row with nothing in it is someone who added a row and changed
			// their mind, not an error worth reporting.
			if ( '' === $text && '' === $url ) {
				continue;
			}

			$rel = isset( $item['rel'] ) && in_array( $item['rel'], $types, true ) ? $item['rel'] : 'nofollow';

			$items[] = array( 'text' => $text, 'url' => $url, 'rel' => $rel );
		}
	}

	return array(
		'enabled' => empty( $input['enabled'] ) ? 0 : 1,
		'title'   => isset( $input['title'] ) ? sanitize_text_field( $input['title'] ) : '',
		'items'   => $items,
	);
}

/**
 * One editable row.
 *
 * @param array $item  Row values.
 * @param int   $index Row index, or -1 for the hidden template row.
 */
function techrato_link_ads_row( $item = array(), $index = 0 ) {
	$name = TECHRATO_LINK_ADS_OPTION . '[items][' . ( $index < 0 ? '__i__' : $index ) . ']';
	$text = isset( $item['text'] ) ? $item['text'] : '';
	$url  = isset( $item['url'] ) ? $item['url'] : '';
	$rel  = isset( $item['rel'] ) ? $item['rel'] : 'nofollow';
	?>
	<tr class="techrato-ad-row"<?php echo $index < 0 ? ' style="display:none;" data-template="1"' : ''; ?>>
		<td><input type="text" class="regular-text" name="<?php echo esc_attr( $name ); ?>[text]" value="<?php echo esc_attr( $text ); ?>" placeholder="<?php esc_attr_e( 'متن لینک', 'techrato' ); ?>"></td>
		<td><input type="url" class="regular-text" dir="ltr" name="<?php echo esc_attr( $name ); ?>[url]" value="<?php echo esc_attr( $url ); ?>" placeholder="https://example.com"></td>
		<td>
			<select name="<?php echo esc_attr( $name ); ?>[rel]">
				<?php foreach ( techrato_link_ads_rel_choices() as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $rel, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td><button type="button" class="button-link techrato-ad-remove" style="color:#b32d2e;"><?php esc_html_e( 'حذف', 'techrato' ); ?></button></td>
	</tr>
	<?php
}

/**
 * The settings screen.
 */
function techrato_link_ads_page() {
	$settings = techrato_link_ads_settings();
	$items    = $settings['items'] ? $settings['items'] : array( array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'تبلیغات لینکی', 'techrato' ); ?></h1>
		<p><?php esc_html_e( 'این لینک‌ها بالای فوتر و در همه‌ی صفحات سایت نمایش داده می‌شوند.', 'techrato' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'techrato_link_ads_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'نمایش بخش', 'techrato' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( TECHRATO_LINK_ADS_OPTION ); ?>[enabled]" value="1" <?php checked( $settings['enabled'], 1 ); ?>>
							<?php esc_html_e( 'این بخش در سایت نشان داده شود', 'techrato' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="techrato-ads-title"><?php esc_html_e( 'عنوان بخش', 'techrato' ); ?></label></th>
					<td>
						<input type="text" id="techrato-ads-title" class="regular-text"
							name="<?php echo esc_attr( TECHRATO_LINK_ADS_OPTION ); ?>[title]"
							value="<?php echo esc_attr( $settings['title'] ); ?>">
						<p class="description"><?php esc_html_e( 'خالی بگذارید تا عنوانی نمایش داده نشود.', 'techrato' ); ?></p>
					</td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'لینک‌ها', 'techrato' ); ?></h2>
			<table class="widefat striped" id="techrato-ads-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'متن', 'techrato' ); ?></th>
						<th><?php esc_html_e( 'آدرس', 'techrato' ); ?></th>
						<th><?php esc_html_e( 'نوع لینک', 'techrato' ); ?></th>
						<th style="width:60px;"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $items as $index => $item ) : ?>
						<?php techrato_link_ads_row( $item, $index ); ?>
					<?php endforeach; ?>
					<?php techrato_link_ads_row( array(), -1 ); ?>
				</tbody>
			</table>

			<p><button type="button" class="button" id="techrato-ads-add"><?php esc_html_e( '+ افزودن لینک', 'techrato' ); ?></button></p>

			<div class="notice notice-info inline" style="padding:10px 14px;">
				<p style="margin:0;"><strong><?php esc_html_e( 'کدام نوع را انتخاب کنم؟', 'techrato' ); ?></strong></p>
				<ul class="ul-disc" style="margin:8px 0 0;">
					<li><?php esc_html_e( 'اگر بابت لینک پول گرفته‌اید: «تبلیغاتی / اسپانسری». گوگل لینک پولی فالو را تخلف می‌داند و می‌تواند سایت را جریمه کند.', 'techrato' ); ?></li>
					<li><?php esc_html_e( 'اگر لینک تبادلی یا نامطمئن است: «نوفالو».', 'techrato' ); ?></li>
					<li><?php esc_html_e( 'فقط برای سایت‌هایی که واقعاً به آن‌ها اعتماد دارید: «فالو».', 'techrato' ); ?></li>
				</ul>
			</div>

			<?php submit_button(); ?>
		</form>
	</div>

	<script>
	( function () {
		var table = document.getElementById( 'techrato-ads-table' );
		var add   = document.getElementById( 'techrato-ads-add' );
		if ( ! table || ! add ) {
			return;
		}

		var body     = table.querySelector( 'tbody' );
		var template = body.querySelector( '[data-template]' );
		var next     = body.querySelectorAll( '.techrato-ad-row:not([data-template])' ).length;

		add.addEventListener( 'click', function () {
			var row = template.cloneNode( true );
			row.removeAttribute( 'style' );
			row.removeAttribute( 'data-template' );
			row.innerHTML = row.innerHTML.replace( /__i__/g, next );
			next++;
			body.insertBefore( row, template );
		} );

		body.addEventListener( 'click', function ( e ) {
			var button = e.target.closest( '.techrato-ad-remove' );
			if ( ! button ) {
				return;
			}
			var row = button.closest( 'tr' );
			// Clearing the last row rather than removing it keeps the table
			// from collapsing to nothing.
			if ( body.querySelectorAll( '.techrato-ad-row:not([data-template])' ).length > 1 ) {
				row.parentNode.removeChild( row );
			} else {
				row.querySelectorAll( 'input' ).forEach( function ( input ) { input.value = ''; } );
			}
		} );
	} )();
	</script>
	<?php
}
