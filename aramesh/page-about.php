<?php
/**
 * Template Name: درباره دکتر
 * صفحه ۲ — درباره دکتر.
 *
 * @package Aramesh
 */

get_header();

$doctor_name = aramesh_brand_name();
$experience  = aramesh_option( 'doctor_experience', '10' );
?>

<section class="hero pb-0">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="row align-items-center g-5 mt-1">
			<div class="col-lg-7 order-lg-2 text-center text-lg-end">
				<span class="eyebrow"><?php echo esc_html( aramesh_option( 'doctor_title', 'روانشناس و درمانگر' ) ); ?></span>
				<h1 class="mb-3"><?php echo esc_html( $doctor_name ); ?></h1>
				<p class="lead-soft"><?php echo esc_html( aramesh_option( 'doctor_tagline', 'همراه شما در مسیر شناخت خود' ) ); ?></p>
				<div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-end mt-3">
					<a class="btn btn-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php echo aramesh_icon( 'arrow-left', 18 ); ?> <?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?></a>
					<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'تماس با من', 'aramesh' ); ?></a>
				</div>
			</div>
			<div class="col-lg-5 order-lg-1">
				<div class="hero__media mx-auto" style="max-width:420px">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'aramesh-wide', array( 'class' => 'w-100 h-100', 'style' => 'object-fit:cover' ) ); ?>
					<?php else : ?>
						<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'leaf', 56 ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- بیوگرافی + فلسفه -->
<section class="section-sm">
	<div class="container" style="max-width:900px">
		<div class="article-body mx-auto">
			<?php
			while ( have_posts() ) :
				the_post();
				if ( get_the_content() ) {
					the_content();
				} else {
					echo '<h2>' . esc_html__( 'درباره من', 'aramesh' ) . '</h2>';
					echo '<p>' . esc_html( aramesh_option( 'doctor_bio', 'من ' . $doctor_name . ' هستم؛ روان‌شناس و درمانگر با تمرکز بر سلامت روان، رشد فردی و بهبود روابط. این محتوا را می‌توانید از ویرایشگر همین صفحه تغییر دهید.' ) ) . '</p>';
				}
			endwhile;
			?>
		</div>
	</div>
</section>

<!-- آمار/سابقه -->
<section class="section-sm pt-0">
	<div class="container">
		<div class="row g-3">
			<?php
			$stats = array(
				array( $experience . '+', __( 'سال تجربه', 'aramesh' ) ),
				array( '۳', __( 'حوزه تخصصی', 'aramesh' ) ),
				array( '۱۰۰۰+', __( 'همراه دوره‌ها', 'aramesh' ) ),
				array( '۴.۹', __( 'رضایت شرکت‌کنندگان', 'aramesh' ) ),
			);
			foreach ( $stats as $s ) :
				?>
				<div class="col-6 col-lg-3">
					<div class="dash-stat">
						<div class="dash-stat__num"><?php echo esc_html( $s[0] ); ?></div>
						<div class="dash-stat__label"><?php echo esc_html( $s[1] ); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- حوزه‌های تخصص -->
<section class="section-sm">
	<div class="container">
		<div class="text-center section-head">
			<span class="eyebrow"><?php esc_html_e( 'حوزه‌های تخصص', 'aramesh' ); ?></span>
			<h2 class="m-0"><?php esc_html_e( 'در چه زمینه‌هایی کنار شما هستم', 'aramesh' ); ?></h2>
		</div>
		<div class="row g-4">
			<?php
			$areas = array(
				array( 'heart', __( 'سلامت روان', 'aramesh' ), __( 'مدیریت استرس، اضطراب و هیجانات دشوار.', 'aramesh' ) ),
				array( 'users', __( 'روابط عاطفی', 'aramesh' ), __( 'بهبود کیفیت روابط و مهارت‌های ارتباطی.', 'aramesh' ) ),
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

<!-- CTA -->
<section class="section-sm pb-5">
	<div class="container">
		<div class="cta-soft text-center">
			<h2 class="mb-2"><?php esc_html_e( 'برای شروع، دوره مناسب خود را انتخاب کنید', 'aramesh' ); ?></h2>
			<a class="btn btn-primary btn-lg mt-2" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'مشاهده همه دوره‌ها', 'aramesh' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
