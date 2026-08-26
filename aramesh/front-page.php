<?php
/**
 * صفحه اصلی (Page 1).
 *
 * @package Aramesh
 */

get_header();

$doctor_name = aramesh_brand_name();
$experience  = aramesh_option( 'doctor_experience', '10' );
$telegram    = aramesh_option( 'telegram' );
?>

<!-- ============ HERO ============ -->
<section class="hero">
	<div class="container">
		<div class="row align-items-center g-5">
			<div class="col-lg-6 order-lg-2 text-center text-lg-end">
				<span class="eyebrow"><?php esc_html_e( 'دوره‌های تخصصی روان‌شناسی', 'aramesh' ); ?></span>
				<h1 class="hero__title">
					<?php esc_html_e( 'برای حال بهتر،', 'aramesh' ); ?><br>
					<span class="accent"><?php esc_html_e( 'از شناخت عمیق‌تر شروع کن.', 'aramesh' ); ?></span>
				</h1>
				<p class="lead-soft mb-4">
					<?php esc_html_e( 'کارگاه‌های علمی و کاربردی برای شناخت خود، بهبود حال و ساختن زندگی آگاهانه‌تر.', 'aramesh' ); ?>
				</p>
				<div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-end">
					<a class="btn btn-primary btn-lg" href="<?php echo esc_url( aramesh_courses_url() ); ?>">
						<?php echo aramesh_icon( 'arrow-left', 20 ); ?>
						<?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?>
					</a>
					<a class="btn btn-outline-primary btn-lg" href="<?php echo esc_url( aramesh_page_url( 'about' ) ); ?>">
						<?php esc_html_e( 'آشنایی با من', 'aramesh' ); ?>
						<?php echo aramesh_icon( 'arrow-down', 18 ); ?>
					</a>
				</div>
				<div class="hero__pills justify-content-center justify-content-lg-end">
					<span class="hero__pill"><?php esc_html_e( 'دسترسی همیشگی', 'aramesh' ); ?></span>
					<span class="hero__pill"><?php esc_html_e( 'آموزش آنلاین', 'aramesh' ); ?></span>
					<span class="hero__pill"><?php esc_html_e( 'محتوای تخصصی و کاربردی', 'aramesh' ); ?></span>
				</div>
			</div>
			<div class="col-lg-6 order-lg-1">
				<div class="hero__media mx-auto" style="max-width:460px">
					<?php
					$hero_img = aramesh_doctor_image( 'hero_image' );
					if ( $hero_img ) :
						?>
						<img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php echo esc_attr( $doctor_name ); ?>">
					<?php else : ?>
						<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'leaf', 64 ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============ TRUST STRIP ============ -->
<section class="pb-5">
	<div class="container">
		<div class="trust">
			<div class="row g-0">
				<?php
				$trust_items = array(
					array( 'award', __( 'محتوای علمی و معتبر', 'aramesh' ), __( 'بر پایه دانش روز روان‌شناسی', 'aramesh' ) ),
					array( 'infinity', __( 'دسترسی آسان', 'aramesh' ), __( 'در هر زمان و مکان', 'aramesh' ) ),
					array( 'users', __( 'هزاران همراه', 'aramesh' ), __( 'در مسیر رشد و تغییر', 'aramesh' ) ),
					array( 'award', sprintf( __( '+%s سال تجربه', 'aramesh' ), $experience ), __( 'درمان، مشاوره و آموزش', 'aramesh' ) ),
				);
				foreach ( $trust_items as $t ) :
					?>
					<div class="col-6 col-lg-3">
						<div class="trust__item">
							<div class="trust__icon"><?php echo aramesh_icon( $t[0], 26 ); ?></div>
							<div class="trust__title"><?php echo esc_html( $t[1] ); ?></div>
							<div class="trust__text"><?php echo esc_html( $t[2] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ============ ABOUT ============ -->
<section class="section-sm">
	<div class="container">
		<div class="row align-items-center g-5">
			<div class="col-lg-6">
				<span class="eyebrow"><?php esc_html_e( 'درباره من', 'aramesh' ); ?></span>
				<h2 class="mb-3"><?php esc_html_e( 'شناخت، اولین قدم تغییر است.', 'aramesh' ); ?></h2>
				<p class="lead-soft"><?php echo esc_html( aramesh_option( 'doctor_bio', 'من ' . $doctor_name . ' هستم، روان‌شناس و درمانگر. با استفاده از رویکردهای علمی و تجربی، تلاش می‌کنم آموزش‌هایی ارائه دهم که به شما در مسیر آگاهی، بهبود روابط و ابزارهای کاربردی برای زندگی رضایت‌بخش‌تر کمک کند.' ) ); ?></p>
				<div class="row g-3 my-3">
					<?php
					$about_points = array(
						array( 'heart', __( 'سلامت روان', 'aramesh' ), __( 'مدیریت استرس، اضطراب و هیجانات', 'aramesh' ) ),
						array( 'sprout', __( 'رشد فردی', 'aramesh' ), __( 'خودشناسی و افزایش اعتمادبه‌نفس', 'aramesh' ) ),
						array( 'users', __( 'روابط و ارتباطات', 'aramesh' ), __( 'بهبود کیفیت و حل تعارض‌ها', 'aramesh' ) ),
					);
					foreach ( $about_points as $p ) :
						?>
						<div class="col-md-4">
							<div class="feature h-100">
								<div class="feature__icon"><?php echo aramesh_icon( $p[0], 22 ); ?></div>
								<div class="feature__title"><?php echo esc_html( $p[1] ); ?></div>
								<p class="feature__text"><?php echo esc_html( $p[2] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'about' ) ); ?>">
					<?php esc_html_e( 'بیشتر درباره من', 'aramesh' ); ?>
					<?php echo aramesh_icon( 'arrow-left', 18 ); ?>
				</a>
			</div>
			<div class="col-lg-6">
				<div class="hero__media mx-auto" style="max-width:460px;aspect-ratio:1/1;border-radius:var(--radius-card)">
					<?php
					$about_img = aramesh_doctor_image( 'about_image' );
					if ( $about_img ) : ?>
						<img src="<?php echo esc_url( $about_img ); ?>" alt="<?php echo esc_attr( $doctor_name ); ?>">
					<?php else : ?>
						<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'leaf', 56 ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============ FEATURED COURSES ============ -->
