<?php
/**
 * Template Name: سوالات متداول
 * صفحه ۱۶ — FAQ.
 *
 * @package Aramesh
 */

get_header();

/**
 * گروه‌های FAQ قابل فیلتر. هر آیتم: q, a.
 */
$faq_groups = apply_filters(
	'aramesh_faq_groups',
	array(
		__( 'خرید و دسترسی', 'aramesh' ) => array(
			array( 'q' => __( 'بعد از خرید چطور به دوره دسترسی پیدا می‌کنم؟', 'aramesh' ), 'a' => __( 'بلافاصله پس از پرداخت، دوره در بخش «دوره‌های من» فعال می‌شود.', 'aramesh' ) ),
			array( 'q' => __( 'تا چه مدت به دوره دسترسی دارم؟', 'aramesh' ), 'a' => __( 'دسترسی به محتوای دوره دائمی است.', 'aramesh' ) ),
			array( 'q' => __( 'اگر خارج از ایران باشم چطور ثبت‌نام کنم؟', 'aramesh' ), 'a' => __( 'از طریق تلگرام با منشی هماهنگ می‌کنید و حساب و دوره برای شما فعال می‌شود.', 'aramesh' ) ),
		),
		__( 'محتوا و ویدیو', 'aramesh' ) => array(
			array( 'q' => __( 'آیا امکان دانلود ویدیوها وجود دارد؟', 'aramesh' ), 'a' => __( 'برای حفظ حقوق آموزشی، ویدیوها فقط به‌صورت محافظت‌شده و از حساب کاربری قابل مشاهده‌اند و دانلود مستقیم فعال نیست.', 'aramesh' ) ),
			array( 'q' => __( 'آیا جزوه یا تمرین قابل دانلود است؟', 'aramesh' ), 'a' => __( 'در صورت وجود، فایل‌های PDF و تمرین‌ها در هر جلسه قابل دانلود هستند.', 'aramesh' ) ),
		),
		__( 'پشتیبانی', 'aramesh' ) => array(
			array( 'q' => __( 'اگر به پشتیبانی نیاز داشتم چه کنم؟', 'aramesh' ), 'a' => __( 'از صفحه تماس یا تلگرام پشتیبانی پیام دهید.', 'aramesh' ) ),
		),
	)
);

$all_faqs = array();
foreach ( $faq_groups as $items ) {
	$all_faqs = array_merge( $all_faqs, $items );
}
?>
<section class="hero pb-3">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center mt-2" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'راهنما', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php esc_html_e( 'سوالات متداول', 'aramesh' ); ?></h1>
			<p class="lead-soft"><?php esc_html_e( 'پاسخ پرسش‌های رایج درباره دوره‌ها، خرید و پشتیبانی.', 'aramesh' ); ?></p>
			<div class="mt-3" style="max-width:520px;margin-inline:auto">
				<div class="aramesh-search d-flex gap-2">
					<input type="search" class="form-control" placeholder="<?php esc_attr_e( 'جستجو در سوالات…', 'aramesh' ); ?>" data-faq-search>
					<span class="btn btn-primary flex-shrink-0"><?php echo aramesh_icon( 'search', 20 ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-sm pt-2">
	<div class="container" style="max-width:860px">
		<?php $gi = 0; foreach ( $faq_groups as $group => $items ) : $gi++; ?>
			<h2 class="h5 mt-4 mb-3"><?php echo esc_html( $group ); ?></h2>
			<div class="accordion" id="faq-<?php echo (int) $gi; ?>">
				<?php foreach ( $items as $ii => $faq ) : $uid = $gi . '-' . $ii; ?>
					<div class="accordion-item" data-faq-item>
						<h3 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-c-<?php echo esc_attr( $uid ); ?>" aria-expanded="false"><?php echo esc_html( $faq['q'] ); ?></button>
						</h3>
						<div id="faq-c-<?php echo esc_attr( $uid ); ?>" class="accordion-collapse collapse" data-bs-parent="#faq-<?php echo (int) $gi; ?>">
							<div class="accordion-body text-secondary"><?php echo esc_html( $faq['a'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>

		<div class="cta-soft text-center mt-5">
			<h2 class="h5 mb-2"><?php esc_html_e( 'پاسخ سوال خود را پیدا نکردید؟', 'aramesh' ); ?></h2>
			<a class="btn btn-primary mt-2" href="<?php echo esc_url( aramesh_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'تماس با پشتیبانی', 'aramesh' ); ?></a>
		</div>
	</div>
</section>

<?php aramesh_faq_schema( $all_faqs ); ?>
<?php
get_footer();
