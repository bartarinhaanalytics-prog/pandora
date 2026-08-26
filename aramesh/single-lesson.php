<?php
/**
 * پخش‌کننده جلسه (Page 11).
 *
 * @package Aramesh
 */

get_header();

while ( have_posts() ) :
	the_post();
	$lesson_id  = get_the_ID();
	$course_id  = aramesh_lesson_course_id( $lesson_id );
	$is_preview = '1' === get_post_meta( $lesson_id, '_aramesh_is_preview', true );
	$owned      = aramesh_user_has_course( $course_id );
	$can_watch  = $owned || $is_preview;

	$lessons = aramesh_get_course_lessons( $course_id );
	// prev/next.
	$index = 0;
	foreach ( $lessons as $i => $l ) {
		if ( (int) $l->ID === (int) $lesson_id ) { $index = $i; break; }
	}
	$prev = $index > 0 ? $lessons[ $index - 1 ] : null;
	$next = isset( $lessons[ $index + 1 ] ) ? $lessons[ $index + 1 ] : null;
	$resources = aramesh_parse_resources( get_post_meta( $lesson_id, '_aramesh_resources', true ) );
	$exercises = get_post_meta( $lesson_id, '_aramesh_exercises', true );
	?>

	<section class="section-sm">
		<div class="container">
			<?php aramesh_breadcrumb(); ?>

			<div class="row g-4 mt-1">
				<!-- player + content -->
				<div class="col-lg-8">
					<?php if ( ! $can_watch ) : ?>
						<div class="card-soft p-5 text-center">
							<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'shield', 40 ); ?></div>
							<h1 class="h4"><?php esc_html_e( 'برای تماشای این جلسه باید دوره را تهیه کنید', 'aramesh' ); ?></h1>
							<p class="text-secondary"><?php esc_html_e( 'دسترسی ویدیوها فقط از حساب کاربری و پس از خرید دوره فعال می‌شود.', 'aramesh' ); ?></p>
							<div class="d-flex gap-2 justify-content-center mt-3">
								<?php if ( $course_id ) : ?><a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $course_id ) ); ?>"><?php esc_html_e( 'مشاهده و خرید دوره', 'aramesh' ); ?></a><?php endif; ?>
								<?php if ( ! is_user_logged_in() ) : ?><a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'login' ) ); ?>"><?php esc_html_e( 'ورود به حساب', 'aramesh' ); ?></a><?php endif; ?>
							</div>
						</div>
					<?php else : ?>
						<?php aramesh_render_secure_player( $lesson_id ); ?>

						<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
							<h1 class="h4 m-0"><?php the_title(); ?></h1>
							<div class="d-flex gap-2">
								<?php if ( $prev ) : ?><a class="btn btn-outline-primary btn-sm" href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>"><?php echo aramesh_icon( 'chevron', 16 ); ?> <?php esc_html_e( 'قبلی', 'aramesh' ); ?></a><?php endif; ?>
								<?php if ( $next ) : ?><a class="btn btn-primary btn-sm" href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>"><?php esc_html_e( 'جلسه بعد', 'aramesh' ); ?> <?php echo aramesh_icon( 'arrow-left', 16 ); ?></a><?php endif; ?>
							</div>
						</div>

						<!-- tabs -->
						<ul class="nav nav-tabs mt-4 mb-3" role="tablist">
							<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#l-summary" type="button"><?php esc_html_e( 'خلاصه', 'aramesh' ); ?></button></li>
							<?php if ( $exercises ) : ?><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#l-exercise" type="button"><?php esc_html_e( 'تمرین', 'aramesh' ); ?></button></li><?php endif; ?>
							<?php if ( $resources ) : ?><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#l-resources" type="button"><?php esc_html_e( 'منابع', 'aramesh' ); ?></button></li><?php endif; ?>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="l-summary">
								<div class="article-body"><?php the_content(); ?></div>
							</div>
							<?php if ( $exercises ) : ?>
								<div class="tab-pane fade" id="l-exercise"><div class="card-flat p-4"><?php echo wpautop( esc_html( $exercises ) ); ?></div></div>
							<?php endif; ?>
							<?php if ( $resources ) : ?>
								<div class="tab-pane fade" id="l-resources">
									<ul class="list-unstyled d-grid gap-2">
										<?php foreach ( $resources as $r ) : ?>
											<li class="card-flat p-3 d-flex align-items-center justify-content-between">
												<span><?php echo aramesh_icon( 'book', 18 ); ?> <?php echo esc_html( $r['label'] ); ?></span>
												<?php if ( $r['url'] ) : ?><a class="btn btn-outline-primary btn-sm" href="<?php echo esc_url( $r['url'] ); ?>" download><?php esc_html_e( 'دانلود', 'aramesh' ); ?></a><?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( $next ) : ?>
							<div class="cta-soft mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
								<div><span class="text-secondary small d-block"><?php esc_html_e( 'جلسه بعدی', 'aramesh' ); ?></span><span class="fw-bold"><?php echo esc_html( $next->post_title ); ?></span></div>
								<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>"><?php esc_html_e( 'ادامه', 'aramesh' ); ?> <?php echo aramesh_icon( 'arrow-left', 16 ); ?></a>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<!-- lesson list -->
				<div class="col-lg-4">
					<div class="card-soft p-3">
						<div class="d-flex justify-content-between align-items-center mb-2 px-2">
							<h2 class="h6 m-0"><?php echo esc_html( $course_id ? get_the_title( $course_id ) : __( 'جلسه‌ها', 'aramesh' ) ); ?></h2>
							<?php if ( $course_id ) : ?><span class="text-secondary small"><?php echo (int) aramesh_course_progress_percent( $course_id ); ?>%</span><?php endif; ?>
						</div>
						<ul class="lesson-list">
							<?php foreach ( $lessons as $li => $l ) :
								$l_preview = '1' === get_post_meta( $l->ID, '_aramesh_is_preview', true );
								$l_can     = $owned || $l_preview;
								$prog      = aramesh_get_lesson_progress( $l->ID );
								$active    = (int) $l->ID === (int) $lesson_id;
								$ldur      = get_post_meta( $l->ID, '_aramesh_lesson_duration', true );
								$classes   = 'lesson-list__item';
								if ( $active ) { $classes .= ' is-active'; }
								if ( $prog['completed'] ) { $classes .= ' is-done'; }
								if ( ! $l_can ) { $classes .= ' lesson-locked'; }
								?>
								<li class="<?php echo esc_attr( $classes ); ?>">
									<?php if ( $l_can ) : ?><a href="<?php echo esc_url( get_permalink( $l->ID ) ); ?>"><?php else : ?><a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" aria-disabled="true"><?php endif; ?>
										<span class="lesson-list__num"><?php echo $prog['completed'] ? aramesh_icon( 'check', 14 ) : esc_html( number_format_i18n( $li + 1 ) ); ?></span>
										<span class="flex-grow-1">
											<span class="lesson-list__title d-block"><?php echo esc_html( $l->post_title ); ?></span>
											<span class="lesson-list__meta">
												<?php echo $l_can ? aramesh_icon( 'play', 12 ) : aramesh_icon( 'shield', 12 ); ?>
												<?php echo $ldur ? esc_html( $ldur ) : ''; ?>
												<?php if ( $l_preview ) : ?> · <?php esc_html_e( 'پیش‌نمایش', 'aramesh' ); ?><?php endif; ?>
											</span>
										</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
endwhile;

get_footer();
