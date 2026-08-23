<?php
/**
 * The footer for the Techrato theme.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<?php // Text link ads — above the footer, on every page. ?>
	<?php techrato_link_ads_render(); ?>

	<footer class="site-footer">
		<div class="container">

			<div class="footer-grid">

				<div class="footer-col footer-col--popular">
					<h4><?php esc_html_e( 'پر بحث ترین اخبار و مقالات فناوری', 'techrato' ); ?></h4>
					<div class="grid-2">
						<?php
						$popular = new WP_Query( array(
							'posts_per_page'      => 4,
							'ignore_sticky_posts' => true,
							'orderby'             => 'comment_count',
							'order'               => 'DESC',
						) );
						if ( $popular->have_posts() ) :
							while ( $popular->have_posts() ) : $popular->the_post();
								?>
								<a class="card-app" href="<?php the_permalink(); ?>">
									<span class="thumb">
										<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'techrato-square' ); } else { ?>
											<img src="<?php echo esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ); ?>" alt="">
										<?php } ?>
									</span>
									<span class="body">
										<?php $__cats = get_the_category(); ?>
										<span class="eyebrow"><?php echo esc_html( isset( $__cats[0] ) ? $__cats[0]->name : '' ); ?></span>
										<h3><?php the_title(); ?></h3>
									</span>
								</a>
								<?php
							endwhile;
							wp_reset_postdata();
						else :
							techrato_empty_card_notice();
						endif;
						?>
					</div>
				</div>

				<div class="footer-col">
					<h4><?php esc_html_e( 'تکراتو', 'techrato' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer-techrato' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'footer-techrato',
							'container'      => false,
							'menu_class'     => '',
							'items_wrap'     => '<ul>%3$s</ul>',
							'depth'          => 1,
						) );
					} else {
						echo '<ul>';
						echo '<li><a href="' . esc_url( home_url( '/advertise' ) ) . '">' . esc_html__( 'تبلیغات در تکراتو', 'techrato' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '">' . esc_html__( 'درباره ما', 'techrato' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">' . esc_html__( 'تماس با ما', 'techrato' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/sitemap' ) ) . '">' . esc_html__( 'نقشه سایت', 'techrato' ) . '</a></li>';
						echo '</ul>';
					}
					?>
				</div>

				<div class="footer-col footer-col--categories">
					<h4><?php esc_html_e( 'دسته بندی ها', 'techrato' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer-categories' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'footer-categories',
							'container'      => false,
							'items_wrap'     => '<ul>%3$s</ul>',
							'depth'          => 1,
						) );
					} else {
						$cats = get_categories( array( 'number' => 5, 'hide_empty' => false ) );
						echo '<ul>';
						if ( $cats ) {
							foreach ( $cats as $cat ) {
								echo '<li><a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
							}
						}
						echo '</ul>';
					}
					?>
				</div>

				<div class="footer-col footer-brand">
					<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php techrato_site_logo(); ?>
					</a>

					<div class="newsletter-box">
						<h4><?php echo esc_html( get_theme_mod( 'newsletter_title', __( 'با تکراتو همراه باشید', 'techrato' ) ) ); ?></h4>
						<p><?php echo esc_html( get_theme_mod( 'newsletter_text', __( 'برای دریافت آخرین اخبار و مقالات، ایمیل خود را وارد کنید.', 'techrato' ) ) ); ?></p>
						<form method="post" action="<?php echo esc_url( get_theme_mod( 'newsletter_action_url', '' ) ); ?>">
							<input type="email" name="email" required placeholder="<?php esc_attr_e( 'ایمیل شما', 'techrato' ); ?>">
							<button type="submit" class="btn btn-block"><?php esc_html_e( 'عضویت', 'techrato' ); ?></button>
						</form>
					</div>
				</div>

			</div>

			<div class="footer-bottom">
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'کلیه حقوق محفوظ است.', 'techrato' ); ?>
			</div>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
