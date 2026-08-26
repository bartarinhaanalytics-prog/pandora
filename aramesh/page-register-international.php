<?php
/**
 * Template Name: ثبت‌نام خارج از ایران
 * صفحه ۸ — مسیر کاربران خارج ایران از طریق تلگرام و منشی.
 *
 * @package Aramesh
 */

get_header();

$telegram = aramesh_option( 'telegram' );
$intro    = aramesh_option( 'intl_intro', 'اگر خارج از ایران هستید، ثبت‌نام و پرداخت به‌صورت دستی و با پشتیبانی منشی از طریق تلگرام انجام می‌شود.' );
?>
<section class="section">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center section-head mt-2" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'ثبت‌نام خارج از ایران', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php esc_html_e( 'ثبت‌نام بین‌المللی با پشتیبانی منشی', 'aramesh' ); ?></h1>
			<p class="lead-soft"><?php echo esc_html( $intro ); ?></p>
			<?php if ( $telegram ) : ?>
				<a class="btn btn-primary btn-lg mt-3" href="<?php echo esc_url( $telegram ); ?>" target="_blank" rel="noopener"><?php echo aramesh_icon( 'telegram', 20 ); ?> <?php esc_html_e( 'شروع گفتگو در تلگرام', 'aramesh' ); ?></a>
			<?php else : ?>
				<p class="text-secondary small mt-3"><?php esc_html_e( 'لینک تلگرام را از سفارشی‌سازی » اطلاعات تماس تنظیم کنید.', 'aramesh' ); ?></p>
			<?php endif; ?>
		</div>

		<div class="row g-4 mt-2">
			<?php
			$steps = array(
				array( 'telegram', __( '۱. پیام در تلگرام', 'aramesh' ), __( 'با منشی گفتگو را شروع می‌کنید و دوره موردنظر را اعلام می‌کنید.', 'aramesh' ) ),
				array( 'headset', __( '۲. هماهنگی پرداخت', 'aramesh' ), __( 'منشی روش پرداخت مناسب کشور شما را هماهنگ می‌کند.', 'aramesh' ) ),
				array( 'users', __( '۳. ساخت حساب', 'aramesh' ), __( 'حساب کاربری برای شما ساخته و دوره فعال می‌شود.', 'aramesh' ) ),
				array( 'check', __( '۴. دریافت دسترسی', 'aramesh' ), __( 'اطلاعات ورود را می‌گیرید و به «دوره‌های من» می‌روید.', 'aramesh' ) ),
			);
			foreach ( $steps as $s ) :
				?>
				<div class="col-md-6 col-lg-3">
					<div class="step h-100">
						<div class="feature__icon mb-3"><?php echo aramesh_icon( $s[0], 22 ); ?></div>
						<div class="feature__title mb-1"><?php echo esc_html( $s[1] ); ?></div>
						<p class="feature__text"><?php echo esc_html( $s[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="row g-4 mt-3">
			<div class="col-lg-6">
				<div class="card-soft p-4 h-100">
					<h2 class="h5 mb-3"><?php esc_html_e( 'چرا این مسیر دستی است؟', 'aramesh' ); ?></h2>
					<ul class="check-list">
						<li><?php esc_html_e( 'نیازی به شماره موبایل ایران نیست.', 'aramesh' ); ?></li>
						<li><?php esc_html_e( 'پرداخت ریالی داخلی الزامی نیست.', 'aramesh' ); ?></li>
						<li><?php esc_html_e( 'پشتیبانی انسانی در تمام مراحل.', 'aramesh' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card-soft p-4 h-100">
					<h2 class="h5 mb-3"><?php esc_html_e( 'سوالات پرتکرار', 'aramesh' ); ?></h2>
					<?php
					$faqs = array(
						array( 'q' => __( 'چقدر طول می‌کشد؟', 'aramesh' ), 'a' => __( 'معمولاً در همان روز کاری هماهنگ می‌شود.', 'aramesh' ) ),
						array( 'q' => __( 'بعد از پرداخت چطور دسترسی می‌گیرم؟', 'aramesh' ), 'a' => __( 'حساب شما ساخته و دوره فعال می‌شود و اطلاعات ورود برایتان ارسال می‌گردد.', 'aramesh' ) ),
					);
					echo '<div class="accordion" id="intlFaq">';
					foreach ( $faqs as $i => $faq ) :
						?>
						<div class="accordion-item">
							<h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#if-<?php echo (int) $i; ?>"><?php echo esc_html( $faq['q'] ); ?></button></h3>
							<div id="if-<?php echo (int) $i; ?>" class="accordion-collapse collapse" data-bs-parent="#intlFaq"><div class="accordion-body text-secondary"><?php echo esc_html( $faq['a'] ); ?></div></div>
						</div>
					<?php endforeach;
					echo '</div>';
					aramesh_faq_schema( $faqs );
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
