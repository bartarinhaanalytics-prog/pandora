<?php
/**
 * Template Name: تماس با ما
 * صفحه ۱۵ — تماس.
 *
 * @package Aramesh
 */

get_header();

$phone    = aramesh_option( 'phone' );
$email    = aramesh_option( 'email' );
$address  = aramesh_option( 'address' );
$hours    = aramesh_option( 'hours', 'شنبه تا پنج‌شنبه، ۹ تا ۱۷' );
$telegram = aramesh_option( 'telegram' );
$map      = aramesh_option( 'map_embed' );
?>
<section class="hero pb-3">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center mt-2" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'تماس با ما', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php esc_html_e( 'در ارتباط باشیم', 'aramesh' ); ?></h1>
			<p class="lead-soft"><?php esc_html_e( 'سوال یا درخواستی دارید؟ از راه‌های زیر با ما در تماس باشید.', 'aramesh' ); ?></p>
		</div>
	</div>
</section>

<section class="pb-3">
	<div class="container">
		<div class="row g-3">
			<?php
			$cards = array();
			if ( $phone )    { $cards[] = array( 'phone', __( 'تلفن', 'aramesh' ), $phone, 'tel:' . preg_replace( '/\s+/', '', $phone ) ); }
			if ( $email )    { $cards[] = array( 'mail', __( 'ایمیل', 'aramesh' ), $email, 'mailto:' . antispambot( $email ) ); }
			if ( $telegram ) { $cards[] = array( 'telegram', __( 'تلگرام', 'aramesh' ), __( 'گفتگو با پشتیبانی', 'aramesh' ), $telegram ); }
			if ( $address )  { $cards[] = array( 'pin', __( 'آدرس', 'aramesh' ), $address, '' ); }
			if ( empty( $cards ) ) {
				$cards[] = array( 'headset', __( 'اطلاعات تماس', 'aramesh' ), __( 'از سفارشی‌سازی » اطلاعات تماس تنظیم کنید.', 'aramesh' ), '' );
			}
			foreach ( $cards as $c ) :
				?>
				<div class="col-6 col-lg-3">
					<div class="card-soft p-4 h-100 text-center">
						<div class="feature__icon mx-auto"><?php echo aramesh_icon( $c[0], 22 ); ?></div>
						<div class="fw-bold mb-1"><?php echo esc_html( $c[1] ); ?></div>
						<?php if ( $c[3] ) : ?>
							<a class="text-secondary" href="<?php echo esc_url( $c[3] ); ?>" <?php echo ( 0 === strpos( $c[3], 'http' ) ) ? 'target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $c[2] ); ?></a>
						<?php else : ?>
							<div class="text-secondary small"><?php echo esc_html( $c[2] ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section-sm pt-3">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-7">
				<div class="card-soft p-4 p-md-5">
					<h2 class="h4 mb-3"><?php esc_html_e( 'فرم تماس', 'aramesh' ); ?></h2>
					<form data-contact-form novalidate>
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label" for="c-name"><?php esc_html_e( 'نام', 'aramesh' ); ?></label>
								<input type="text" id="c-name" name="name" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="c-phone"><?php esc_html_e( 'تلفن (اختیاری)', 'aramesh' ); ?></label>
								<input type="tel" id="c-phone" name="phone" class="form-control" inputmode="numeric">
							</div>
							<div class="col-12">
								<label class="form-label" for="c-email"><?php esc_html_e( 'ایمیل', 'aramesh' ); ?></label>
								<input type="email" id="c-email" name="email" class="form-control">
							</div>
							<div class="col-12">
								<label class="form-label" for="c-message"><?php esc_html_e( 'پیام شما', 'aramesh' ); ?></label>
								<textarea id="c-message" name="message" class="form-control" rows="5" required></textarea>
							</div>
							<div class="col-12">
								<button type="submit" class="btn btn-primary"><?php echo aramesh_icon( 'send', 18 ); ?> <?php esc_html_e( 'ارسال پیام', 'aramesh' ); ?></button>
							</div>
						</div>
						<div class="form-message mt-3" data-contact-message role="status"></div>
					</form>
				</div>
			</div>

			<div class="col-lg-5">
				<div class="card-soft p-4 mb-4">
					<h2 class="h6 mb-2"><?php echo aramesh_icon( 'clock', 18 ); ?> <?php esc_html_e( 'ساعات پاسخگویی', 'aramesh' ); ?></h2>
					<p class="text-secondary m-0"><?php echo esc_html( $hours ); ?></p>
				</div>
				<?php if ( $map ) : ?>
					<div class="card-soft overflow-hidden"><?php echo wp_kses( $map, array( 'iframe' => array( 'src' => true, 'width' => true, 'height' => true, 'style' => true, 'loading' => true, 'allowfullscreen' => true, 'referrerpolicy' => true ) ) ); ?></div>
				<?php else : ?>
					<div class="cta-soft">
						<h2 class="h6 mb-2"><?php esc_html_e( 'ترجیح می‌دهید پیام بدهید؟', 'aramesh' ); ?></h2>
						<?php if ( $telegram ) : ?>
							<a class="btn btn-primary w-100" href="<?php echo esc_url( $telegram ); ?>" target="_blank" rel="noopener"><?php echo aramesh_icon( 'telegram', 18 ); ?> <?php esc_html_e( 'گفتگو در تلگرام', 'aramesh' ); ?></a>
						<?php else : ?>
							<p class="text-secondary small m-0"><?php esc_html_e( 'لینک تلگرام را در تنظیمات قالب وارد کنید.', 'aramesh' ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