<?php
$featured_q = new WP_Query(
	array(
		'post_type'      => 'course',
		'posts_per_page' => 3,
		'meta_key'       => '_aramesh_featured',
		'meta_value'     => '1',
	)
);
if ( ! $featured_q->have_posts() ) {
	wp_reset_postdata();
	$featured_q = new WP_Query( array( 'post_type' => 'course', 'posts_per_page' => 3 ) );
}
if ( $featured_q->have_posts() ) :
	?>
	<section class="section-sm">
		<div class="container">
			<div class="d-flex flex-wrap justify-content-between align-items-end section-head gap-2">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'دوره‌های منتخب', 'aramesh' ); ?></span>
					<h2 class="m-0"><?php esc_html_e( 'دوره‌هایی برای یک تغییر واقعی', 'aramesh' ); ?></h2>
				</div>
				<a class="btn btn-ghost" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'مشاهده همه دوره‌ها', 'aramesh' ); ?> <?php echo aramesh_icon( 'arrow-left', 18 ); ?></a>
			</div>
			<div class="row g-4">
				<?php
				while ( $featured_q->have_posts() ) :
					$featured_q->the_post();
					echo '<div class="col-md-6 col-lg-4">';
					aramesh_render_course_card( get_the_ID() );
					echo '</div>';
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ TOPICS ============ -->
<?php
$topics = get_terms( array( 'taxonomy' => 'topic', 'hide_empty' => false, 'number' => 6 ) );
if ( ! is_wp_error( $topics ) && ! empty( $topics ) ) :
	$topic_icons = array( 'brain', 'sprout', 'heart', 'users', 'shield', 'leaf' );
	?>
	<section class="section-sm">
		<div class="container text-center">
			<span class="eyebrow"><?php esc_html_e( 'موضوعات', 'aramesh' ); ?></span>
			<h2 class="section-head"><?php esc_html_e( 'این روزها بیشتر با کدام موضوع درگیری؟', 'aramesh' ); ?></h2>
			<div class="row g-3 justify-content-center">
				<?php foreach ( $topics as $i => $topic ) : ?>
					<div class="col-6 col-md-4 col-lg-2">
						<a class="feature d-block text-center h-100 hover-lift" href="<?php echo esc_url( get_term_link( $topic ) ); ?>">
							<div class="feature__icon mx-auto"><?php echo aramesh_icon( $topic_icons[ $i % count( $topic_icons ) ], 22 ); ?></div>
							<div class="feature__title" style="font-size:.98rem"><?php echo esc_html( $topic->name ); ?></div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ WHY ============ -->
