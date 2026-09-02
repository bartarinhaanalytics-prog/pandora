<?php
/**
 * Plugin Name: تبدیل خودکار تصاویر به WebP
 * Description: هر عکسی که از این پس آپلود شود، پیش از ساخته‌شدن اندازه‌های مختلف به WebP تبدیل می‌شود. کیفیت به‌صورت پیش‌فرض ۸۵ درصد است و از تنظیمات قابل تغییر است.
 * Version: 1.1.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Techrato
 * Text Domain: techrato-webp
 *
 * The conversion happens on wp_handle_upload — before WordPress generates the
 * thumbnail sizes — so every generated size is WebP too, rather than a WebP
 * original surrounded by JPEG thumbnails.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const TECHRATO_WEBP_OPTIONS = 'techrato_webp_options';

/**
 * Settings, with defaults.
 *
 * @return array
 */
function techrato_webp_settings() {
	$defaults = array(
		'enabled'         => 1,
		'quality'         => 85,
		'keep_original'   => 0,
		// Off by default: a JPEG that a camera or another tool already
		// compressed often grows when re-encoded to WebP, and refusing those
		// would leave most photos on the site as JPEG.
		'only_if_smaller' => 0,
	);

	$saved = get_option( TECHRATO_WEBP_OPTIONS, array() );
	$saved = is_array( $saved ) ? $saved : array();

	$settings = array_merge( $defaults, $saved );

	$settings['enabled']         = ! empty( $settings['enabled'] ) ? 1 : 0;
	$settings['keep_original']   = ! empty( $settings['keep_original'] ) ? 1 : 0;
	$settings['only_if_smaller'] = ! empty( $settings['only_if_smaller'] ) ? 1 : 0;
	$settings['quality']         = min( 100, max( 1, (int) $settings['quality'] ) );

	return $settings;
}

/**
 * Whether this server can write WebP at all.
 *
 * @return bool
 */
function techrato_webp_supported() {
	if ( function_exists( 'imagewebp' ) ) {
		return true;
	}

	if ( class_exists( 'Imagick' ) ) {
		$formats = Imagick::queryFormats( 'WEBP' );
		return ! empty( $formats );
	}

	return false;
}

/**
 * Image types worth converting.
 *
 * GIF is left alone: an animated GIF would lose its animation, and the
 * libraries available here cannot write animated WebP.
 *
 * @return array
 */
function techrato_webp_source_types() {
	return apply_filters( 'techrato_webp_source_types', array( 'image/jpeg', 'image/png' ) );
}

/**
 * Convert an upload to WebP before WordPress builds its thumbnails.
 *
 * @param array $upload array( file, url, type ) from wp_handle_upload.
 * @return array
 */
function techrato_webp_convert_upload( $upload ) {
	$settings = techrato_webp_settings();

	if ( ! $settings['enabled'] || empty( $upload['file'] ) || empty( $upload['type'] ) ) {
		return $upload;
	}

	if ( ! in_array( $upload['type'], techrato_webp_source_types(), true ) ) {
		return $upload;
	}

	if ( ! techrato_webp_supported() ) {
		return $upload;
	}

	$source = $upload['file'];
	$editor = wp_get_image_editor( $source );
	if ( is_wp_error( $editor ) ) {
		return $upload;
	}

	$editor->set_quality( $settings['quality'] );

	// Save beside the original with a .webp extension, letting WordPress pick
	// a free filename so nothing is overwritten.
	$target = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $source );
	if ( $target === $source ) {
		$target = $source . '.webp';
	}
	$target = wp_unique_filename( dirname( $target ), basename( $target ) );
	$target = trailingslashit( dirname( $source ) ) . $target;

	$saved = $editor->save( $target, 'image/webp' );

	if ( is_wp_error( $saved ) || empty( $saved['path'] ) || ! file_exists( $saved['path'] ) ) {
		return $upload;
	}

	// Only when the site owner asked for it. Left on unconditionally this
	// rejects most JPEG uploads: a photo saved at quality 70-80 is already
	// compressed, so a WebP of it at quality 85 comes out larger even though
	// the picture is fine. That is why JPEGs appeared not to convert at all.
	if ( $settings['only_if_smaller'] && filesize( $saved['path'] ) >= filesize( $source ) ) {
		wp_delete_file( $saved['path'] );
		return $upload;
	}

	if ( ! $settings['keep_original'] ) {
		wp_delete_file( $source );
	}

	$upload['file'] = $saved['path'];
	$upload['url']  = str_replace( basename( $source ), basename( $saved['path'] ), $upload['url'] );
	$upload['type'] = 'image/webp';

	return $upload;
}
add_filter( 'wp_handle_upload', 'techrato_webp_convert_upload' );

