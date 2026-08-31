<?php
/**
 * Site footer, plus the three ad slots that belong on every page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<?php // The native block belongs above the app showcase; templates without a
	// showcase still have to show it, so it is printed here. It knows whether it
	// already ran on this page and stays quiet if it did. ?>
	<?php techrato_ads_render_apps_native( true ); ?>

	<?php // Text link ads — above the footer, on every page. ?>
	<?php techrato_link_ads_render(); ?>

	<?php // Banner pair under the text link ads, on every page. ?>
	<?php techrato_ads_render_footer_banners(); ?>

	<footer class="site-footer">
		<div class="container footer-grid">
			<div class="footer-brand">
				<div class="brand-mark">T</div>
				<h3><?php bloginfo( 'name' ); ?></h3>
				<p><?php bloginfo( 'description' ); ?></p>
			</div>

			<div class="footer-column">
				<h4><?php esc_html_e( 'دسته‌بندی‌ها', 'techrato' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer-categories' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-categories',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'walker'         => new Techrato_Footer_Link_Walker(),
					) );
				} else {
					wp_list_categories( array(
						'title_li'   => '',
						'number'     => 5,
						'orderby'    => 'count',
						'order'      => 'DESC',
						'hide_empty' => true,
						'style'      => '',
						'separator'  => '',
					) );
				}
				?>
			</div>

			<div class="footer-column">
				<h4><?php bloginfo( 'name' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer-techrato' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-techrato',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'walker'         => new Techrato_Footer_Link_Walker(),
					) );
				}
				?>
			</div>

			<div class="footer-column">
				<h4><?php esc_html_e( 'همراه تکراتو', 'techrato' ); ?></h4>
				<?php
				if ( has_nav_menu( 'social' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'social',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'walker'         => new Techrato_Footer_Link_Walker(),
					) );
				}
				?>
			</div>
		</div>

		<div class="container copyright">
			<?php
			/* translators: %s: site name */
			printf( esc_html__( '© %1$s %2$s. کلیه حقوق محفوظ است.', 'techrato' ), esc_html( date_i18n( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) );
			?>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
