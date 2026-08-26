<?php
/**
 * پخش‌کننده ویدیوی محافظت‌شده (abstraction).
 *
 * هدف: جلوگیری از دانلود ساده و کاهش بازنشر — نه ادعای غیرقابل‌ضبط بودن.
 * - URL مستقیم MP4 عمومی افشا نمی‌شود.
 * - playback URL موقت/امضاشده از طریق فیلتر ساخته می‌شود.
 * - watermark پویا با شناسه ماسک‌شده کاربر روی ویدیو نمایش داده می‌شود.
 * - دکمه دانلود ویدیو وجود ندارد.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * ساخت URL امن و موقت پخش برای یک جلسه.
 * پیاده‌سازی واقعی باید سرویس HLS/DASH یا CDN با signed URL را از طریق فیلتر متصل کند.
 *
 * @param int $lesson_id
 * @param int $user_id
 * @return string
 */
function aramesh_get_signed_playback_url( $lesson_id, $user_id = 0 ) {
	$user_id  = $user_id ? (int) $user_id : get_current_user_id();
	$provider = get_post_meta( $lesson_id, '_aramesh_video_provider', true );
	$video_id = get_post_meta( $lesson_id, '_aramesh_video_id', true );

	/**
	 * فیلتر برای ساخت URL امضاشده توسط سرویس واقعی.
	 *
	 * @param string $url      URL نهایی (خالی = هنوز متصل نشده).
	 * @param string $video_id شناسه امن ویدیو.
	 * @param string $provider نوع ارائه‌دهنده.
	 * @param int    $lesson_id
	 * @param int    $user_id
	 */
	$url = apply_filters( 'aramesh_signed_playback_url', '', $video_id, $provider, $lesson_id, $user_id );

	return $url ? esc_url_raw( $url ) : '';
}

/**
 * برچسب واترمارک ماسک‌شده کاربر.
 * مثال: 0912***4567 — کاربر ۱۲
 */
function aramesh_user_watermark( $user_id = 0 ) {
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	if ( ! $user_id ) {
		return '';
	}
	$mobile = get_user_meta( $user_id, 'aramesh_mobile', true );
	if ( $mobile && strlen( $mobile ) === 11 ) {
		$masked = substr( $mobile, 0, 4 ) . '***' . substr( $mobile, -4 );
	} else {
		$user   = get_user_by( 'id', $user_id );
		$masked = $user ? $user->display_name : '';
	}
	/* translators: %s: masked user identifier */
	return trim( $masked . ' · #' . $user_id );
}

/**
 * REST: دریافت URL امضاشده پخش برای یک جلسه (فقط برای مالک دوره).
 */
function aramesh_register_video_route() {
	register_rest_route(
		'aramesh/v1',
		'/playback/(?P<lesson_id>\d+)',
		array(
			'methods'             => 'GET',
			'permission_callback' => function () {
				return is_user_logged_in();
			},
			'callback'            => 'aramesh_rest_playback',
		)
	);
}
add_action( 'rest_api_init', 'aramesh_register_video_route' );

/**
 * هندلر REST پخش.
 */
function aramesh_rest_playback( WP_REST_Request $request ) {
	$lesson_id = (int) $request['lesson_id'];
	$course_id = (int) get_post_meta( $lesson_id, '_aramesh_course_id', true );
	$is_preview = '1' === get_post_meta( $lesson_id, '_aramesh_is_preview', true );

	if ( ! $is_preview && ! aramesh_user_has_course( $course_id ) ) {
		return new WP_REST_Response( array( 'ok' => false, 'message' => 'no_access' ), 403 );
	}

	$url = aramesh_get_signed_playback_url( $lesson_id );

	return new WP_REST_Response(
		array(
			'ok'        => (bool) $url,
			'url'       => $url,
			'watermark' => aramesh_user_watermark(),
			'provider'  => get_post_meta( $lesson_id, '_aramesh_video_provider', true ),
			'message'   => $url ? '' : 'not_configured',
		),
		200
	);
}

/**
 * چاپ markup پخش‌کننده امن با watermark و بدون دکمه دانلود.
 *
 * @param int $lesson_id
 */
function aramesh_render_secure_player( $lesson_id ) {
	$watermark  = aramesh_user_watermark();
	$is_preview = '1' === get_post_meta( $lesson_id, '_aramesh_is_preview', true );
	$rest_url   = esc_url( rest_url( 'aramesh/v1/playback/' . (int) $lesson_id ) );
	?>
	<div class="aramesh-player" data-lesson="<?php echo (int) $lesson_id; ?>" data-endpoint="<?php echo $rest_url; ?>">
		<div class="aramesh-player__frame ratio ratio-16x9">
			<video
				class="aramesh-player__video"
				controls
				controlsList="nodownload noremoteplayback"
				disablepictureinpicture
				oncontextmenu="return false;"
				playsinline
				preload="none">
				<?php esc_html_e( 'مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.', 'aramesh' ); ?>
			</video>
			<?php if ( $watermark ) : ?>
				<div class="aramesh-player__watermark" aria-hidden="true"><?php echo esc_html( $watermark ); ?></div>
			<?php endif; ?>
			<div class="aramesh-player__state" role="status"></div>
		</div>
		<p class="aramesh-player__note small text-secondary mt-2">
			<?php esc_html_e( 'برای حفظ حقوق آموزشی، دسترسی ویدیوها به‌صورت محافظت‌شده و فقط از حساب کاربری ارائه می‌شود و دانلود مستقیم فعال نیست.', 'aramesh' ); ?>
		</p>
	</div>
	<?php
}
