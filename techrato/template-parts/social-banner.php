<?php
/**
 * "Follow us on social media" full-width banner section.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$socials = array(
	get_theme_mod( 'social_link_1', '' ),
	get_theme_mod( 'social_link_2', '' ),
	get_theme_mod( 'social_link_3', '' ),
);
?>
<section class="block social-banner-wrap">
	<div class="container">
		<div class="social-banner">
			<div class="wordmark">
				<span><?php bloginfo( 'name' ); ?></span>
				<span class="logo-mark">
					<svg viewBox="0 0 24 24" fill="none"><rect x="3" y="12" width="4" height="9" rx="1" fill="currentColor"/><rect x="10" y="7" width="4" height="14" rx="1" fill="currentColor"/><rect x="17" y="3" width="4" height="18" rx="1" fill="currentColor"/></svg>
				</span>
			</div>
			<div class="content">
				<h2><?php echo esc_html( get_theme_mod( 'follow_banner_title', __( 'مارا در شبکه‌های اجتماعی دنبال کنید', 'techrato' ) ) ); ?></h2>
				<p><?php echo esc_html( get_theme_mod( 'follow_banner_text', '' ) ); ?></p>
				<div class="socials">
					<?php foreach ( $socials as $url ) : techrato_social_icon( $url ); endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
