<?php
/**
 * Compact sidebar "follow us" promo box.
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
<div class="promo-box">
	<h3><?php echo esc_html( get_theme_mod( 'follow_banner_title', __( 'مارا در شبکه اجتماعی دنبال کنید', 'techrato' ) ) ); ?></h3>
	<p><?php echo esc_html( wp_trim_words( get_theme_mod( 'follow_banner_text', '' ), 24 ) ); ?></p>
	<div class="wordmark" style="font-size:22px;color:var(--border-strong);display:flex;align-items:center;gap:8px;margin-bottom:16px;">
		<span><?php bloginfo( 'name' ); ?></span>
		<span class="logo-mark">
			<svg viewBox="0 0 24 24" width="20" height="20" fill="none"><rect x="3" y="12" width="4" height="9" rx="1" fill="currentColor"/><rect x="10" y="7" width="4" height="14" rx="1" fill="currentColor"/><rect x="17" y="3" width="4" height="18" rx="1" fill="currentColor"/></svg>
		</span>
	</div>
	<div class="socials" style="display:flex;gap:10px;">
		<?php foreach ( $socials as $url ) : techrato_social_icon( $url ); endforeach; ?>
	</div>
</div>
