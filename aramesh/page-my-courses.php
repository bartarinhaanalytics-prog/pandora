<?php
/**
 * Template Name: دوره‌های من
 * صفحه ۱۰ — دوره‌های من.
 *
 * @package Aramesh
 */

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( aramesh_page_url( 'my_courses' ) ), aramesh_page_url( 'login' ) ) );
	exit;
}

get_header();

$course_ids = aramesh_get_user_course_ids();
?>
<section class="section">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-3">
				<?php set_query_var( 'aramesh_account_active', 'my_courses' ); get_template_part( 'template-parts/account-nav' ); ?>
			</div>

			<div class="col-lg-9">
				<div class="mb-4">
					<span class="eyebrow"><?php esc_html_e( 'یادگیری', 'aramesh' ); ?></span>
					<h1 class="h3 m-0"><?php esc_html_e( 'دوره‌های من', 'aramesh' ); ?></h1>
				</div>

				<?php if ( empty( $course_ids ) ) : ?>
					<div class="card-soft p-5 text-center">
						<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'video', 36 ); ?></div>
						<h3 class="h5"><?php esc_html_e( 'هنوز دوره‌ای تهیه نکرده‌اید', 'aramesh' ); ?></h3>
						<a class="btn btn-primary mt-2" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?></a>
					</div>
				<?php else : ?>

					<?php
					// Continue learning (اولین دوره ناتمام).
					$continue_course = 0;
					foreach ( $course_ids as $cid ) {
						if ( aramesh_course_progress_percent( $cid ) < 100 ) { $continue_course = $cid; break; }
					}
					if ( $continue_course ) :
						$continue_lesson = aramesh_continue_lesson_id( $continue_course );
						?>
						<div class="cta-band mb-4">
							<div class="row align-items-center g-3">
								<div class="col-lg-8">
									<span class="badge-soft mb-2" style="background:rgba(255,255,255,.2);color:#fff"><?php esc_html_e( 'ادامه یادگیری', 'aramesh' ); ?></span>
									<h2 class="h4 m-0"><?php echo esc_html( get_the_title( $continue_course ) ); ?></h2>
								</div>
								<div class="col-lg-4 text-lg-start">
									<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $continue_lesson ) ); ?>"><?php echo aramesh_icon( 'play', 18 ); ?> <?php esc_html_e( 'ادامه بده', 'aramesh' ); ?></a>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div class="row g-4">
						<?php foreach ( $course_ids as $cid ) :
							$percent  = aramesh_course_progress_percent( $cid );
							$lessons  = aramesh_get_course_lessons( $cid );
							$continue = aramesh_continue_lesson_id( $cid );
							?>
							<div class="col-md-6">
								<article class="a-card h-100">
									<a class="a-card__media" href="<?php echo esc_url( $continue ? get_permalink( $continue ) : get_permalink( $cid ) ); ?>">
										<?php echo has_post_thumbnail( $cid ) ? get_the_post_thumbnail( $cid, 'aramesh-card', array( 'loading' => 'lazy' ) ) : '<span class="ph-media w-100 h-100">' . aramesh_icon( 'video', 40 ) . '</span>'; ?>
									</a>
									<div class="a-card__body">
										<h3 class="a-card__title"><a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"><?php echo esc_html( get_the_title( $cid ) ); ?></a></h3>
										<div class="progress my-1"><div class="progress-bar" style="width:<?php echo (int) $percent; ?>%"></div></div>
										<div class="text-secondary small"><?php printf( esc_html__( '%1$d%% · %2$d جلسه', 'aramesh' ), (int) $percent, count( $lessons ) ); ?></div>
									</div>
									<div class="a-card__foot">
										<a class="btn btn-primary btn-sm w-100" href="<?php echo esc_url( $continue ? get_permalink( $continue ) : get_permalink( $cid ) ); ?>">
											<?php echo (int) $percent > 0 ? esc_html__( 'ادامه یادگیری', 'aramesh' ) : esc_html__( 'شروع دوره', 'aramesh' ); ?>
										</a>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