/**
 * Use the configured quality for every WebP WordPress writes, including the
 * thumbnail sizes it generates after the upload.
 *
 * @param int    $quality Default quality.
 * @param string $mime    Mime type being written.
 * @return int
 */
function techrato_webp_quality( $quality, $mime = '' ) {
	if ( 'image/webp' === $mime ) {
		$settings = techrato_webp_settings();
		return $settings['quality'];
	}

	return $quality;
}
add_filter( 'wp_editor_set_quality', 'techrato_webp_quality', 10, 2 );

/* -------------------------------------------------------------------------
 * Settings screen
 * ---------------------------------------------------------------------- */

/**
 * Add the settings page under Settings.
 */
function techrato_webp_menu() {
	add_options_page(
		__( 'تبدیل به WebP', 'techrato-webp' ),
		__( 'تبدیل به WebP', 'techrato-webp' ),
		'manage_options',
		'techrato-webp',
		'techrato_webp_settings_page'
	);
}
add_action( 'admin_menu', 'techrato_webp_menu' );

/**
 * Register the option and its sanitiser.
 */
function techrato_webp_register_settings() {
	register_setting( 'techrato_webp', TECHRATO_WEBP_OPTIONS, array(
		'sanitize_callback' => 'techrato_webp_sanitize',
		'default'           => array(),
	) );
}
add_action( 'admin_init', 'techrato_webp_register_settings' );

/**
 * Keep whatever is stored inside sane bounds.
 *
 * @param mixed $input Submitted values.
 * @return array
 */
function techrato_webp_sanitize( $input ) {
	$input = is_array( $input ) ? $input : array();

	return array(
		'enabled'         => empty( $input['enabled'] ) ? 0 : 1,
		'keep_original'   => empty( $input['keep_original'] ) ? 0 : 1,
		'only_if_smaller' => empty( $input['only_if_smaller'] ) ? 0 : 1,
		'quality'         => min( 100, max( 1, (int) ( $input['quality'] ?? 85 ) ) ),
	);
}

/**
 * The settings page itself.
 */
