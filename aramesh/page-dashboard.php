<?php
/**
 * Template Name: داشبورد کاربر
 * صفحه ۹ — داشبورد.
 *
 * @package Aramesh
 */

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( aramesh_page_url( 'account' ) ), aramesh_page_url( 'login' ) ) );
	exit;
}

get_header();

$user        = wp_get_current_user();
$course_ids  = aramesh_get_user_course_ids();
$watch_min   = aramesh_total_watch_minutes();
$completed   = aramesh_completed_courses_count();
$display     = $user->display_name ? $user->display_name : $user->user_login;
?>
<section class="section">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-3">
				<?php set_query_var( 'aramesh_account_active', 'dashboard' ); get_template_part( 'template-parts/account-nav' ); ?>
			</div>

			<div class="col-lg-9">
				<div class="mb-4">
					<span class="eyebrow"><?php esc_html_e( 'داشبورد', 'aramesh' ); ?></span>
					<h1 class="h3 m-0"><?php printf( esc_html__( 'خوش آمدید، %s 👋', 'aramesh' ), esc_html( $display ) ); ?></h1>
				</div>

				<!-- stats -->
				<div class="row g-3 mb-4">
					<div class="col-6 col-lg-3"><div class="dash-stat"><div class="dash-stat__num"><?php echo esc_html( number_format_i18n( count( $course_ids ) ) ); ?></div><div class="dash-stat__label"><?php esc_html_e( 'دوره فعال', 'aramesh' ); ?></div></div></div>
					<div class="col-6 col-lg-3"><div class="dash-stat"><div class="dash-stat__num"><?php echo esc_html( number_format_i18n( $completed ) ); ?></div><div class="dash-stat__label"><?php esc_html_e( 'دوره تکمیل‌شده', 'aramesh' ); ?></div></div></div>
					<div class="col-6 col-lg-3"><div class="dash-stat"><div class="dash-stat__num"><?php echo esc_html( number_format_i18n( $watch_min ) ); ?></div><div class="dash-stat__label"><?php esc_html_e( 'دقیقه یادگیری', 'aramesh' ); ?></div></div></div>
					<div class="col-6 col-lg-3"><div class="dash-stat"><div class="dash-stat__num"><?php echo esc_html( number_format_i18n( count( $course_ids ) - $completed ) ); ?></div><div class="dash-stat__label"><?php esc_html_e( 'در حال یادگیری', 'aramesh' ); ?></div></div></div>
				</div>

				<!-- continue / active courses -->
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h2 class="h5 m-0"><?php esc_html_e( 'دوره‌های فعال', 'aramesh' ); ?></h2>
					<a class="btn btn-ghost btn-sm" href="<?php echo esc_url( aramesh_page_url( 'my_courses' ) ); ?>"><?php esc_html_e( 'همه دوره‌های من', 'aramesh' ); ?></a>
				</div>

				<?php if ( empty( $course_ids ) ) : ?>
					<div class="card-soft p-5 text-center">
						<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'sprout', 36 ); ?></div>
						<h3 class="h5"><?php esc_html_e( 'هنوز دوره‌ای ندارید', 'aramesh' ); ?></h3>
						<p class="text-secondary"><?php esc_html_e( 'اولین دوره خود را انتخاب کنید و مسیر یادگیری را شروع کنید.', 'aramesh' ); ?></p>
						<a class="btn btn-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?></a>
					</div>
				<?php else : ?>
					<div class="row g-3">
						<?php foreach ( array_slice( $course_ids, 0, 4 ) as $cid ) :
							$percent   = aramesh_course_progress_percent( $cid );
							$continue  = aramesh_continue_lesson_id( $cid );
							?>
							<div class="col-md-6">
								<div class="card-soft p-3 h-100">
									<div class="d-flex gap-3">
										<span class="ph-media flex-shrink-0" style="width:72px;height:72px;border-radius:16px;display:grid;place-items:center">
											<?php echo has_post_thumbnail( $cid ) ? get_the_post_thumbnail( $cid, 'aramesh-avatar', array( 'style' => 'width:72px;height:72px;object-fit:cover;border-radius:16px' ) ) : aramesh_icon( 'video', 24 ); ?>
										</span>
										<div class="flex-grow-1">
											<h3 class="h6 mb-2"><a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"><?php echo esc_html( get_the_title( $cid ) ); ?></a></h3>
											<div class="progress mb-1"><div class="progress-bar" style="width:<?php echo (int) $percent; ?>%"></div></div>
											<div class="text-secondary small"><?php printf( esc_html__( '%d%% تکمیل‌شده', 'aramesh' ), (int) $percent ); ?></div>
										</div>
									</div>
									<?php if ( $continue ) : ?>
										<a class="btn btn-primary btn-sm w-100 mt-3" href="<?php echo esc_url( get_permalink( $continue ) ); ?>"><?php echo aramesh_icon( 'play', 16 ); ?> <?php esc_html_e( 'ادامه یادگیری', 'aramesh' ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<!-- support -->
				<div class="cta-soft mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
					<div>
						<h3 class="h6 m-0"><?php esc_html_e( 'به کمک نیاز دارید؟', 'aramesh' ); ?></h3>
						<p class="text-secondary small m-0"><?php esc_html_e( 'تیم پشتیبانی همراه شماست.', 'aramesh' ); ?></p>
					</div>
					<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'تماس با پشتیبانی', 'aramesh' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
