<?php
/**
 * Template Name: درباره دکتر
 * صفحه ۲ — درباره دکتر.
 *
 * @package Aramesh
 */

get_header();

$doctor_name = aramesh_brand_name();
$experience  = aramesh_option( 'doctor_experience', '15' );
$photo       = aramesh_doctor_image( 'about_image' );
$tagline     = aramesh_option( 'doctor_tagline', 'همراه شما در مسیر شناخت خود و حال بهتر' );
$bio         = aramesh_option( 'doctor_bio', 'دکترای روانشناسی عمومی؛ با ارائه هزاران مشاوره فردی و خانوادگی و برگزاری بیش از ۳۰ عنوان کارگاه درمان ماهانه.' );
?>

<!-- ===== Hero ===== -->
<section class="hero pb-0">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="row align-items-center g-4 g-lg-5 mt-1">
			<div class="col-lg-7 text-center text-lg-start">
				<span class="eyebrow"><?php echo esc_html( aramesh_option( 'doctor_title', 'دکترای روانشناسی عمومی' ) ); ?></span>
				<h1 class="mb-2"><?php echo esc_html( $doctor_name ); ?></h1>
				<p class="lead-soft mb-3"><?php echo esc_html( $tagline ); ?></p>
				<p class="text-secondary mb-3"><?php echo esc_html( $bio ); ?></p>
				<div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
					<a class="btn btn-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php echo aramesh_icon( 'arrow-left', 18 ); ?> <?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?></a>
					<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'تماس با من', 'aramesh' ); ?></a>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="about-photo">
					<?php if ( $photo ) : ?>
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $doctor_name ); ?>">
					<?php else : ?>
						<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'leaf', 56 ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ===== Stats ===== -->
<section class="section-sm pb-0">
	<div class="container">
		<div class="row g-3">
			<?php
			$stats = array(
				array( $experience . '+', __( 'سال تجربه', 'aramesh' ) ),
				array( '۳۰+', __( 'کارگاه درمانی', 'aramesh' ) ),
				array( '۱۰۰۰+', __( 'همراه دوره‌ها', 'aramesh' ) ),
				array( '۴.۹', __( 'رضایت شرکت‌کنندگان', 'aramesh' ) ),
			);
			foreach ( $stats as $s ) :
				?>
				<div class="col-6 col-lg-3">
					<div class="dash-stat h-100">
						<div class="dash-stat__num"><?php echo esc_html( $s[0] ); ?></div>
						<div class="dash-stat__label"><?php echo esc_html( $s[1] ); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== Bio / سوابق ===== -->
<?php
$has_content = false;
while ( have_posts() ) :
	the_post();
	if ( trim( wp_strip_all_tags( get_the_content() ) ) ) {
		$has_content = true;
		?>
		<section class="section-sm">
			<div class="container">
				<div class="card-soft p-4 p-md-5 about-content"><?php the_content(); ?></div>
			</div>
		</section>
		<?php
	}
endwhile;

if ( ! $has_content ) :
	?>
	<section class="section-sm">
		<div class="container">
			<div class="card-soft p-4 p-md-5 about-content">
				<h2><?php printf( esc_html__( 'درباره %s', 'aramesh' ), esc_html( $doctor_name ) ); ?></h2>
				<p><?php echo esc_html( $bio ); ?></p>
				<p class="text-secondary"><?php esc_html_e( 'این متن را می‌توانید از ویرایشگر همین صفحه در پیشخوان تغییر دهید.', 'aramesh' ); ?></p>
			</div>
		</div>
	</section>
	<?php
endif;
?>

<!-- ===== حوزه‌های تخصص ===== -->
<section class="section-sm pt-0">
	<div class="container">
		<div class="text-center section-head">
			<span class="eyebrow"><?php esc_html_e( 'حوزه‌های تخصص', 'aramesh' ); ?></span>
			<h2 class="m-0"><?php esc_html_e( 'در چه زمینه‌هایی کنار شما هستم', 'aramesh' ); ?></h2>
		</div>
		<div class="row g-4">
			<?php
			$areas = array(
				array( 'heart', __( 'سلامت روان', 'aramesh' ), __( 'مدیریت استرس، اضطراب و هیجانات دشوار.', 'aramesh' ) ),
				array( 'users', __( 'روابط و خانواده', 'aramesh' ), __( 'بهبود کیفیت روابط و مهارت‌های ارتباطی.', 'aramesh' ) ),
				array( 'sprout', __( 'رشد فردی', 'aramesh' ), __( 'خودشناسی، اعتمادبه‌نفس و انگیزه.', 'aramesh' ) ),
			);
			foreach ( $areas as $a ) :
				?>
				<div class="col-md-4">
					<div class="feature h-100">
						<div class="feature__icon"><?php echo aramesh_icon( $a[0], 22 ); ?></div>
						<div class="feature__title"><?php echo esc_html( $a[1] ); ?></div>
						<p class="feature__text"><?php echo esc_html( $a[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== CTA ===== -->
<section class="section-sm pt-0 pb-5">
	<div class="container">
		<div class="cta-band">
			<div class="row align-items-center g-3">
				<div class="col-lg-8 text-center text-lg-start">
					<h2 class="h4 mb-1"><?php esc_html_e( 'برای شروع، دوره مناسب خود را انتخاب کنید', 'aramesh' ); ?></h2>
					<p class="m-0" style="opacity:.9"><?php esc_html_e( 'مسیر یادگیری خود را از میان دوره‌ها و کارگاه‌ها پیدا کنید.', 'aramesh' ); ?></p>
				</div>
				<div class="col-lg-4 text-center text-lg-start">
					<a class="btn btn-primary btn-lg" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'مشاهده همه دوره‌ها', 'aramesh' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