function techrato_webp_settings_page() {
	$settings  = techrato_webp_settings();
	$supported = techrato_webp_supported();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'تبدیل خودکار تصاویر به WebP', 'techrato-webp' ); ?></h1>

		<?php if ( ! $supported ) : ?>
			<div class="notice notice-error">
				<p><strong><?php esc_html_e( 'سرور شما توانایی ساخت WebP را ندارد.', 'techrato-webp' ); ?></strong></p>
				<p><?php esc_html_e( 'به پشتیبانی هاست بگویید پشتیبانی WebP را برای GD یا Imagick در نسخه‌ی PHP سایت فعال کند. تا آن زمان این افزونه کاری انجام نمی‌دهد و عکس‌ها مثل قبل آپلود می‌شوند.', 'techrato-webp' ); ?></p>
			</div>
		<?php else : ?>
			<div class="notice notice-success">
				<p><?php esc_html_e( 'سرور شما WebP را پشتیبانی می‌کند.', 'techrato-webp' ); ?>
				<?php
				$engine = function_exists( 'imagewebp' ) ? 'GD' : 'Imagick';
				/* translators: %s: image library name */
				printf( esc_html__( 'موتور فعال: %s', 'techrato-webp' ), esc_html( $engine ) );
				?></p>
			</div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'techrato_webp' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'تبدیل خودکار', 'techrato-webp' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( TECHRATO_WEBP_OPTIONS ); ?>[enabled]" value="1" <?php checked( $settings['enabled'], 1 ); ?>>
							<?php esc_html_e( 'عکس‌های جدید هنگام آپلود به WebP تبدیل شوند', 'techrato-webp' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="techrato-webp-quality"><?php esc_html_e( 'کیفیت', 'techrato-webp' ); ?></label></th>
					<td>
						<input type="number" min="1" max="100" step="1" id="techrato-webp-quality"
							name="<?php echo esc_attr( TECHRATO_WEBP_OPTIONS ); ?>[quality]"
							value="<?php echo esc_attr( $settings['quality'] ); ?>" class="small-text"> %
						<p class="description"><?php esc_html_e( 'پیشنهاد: ۸۵. پایین‌تر از ۷۰ افت کیفیت دیده می‌شود و بالاتر از ۹۰ حجم را بی‌دلیل زیاد می‌کند.', 'techrato-webp' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'نگه داشتن فایل اصلی', 'techrato-webp' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( TECHRATO_WEBP_OPTIONS ); ?>[keep_original]" value="1" <?php checked( $settings['keep_original'], 1 ); ?>>
							<?php esc_html_e( 'فایل JPEG/PNG اصلی روی سرور بماند', 'techrato-webp' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'پیشنهاد: خاموش. روشن بودنش فضای هاست را دو برابر می‌کند و سایت هم از آن استفاده نمی‌کند.', 'techrato-webp' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'فقط وقتی حجم کمتر شد', 'techrato-webp' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( TECHRATO_WEBP_OPTIONS ); ?>[only_if_smaller]" value="1" <?php checked( $settings['only_if_smaller'], 1 ); ?>>
							<?php esc_html_e( 'اگر فایل WebP از فایل اصلی بزرگ‌تر شد، تبدیل انجام نشود', 'techrato-webp' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'پیشنهاد: خاموش. اگر روشنش کنید بیشتر عکس‌های JPG تبدیل نمی‌شوند، چون عکسی که قبلاً فشرده شده معمولاً در WebP کمی بزرگ‌تر درمی‌آید.', 'techrato-webp' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<h2><?php esc_html_e( 'نکته‌ها', 'techrato-webp' ); ?></h2>
		<ul class="ul-disc">
			<li><?php esc_html_e( 'فقط روی عکس‌هایی اثر دارد که از این به بعد آپلود می‌شوند. عکس‌های قبلی سایت دست‌نخورده می‌مانند.', 'techrato-webp' ); ?></li>
			<li><?php esc_html_e( 'فایل‌های GIF تبدیل نمی‌شوند تا متحرک‌بودنشان از بین نرود.', 'techrato-webp' ); ?></li>
			<li><?php esc_html_e( 'هر سه نوع JPG و JPEG و PNG تبدیل می‌شوند.', 'techrato-webp' ); ?></li>
			<li><?php esc_html_e( 'بعد از تغییر تنظیمات، کش سایت را خالی کنید.', 'techrato-webp' ); ?></li>
		</ul>
	</div>
	<?php
}

/**
 * Point at the settings from the plugins list.
 *
 * @param array $links Existing action links.
 * @return array
 */
function techrato_webp_action_links( $links ) {
	$url = admin_url( 'options-general.php?page=techrato-webp' );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'تنظیمات', 'techrato-webp' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'techrato_webp_action_links' );

/**
 * Warn once, on the plugins and media screens, if the server cannot do it.
 */
function techrato_webp_admin_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->id, array( 'plugins', 'upload' ), true ) ) {
		return;
	}

	if ( techrato_webp_supported() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p>';
	esc_html_e( 'افزونه‌ی تبدیل به WebP فعال است، ولی سرور توانایی ساخت WebP را ندارد. عکس‌ها فعلاً بدون تبدیل آپلود می‌شوند.', 'techrato-webp' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'techrato_webp_admin_notice' );
