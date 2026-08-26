<?php
/**
 * فوتر قالب.
 *
 * @package Aramesh
 */

$phone     = aramesh_option( 'phone' );
$email     = aramesh_option( 'email' );
$address   = aramesh_option( 'address' );
$telegram  = aramesh_option( 'telegram' );
$instagram = aramesh_option( 'instagram' );
$youtube   = aramesh_option( 'youtube' );
?>
</main><!-- #main -->

<footer class="site-footer">
	<div class="container">
		<div class="row g-4 g-lg-5">

			<div class="col-12 col-lg-4">
				<?php aramesh_logo(); ?>
				<p class="text-secondary mt-3 mb-3" style="max-width:34ch">
					<?php echo esc_html( aramesh_option( 'doctor_bio', 'همراه شما در مسیر شناخت خود، بهبود روابط و ساختن زندگی آگاهانه‌تر و معنادارتر.' ) ); ?>
				</p>
				<div class="footer-social">
					<?php if ( $telegram ) : ?><a href="<?php echo esc_url( $telegram ); ?>" aria-label="تلگرام" target="_blank" rel="noopener"><?php echo aramesh_icon( 'telegram', 20 ); ?></a><?php endif; ?>
					<?php if ( $instagram ) : ?><a href="<?php echo esc_url( $instagram ); ?>" aria-label="اینستاگرام" target="_blank" rel="noopener"><?php echo aramesh_icon( 'instagram', 20 ); ?></a><?php endif; ?>
					<?php if ( $youtube ) : ?><a href="<?php echo esc_url( $youtube ); ?>" aria-label="یوتیوب" target="_blank" rel="noopener"><?php echo aramesh_icon( 'youtube', 20 ); ?></a><?php endif; ?>
					<?php if ( $email ) : ?><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>" aria-label="ایمیل"><?php echo aramesh_icon( 'mail', 20 ); ?></a><?php endif; ?>
				</div>
			</div>

			<div class="col-6 col-lg-3">
				<h4 class="footer-col-title"><?php esc_html_e( 'دسترسی سریع', 'aramesh' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer_links' ) ) {
					wp_nav_menu( array( 'theme_location' => 'footer_links', 'container' => false, 'menu_class' => 'footer-links', 'depth' => 1, 'fallback_cb' => false ) );
				} else {
					echo '<ul class="footer-links">';
					printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'خانه', 'aramesh' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( aramesh_courses_url() ), esc_html__( 'دوره‌ها', 'aramesh' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( aramesh_page_url( 'blog' ) ), esc_html__( 'مقالات', 'aramesh' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( aramesh_page_url( 'about' ) ), esc_html__( 'درباره من', 'aramesh' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( aramesh_page_url( 'contact' ) ), esc_html__( 'تماس با ما', 'aramesh' ) );
					echo '</ul>';
				}
				?>
			</div>

			<div class="col-6 col-lg-2">
				<h4 class="footer-col-title"><?php esc_html_e( 'حساب کاربری', 'aramesh' ); ?></h4>
				<ul class="footer-links">
					<li><a href="<?php echo esc_url( aramesh_page_url( 'account' ) ); ?>"><?php esc_html_e( 'داشبورد', 'aramesh' ); ?></a></li>
					<li><a href="<?php echo esc_url( aramesh_page_url( 'my_courses' ) ); ?>"><?php esc_html_e( 'دوره‌های من', 'aramesh' ); ?></a></li>
					<li><a href="<?php echo esc_url( aramesh_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'سوالات متداول', 'aramesh' ); ?></a></li>
					<li><a href="<?php echo esc_url( aramesh_page_url( 'legal' ) ); ?>"><?php esc_html_e( 'قوانین و حریم خصوصی', 'aramesh' ); ?></a></li>
				</ul>
			</div>

			<div class="col-12 col-lg-3">
				<h4 class="footer-col-title"><?php esc_html_e( 'اطلاعات ارتباطی', 'aramesh' ); ?></h4>
				<div class="footer-contact">
					<?php if ( $phone ) : ?><span><?php echo aramesh_icon( 'phone', 18 ); ?> <?php echo esc_html( $phone ); ?></span><?php endif; ?>
					<?php if ( $email ) : ?><span><?php echo aramesh_icon( 'mail', 18 ); ?> <?php echo esc_html( $email ); ?></span><?php endif; ?>
					<?php if ( $address ) : ?><span><?php echo aramesh_icon( 'pin', 18 ); ?> <?php echo esc_html( $address ); ?></span><?php endif; ?>
					<?php if ( ! $phone && ! $email && ! $address ) : ?>
						<span class="text-secondary small"><?php esc_html_e( 'اطلاعات تماس را از سفارشی‌سازی » اطلاعات تماس تنظیم کنید.', 'aramesh' ); ?></span>
					<?php endif; ?>
				</div>
			</div>

		</div>

		<div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
			<span><?php printf( esc_html__( '© %1$s %2$s — تمامی حقوق محفوظ است.', 'aramesh' ), esc_html( date_i18n( 'Y' ) ), esc_html( aramesh_brand_name() ) ); ?></span>
			<span><a href="<?php echo esc_url( aramesh_page_url( 'legal' ) ); ?>"><?php esc_html_e( 'حریم خصوصی', 'aramesh' ); ?></a></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
