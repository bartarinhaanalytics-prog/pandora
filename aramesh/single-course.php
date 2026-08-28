<?php
/**
 * صفحه جزئیات دوره (Page 4).
 *
 * @package Aramesh
 */

get_header();

while ( have_posts() ) :
	the_post();
	$course_id = get_the_ID();
	$price     = aramesh_course_price( $course_id );
	$teacher   = get_post_meta( $course_id, '_aramesh_teacher', true );
	$duration  = get_post_meta( $course_id, '_aramesh_duration', true );
	$level     = get_post_meta( $course_id, '_aramesh_level', true );
	$trailer   = get_post_meta( $course_id, '_aramesh_trailer', true );
	$short     = get_post_meta( $course_id, '_aramesh_short_desc', true );
	$outcomes  = aramesh_lines_to_array( get_post_meta( $course_id, '_aramesh_outcomes', true ) );
	$suitable  = aramesh_lines_to_array( get_post_meta( $course_id, '_aramesh_suitable', true ) );
	$prereq    = aramesh_lines_to_array( get_post_meta( $course_id, '_aramesh_prerequisites', true ) );
	$faqs      = aramesh_parse_faq( get_post_meta( $course_id, '_aramesh_faq', true ) );
	$lessons   = aramesh_get_course_lessons( $course_id );
	$lesson_ct = get_post_meta( $course_id, '_aramesh_lesson_count', true );
	$lesson_ct = $lesson_ct ? $lesson_ct : count( $lessons );
	$owned     = aramesh_user_has_course( $course_id );
	$bestseller = '1' === get_post_meta( $course_id, '_aramesh_bestseller', true );
	?>

	<section class="hero pb-0">
		<div class="container">
			<?php aramesh_breadcrumb(); ?>
			<div class="row g-5 align-items-start mt-1">

				<!-- media -->
				<div class="col-lg-6 order-lg-2">
					<?php if ( $trailer ) : ?>
						<?php aramesh_render_inline_protected_video( $trailer, __( 'پیش‌نمایش دوره', 'aramesh' ) ); ?>
					<?php else : ?>
						<div class="trailer ratio ratio-16x9">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'aramesh-cover', array( 'style' => 'object-fit:cover;width:100%;height:100%' ) ); ?>
							<?php else : ?>
								<span class="ph-media w-100 h-100"><?php echo aramesh_icon( 'sprout', 56 ); ?></span>
							<?php endif; ?>
							<?php if ( $bestseller ) : ?><span class="badge-soft badge-best position-absolute" style="top:12px;inset-inline-start:12px"><?php esc_html_e( 'پرفروش‌ترین دوره', 'aramesh' ); ?></span><?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<!-- summary + price -->
				<div class="col-lg-6 order-lg-1">
					<span class="eyebrow"><?php esc_html_e( 'کارگاه تخصصی', 'aramesh' ); ?></span>
					<h1 class="mb-3"><?php the_title(); ?></h1>
					<?php if ( $short ) : ?><p class="lead-soft"><?php echo esc_html( $short ); ?></p><?php endif; ?>

					<div class="meta-row my-3">
						<?php $workshop_date = get_post_meta( $course_id, '_aramesh_workshop_date', true ); ?>
						<?php if ( $workshop_date ) : ?><span><?php echo aramesh_icon( 'calendar', 18 ); ?> <?php echo esc_html( $workshop_date ); ?></span><?php endif; ?>
						<?php if ( $lesson_ct ) : ?><span><?php echo aramesh_icon( 'video', 18 ); ?> <?php echo esc_html( $lesson_ct ); ?> <?php esc_html_e( 'جلسه', 'aramesh' ); ?></span><?php endif; ?>
						<?php if ( $level ) : ?><span><?php echo aramesh_icon( 'layers', 18 ); ?> <?php echo esc_html( $level ); ?></span><?php endif; ?>
						<?php if ( $duration ) : ?><span><?php echo aramesh_icon( 'clock', 18 ); ?> <?php echo esc_html( $duration ); ?></span><?php endif; ?>
						<span><?php echo aramesh_icon( 'infinity', 18 ); ?> <?php esc_html_e( 'دسترسی دائمی', 'aramesh' ); ?></span>
					</div>

					<div class="course-hero__price mt-3">
						<?php if ( $owned ) : ?>
							<p class="text-primary-dark fw-bold mb-2"><?php echo aramesh_icon( 'check', 18 ); ?> <?php esc_html_e( 'شما به این دوره دسترسی دارید.', 'aramesh' ); ?></p>
							<a class="btn btn-primary w-100 btn-lg" href="<?php echo esc_url( aramesh_page_url( 'my_courses' ) ); ?>"><?php esc_html_e( 'ورود به دوره', 'aramesh' ); ?></a>
						<?php elseif ( ! empty( $price['on_request'] ) ) : ?>
							<?php $telegram = aramesh_option( 'telegram' ); ?>
							<p class="text-secondary mb-3"><?php esc_html_e( 'برای ثبت‌نام و اطلاع از شهریه، در تلگرام پیام دهید. پس از هماهنگی و واریز وجه، به کانال صوتی کارگاه اضافه می‌شوید.', 'aramesh' ); ?></p>
							<a class="btn btn-primary w-100 btn-lg mb-2" href="<?php echo esc_url( $telegram ? $telegram : aramesh_page_url( 'register_path' ) ); ?>" <?php echo $telegram ? 'target="_blank" rel="noopener"' : ''; ?>>
								<?php echo aramesh_icon( 'telegram', 20 ); ?> <?php esc_html_e( 'ثبت‌نام و هماهنگی در تلگرام', 'aramesh' ); ?>
							</a>
							<a class="btn btn-outline-primary w-100" href="<?php echo esc_url( aramesh_page_url( 'register_path' ) ); ?>"><?php echo aramesh_icon( 'heart', 18 ); ?> <?php esc_html_e( 'راهنمای ثبت‌نام (داخل/خارج ایران)', 'aramesh' ); ?></a>
						<?php else : ?>
							<div class="d-flex align-items-baseline gap-2 mb-3">
								<span class="amount"><?php echo esc_html( aramesh_format_toman( $price['effective'] ) ); ?></span>
								<span class="text-secondary"><?php esc_html_e( 'تومان', 'aramesh' ); ?></span>
								<?php if ( $price['has_sale'] ) : ?><del class="text-secondary ms-2"><?php echo esc_html( aramesh_format_toman( $price['price'] ) ); ?></del><?php endif; ?>
							</div>
							<button class="btn btn-primary w-100 btn-lg mb-2" data-buy-course="<?php echo (int) $course_id; ?>" data-login-url="<?php echo esc_url( add_query_arg( 'redirect_to', get_permalink(), aramesh_page_url( 'register_iran' ) ) ); ?>">
								<?php echo aramesh_icon( 'check', 20 ); ?> <?php esc_html_e( 'ثبت‌نام و خرید (داخل ایران)', 'aramesh' ); ?>
							</button>
							<?php $telegram = aramesh_option( 'telegram' ); ?>
							<a class="btn btn-outline-primary w-100" href="<?php echo esc_url( $telegram ? $telegram : aramesh_page_url( 'register_intl' ) ); ?>" <?php echo $telegram ? 'target="_blank" rel="noopener"' : ''; ?>>
								<?php echo aramesh_icon( 'telegram', 18 ); ?> <?php esc_html_e( 'خارج از کشور هستید؟ ثبت‌نام در تلگرام', 'aramesh' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- benefits strip -->
	<section class="section-sm">
		<div class="container">
			<div class="card-flat p-4">
				<div class="row g-4 text-center text-md-start">
					<?php
					$benefits = array(
						array( 'infinity', __( 'دسترسی همیشگی', 'aramesh' ), __( 'به محتوای دوره', 'aramesh' ) ),
						array( 'video', __( 'تماشای آنلاین', 'aramesh' ), __( 'در هر زمان و مکان', 'aramesh' ) ),
						array( 'headset', __( 'پشتیبانی و همراهی', 'aramesh' ), __( 'در طول دوره', 'aramesh' ) ),
						array( 'shield', __( 'محتوای کاربردی', 'aramesh' ), __( 'و قابل اجرا', 'aramesh' ) ),
					);
					foreach ( $benefits as $b ) :
						?>
						<div class="col-6 col-md-3">
							<div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
								<span class="feature__icon m-0"><?php echo aramesh_icon( $b[0], 22 ); ?></span>
								<span><span class="fw-bold d-block"><?php echo esc_html( $b[1] ); ?></span><span class="text-secondary small"><?php echo esc_html( $b[2] ); ?></span></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- main content: tabs + sidebar -->
	<section class="section-sm pt-0">
		<div class="container">
			<div class="row g-5">
				<div class="col-lg-8 order-2 order-lg-1">
					<ul class="nav nav-tabs mb-4" role="tablist">
						<li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-about" type="button" role="tab"><?php esc_html_e( 'درباره دوره', 'aramesh' ); ?></button></li>
						<li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-curriculum" type="button" role="tab"><?php esc_html_e( 'سرفصل‌ها', 'aramesh' ); ?></button></li>
						<?php if ( $faqs ) : ?><li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-faq" type="button" role="tab"><?php esc_html_e( 'سوالات متداول', 'aramesh' ); ?></button></li><?php endif; ?>
					</ul>

					<div class="tab-content">
						<!-- about -->
						<div class="tab-pane fade show active" id="tab-about" role="tabpanel">
							<div class="article-body">
								<h2><?php esc_html_e( 'درباره این دوره', 'aramesh' ); ?></h2>
								<?php the_content(); ?>
							</div>

							<?php if ( $outcomes ) : ?>
								<h3 class="mt-4 mb-3"><?php esc_html_e( 'آنچه در این دوره یاد می‌گیرید', 'aramesh' ); ?></h3>
								<ul class="check-list">
									<?php foreach ( $outcomes as $o ) : ?><li><?php echo esc_html( $o ); ?></li><?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( $prereq ) : ?>
								<h3 class="mt-4 mb-3"><?php esc_html_e( 'پیش‌نیازها', 'aramesh' ); ?></h3>
								<ul class="check-list">
									<?php foreach ( $prereq as $pr ) : ?><li><?php echo esc_html( $pr ); ?></li><?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>

						<!-- curriculum -->
						<div class="tab-pane fade" id="tab-curriculum" role="tabpanel">
							<h2 class="mb-3"><?php esc_html_e( 'سرفصل‌های دوره', 'aramesh' ); ?></h2>
							<?php if ( $lessons ) : ?>
								<?php
								$chapters = array();
								foreach ( $lessons as $lesson ) {
									$chapter = get_post_meta( $lesson->ID, '_aramesh_chapter', true );
									$chapter = $chapter ? $chapter : __( 'محتوای دوره', 'aramesh' );
									$chapters[ $chapter ][] = $lesson;
								}
								$ci = 0;
								echo '<div class="accordion" id="curriculum">';
								foreach ( $chapters as $chapter => $items ) :
									$ci++;
									?>
									<div class="accordion-item">
										<h3 class="accordion-header">
											<button class="accordion-button <?php echo $ci > 1 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#ch-<?php echo (int) $ci; ?>" aria-expanded="<?php echo $ci === 1 ? 'true' : 'false'; ?>">
												<?php echo esc_html( $chapter ); ?> <span class="text-secondary small mx-2">(<?php echo count( $items ); ?> <?php esc_html_e( 'جلسه', 'aramesh' ); ?>)</span>
											</button>
										</h3>
										<div id="ch-<?php echo (int) $ci; ?>" class="accordion-collapse collapse <?php echo $ci === 1 ? 'show' : ''; ?>" data-bs-parent="#curriculum">
											<div class="accordion-body p-0">
												<ul class="list-unstyled m-0">
													<?php foreach ( $items as $li => $lesson ) :
														$is_preview = '1' === get_post_meta( $lesson->ID, '_aramesh_is_preview', true );
														$ldur       = get_post_meta( $lesson->ID, '_aramesh_lesson_duration', true );
														$can_open   = $owned || $is_preview;
														?>
														<li class="d-flex align-items-center justify-content-between gap-2 p-3 <?php echo $li ? 'border-top' : ''; ?>" style="border-color:var(--border)">
															<span class="d-flex align-items-center gap-2">
																<?php echo aramesh_icon( $can_open ? 'play' : 'shield', 18 ); ?>
																<span><?php echo esc_html( $lesson->post_title ); ?></span>
																<?php if ( $is_preview ) : ?><span class="badge-soft badge-accent"><?php esc_html_e( 'پیش‌نمایش', 'aramesh' ); ?></span><?php endif; ?>
															</span>
															<span class="d-flex align-items-center gap-3">
																<?php if ( $ldur ) : ?><span class="text-secondary small"><?php echo esc_html( $ldur ); ?></span><?php endif; ?>
																<?php if ( $can_open ) : ?><a class="text-primary-dark" href="<?php echo esc_url( get_permalink( $lesson->ID ) ); ?>"><?php echo aramesh_icon( 'arrow-left', 16 ); ?></a><?php endif; ?>
															</span>
														</li>
													<?php endforeach; ?>
												</ul>
											</div>
										</div>
									</div>
								<?php endforeach;
								echo '</div>';
								?>
							<?php else : ?>
								<p class="text-secondary"><?php esc_html_e( 'سرفصل‌ها به‌زودی اضافه می‌شوند.', 'aramesh' ); ?></p>
							<?php endif; ?>
						</div>

						<!-- faq -->
						<?php if ( $faqs ) : ?>
							<div class="tab-pane fade" id="tab-faq" role="tabpanel">
								<h2 class="mb-3"><?php esc_html_e( 'سوالات متداول', 'aramesh' ); ?></h2>
								<div class="accordion" id="courseFaq">
									<?php foreach ( $faqs as $i => $faq ) : ?>
										<div class="accordion-item">
											<h3 class="accordion-header">
												<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cf-<?php echo (int) $i; ?>" aria-expanded="false"><?php echo esc_html( $faq['q'] ); ?></button>
											</h3>
											<div id="cf-<?php echo (int) $i; ?>" class="accordion-collapse collapse" data-bs-parent="#courseFaq">
												<div class="accordion-body text-secondary"><?php echo esc_html( $faq['a'] ); ?></div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
								<?php aramesh_faq_schema( $faqs ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<!-- sidebar -->
				<div class="col-lg-4 order-1 order-lg-2">
					<div class="card-soft p-4 mb-4">
						<h3 class="h6 text-secondary mb-3"><?php esc_html_e( 'مدرس دوره', 'aramesh' ); ?></h3>
						<div class="instructor">
							<span class="ph-media" style="width:64px;height:64px;border-radius:16px;display:grid;place-items:center"><?php echo aramesh_icon( 'users', 24 ); ?></span>
							<div>
								<div class="fw-bold"><?php echo esc_html( $teacher ? $teacher : aramesh_brand_name() ); ?></div>
								<div class="text-secondary small"><?php echo esc_html( aramesh_option( 'doctor_title', 'روانشناس و درمانگر' ) ); ?></div>
							</div>
						</div>
						<a class="btn btn-outline-primary w-100 mt-3" href="<?php echo esc_url( aramesh_page_url( 'about' ) ); ?>"><?php esc_html_e( 'درباره مدرس', 'aramesh' ); ?></a>
					</div>

					<?php if ( $suitable ) : ?>
						<div class="card-soft p-4">
							<h3 class="h6 mb-3"><?php esc_html_e( 'این دوره برای چه کسانی مناسب است؟', 'aramesh' ); ?></h3>
							<ul class="check-list">
								<?php foreach ( $suitable as $s ) : ?><li><?php echo esc_html( $s ); ?></li><?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php if ( ! $owned ) : ?>
		<!-- sticky mobile CTA -->
		<div class="sticky-cta d-lg-none">
			<div class="d-flex align-items-center justify-content-between gap-2">
				<?php if ( ! empty( $price['on_request'] ) ) : ?>
					<?php $sticky_tg = aramesh_option( 'telegram' ); ?>
					<div><span class="fw-bold"><?php esc_html_e( 'ثبت‌نام کارگاه', 'aramesh' ); ?></span></div>
					<a class="btn btn-primary flex-grow-1" href="<?php echo esc_url( $sticky_tg ? $sticky_tg : aramesh_page_url( 'register_path' ) ); ?>" <?php echo $sticky_tg ? 'target="_blank" rel="noopener"' : ''; ?>><?php esc_html_e( 'ثبت‌نام در تلگرام', 'aramesh' ); ?></a>
				<?php else : ?>
					<div><span class="fw-bold"><?php echo esc_html( aramesh_format_toman( $price['effective'] ) ); ?></span> <span class="text-secondary small"><?php esc_html_e( 'تومان', 'aramesh' ); ?></span></div>
					<button class="btn btn-primary flex-grow-1" data-buy-course="<?php echo (int) $course_id; ?>" data-login-url="<?php echo esc_url( aramesh_page_url( 'register_path' ) ); ?>"><?php esc_html_e( 'ثبت‌نام و خرید', 'aramesh' ); ?></button>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
endwhile;

get_footer();