<section class="section-sm">
	<div class="container">
		<div class="text-center section-head">
			<span class="eyebrow"><?php esc_html_e( 'چرا این دوره‌ها؟', 'aramesh' ); ?></span>
			<h2 class="m-0"><?php esc_html_e( 'آموزشی که به کار زندگی می‌آید', 'aramesh' ); ?></h2>
		</div>
		<div class="row g-4">
			<?php
			$why = array(
				array( 'shield', __( 'علمی و قابل اعتماد', 'aramesh' ), __( 'محتوا بر پایه معتبرترین یافته‌های روان‌شناسی تهیه شده است.', 'aramesh' ) ),
				array( 'sprout', __( 'کاربردی و عملی', 'aramesh' ), __( 'آموزش‌ها را با تمرین در زندگی خود به کار بگیرید.', 'aramesh' ) ),
				array( 'clock', __( 'دسترسی همیشگی', 'aramesh' ), __( 'هر زمان که نیاز دارید به محتوا دسترسی خواهید داشت.', 'aramesh' ) ),
			);
			foreach ( $why as $w ) :
				?>
				<div class="col-md-4">
					<div class="feature h-100">
						<div class="feature__icon"><?php echo aramesh_icon( $w[0], 22 ); ?></div>
						<div class="feature__title"><?php echo esc_html( $w[1] ); ?></div>
						<p class="feature__text"><?php echo esc_html( $w[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="section-sm">
	<div class="container">
		<div class="text-center section-head">
			<span class="eyebrow"><?php esc_html_e( 'مسیر ساده', 'aramesh' ); ?></span>
			<h2 class="m-0"><?php esc_html_e( 'چگونه شروع کنیم؟', 'aramesh' ); ?></h2>
		</div>
		<div class="row g-4">
			<?php
			$steps = array(
				array( '۰۱', __( 'دوره مناسب را انتخاب کن', 'aramesh' ), __( 'بر اساس نیاز و هدف خود، دوره را انتخاب کنید.', 'aramesh' ) ),
				array( '۰۲', __( 'ثبت‌نام و تهیه دوره', 'aramesh' ), __( 'ثبت‌نام کنید و به محتوای دوره دسترسی پیدا کنید.', 'aramesh' ) ),
				array( '۰۳', __( 'یادگیری را شروع کن', 'aramesh' ), __( 'با پخش‌کننده اختصاصی، مسیر یادگیری خود را آغاز کنید.', 'aramesh' ) ),
			);
			foreach ( $steps as $s ) :
				?>
				<div class="col-md-4">
					<div class="step h-100">
						<div class="step__num mb-3"><?php echo esc_html( $s[0] ); ?></div>
						<div class="feature__title mb-1"><?php echo esc_html( $s[1] ); ?></div>
						<p class="feature__text"><?php echo esc_html( $s[2] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ INTERNATIONAL CTA ============ -->
<section class="section-sm">
	<div class="container">
		<div class="cta-band">
			<div class="row align-items-center g-4">
				<div class="col-lg-8">
					<h2 class="h3 mb-2"><?php esc_html_e( 'خارج از ایران هستید؟', 'aramesh' ); ?></h2>
					<p class="m-0" style="opacity:.92"><?php esc_html_e( 'برای ثبت‌نام در دوره‌ها و دریافت راهنمایی، با پشتیبانی ما در تلگرام در ارتباط باشید.', 'aramesh' ); ?></p>
				</div>
				<div class="col-lg-4 text-lg-start">
					<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $telegram ? $telegram : aramesh_page_url( 'register_intl' ) ); ?>" <?php echo $telegram ? 'target="_blank" rel="noopener"' : ''; ?>>
						<?php echo aramesh_icon( 'telegram', 20 ); ?>
						<?php esc_html_e( 'ارتباط در تلگرام', 'aramesh' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<?php
$tq = new WP_Query( array( 'post_type' => 'testimonial', 'posts_per_page' => 3 ) );
if ( $tq->have_posts() ) :
	?>
	<section class="section-sm">
		<div class="container">
			<div class="text-center section-head">
				<span class="eyebrow"><?php esc_html_e( 'تجربه همراهان', 'aramesh' ); ?></span>
				<h2 class="m-0"><?php esc_html_e( 'تجربه همراهان دوره‌ها', 'aramesh' ); ?></h2>
			</div>
			<div class="row g-4">
				<?php
				while ( $tq->have_posts() ) :
					$tq->the_post();
					$role   = get_post_meta( get_the_ID(), '_aramesh_person_role', true );
					$rating = get_post_meta( get_the_ID(), '_aramesh_rating', true );
					?>
					<div class="col-md-4">
						<div class="testimonial">
							<div class="text-primary-dark"><?php echo aramesh_icon( 'quote', 28 ); ?></div>
							<p class="testimonial__quote m-0"><?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?></p>
							<?php echo aramesh_stars( $rating ? $rating : 5 ); ?>
							<div class="testimonial__person">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php echo get_the_post_thumbnail( get_the_ID(), 'aramesh-avatar', array( 'class' => 'testimonial__avatar', 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<span class="testimonial__avatar ph-media"><?php echo aramesh_icon( 'users', 20 ); ?></span>
								<?php endif; ?>
								<div>
									<div class="fw-bold"><?php the_title(); ?></div>
									<?php if ( $role ) : ?><div class="text-secondary small"><?php echo esc_html( $role ); ?></div><?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ LATEST ARTICLES ============ -->
<?php
$aq = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3 ) );
if ( $aq->have_posts() ) :
	?>
	<section class="section-sm">
		<div class="container">
			<div class="d-flex flex-wrap justify-content-between align-items-end section-head gap-2">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'مجله', 'aramesh' ); ?></span>
					<h2 class="m-0"><?php esc_html_e( 'تازه‌ترین مقالات', 'aramesh' ); ?></h2>
				</div>
				<a class="btn btn-ghost" href="<?php echo esc_url( aramesh_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'مشاهده همه مقالات', 'aramesh' ); ?> <?php echo aramesh_icon( 'arrow-left', 18 ); ?></a>
			</div>
			<div class="row g-4">
				<?php
				while ( $aq->have_posts() ) :
					$aq->the_post();
					echo '<div class="col-md-6 col-lg-4">';
					aramesh_render_article_card( get_the_ID() );
					echo '</div>';
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- ============ LEAD CAPTURE ============ -->
<section class="section-sm">
	<div class="container">
		<div class="lead-capture">
			<div class="row align-items-center g-4">
				<div class="col-lg-6">
					<h2 class="h3 mb-2"><?php esc_html_e( 'هر هفته چند دقیقه برای خودت', 'aramesh' ); ?></h2>
					<p class="text-secondary m-0"><?php esc_html_e( 'مطالب و آموزش‌های جدید روان‌شناسی را دریافت کنید.', 'aramesh' ); ?></p>
				</div>
				<div class="col-lg-6">
					<?php echo do_shortcode( '[aramesh_lead]' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============ FAQ ============ -->
<?php
$home_faqs = apply_filters(
	'aramesh_home_faqs',
	array(
		array( 'q' => __( 'بعد از خرید چطور به دوره دسترسی پیدا می‌کنم؟', 'aramesh' ), 'a' => __( 'بلافاصله پس از پرداخت، دوره در بخش «دوره‌های من» فعال می‌شود.', 'aramesh' ) ),
		array( 'q' => __( 'تا چه مدت به دوره دسترسی دارم؟', 'aramesh' ), 'a' => __( 'دسترسی به محتوای دوره دائمی است.', 'aramesh' ) ),
		array( 'q' => __( 'آیا امکان دانلود ویدیوها وجود دارد؟', 'aramesh' ), 'a' => __( 'برای حفظ حقوق آموزشی، ویدیوها فقط به‌صورت محافظت‌شده و از حساب کاربری قابل مشاهده‌اند.', 'aramesh' ) ),
		array( 'q' => __( 'اگر خارج از ایران باشم چطور ثبت‌نام کنم؟', 'aramesh' ), 'a' => __( 'از طریق تلگرام با منشی هماهنگ می‌کنید و حساب و دوره برای شما فعال می‌شود.', 'aramesh' ) ),
	)
);
if ( ! empty( $home_faqs ) ) :
	?>
	<section class="section-sm">
		<div class="container" style="max-width:820px">
			<div class="text-center section-head">
				<span class="eyebrow"><?php esc_html_e( 'سوالات متداول', 'aramesh' ); ?></span>
				<h2 class="m-0"><?php esc_html_e( 'سوالات متداول', 'aramesh' ); ?></h2>
			</div>
			<div class="accordion" id="homeFaq">
				<?php foreach ( $home_faqs as $i => $faq ) : ?>
					<div class="accordion-item">
						<h3 class="accordion-header" id="hf-h-<?php echo (int) $i; ?>">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hf-c-<?php echo (int) $i; ?>" aria-expanded="false" aria-controls="hf-c-<?php echo (int) $i; ?>">
								<?php echo esc_html( $faq['q'] ); ?>
							</button>
						</h3>
						<div id="hf-c-<?php echo (int) $i; ?>" class="accordion-collapse collapse" data-bs-parent="#homeFaq">
							<div class="accordion-body text-secondary"><?php echo esc_html( $faq['a'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php aramesh_faq_schema( $home_faqs ); ?>
<?php endif; ?>

<!-- ============ FINAL CTA ============ -->
<section class="section-sm pb-5">
	<div class="container">
		<div class="cta-soft text-center">
			<h2 class="mb-2"><?php esc_html_e( 'همین امروز مسیر آرامش را شروع کنید', 'aramesh' ); ?></h2>
			<p class="text-secondary mb-4"><?php esc_html_e( 'یک قدم کوچک به سمت زندگی آگاهانه‌تر بردارید.', 'aramesh' ); ?></p>
			<a class="btn btn-primary btn-lg" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php echo aramesh_icon( 'arrow-left', 20 ); ?> <?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?></a>
		</div>
	</div>
</section>

<?php
get_footer();
