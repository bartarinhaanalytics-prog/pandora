<?php
/**
 * "ما را در شبکه‌های اجتماعی دنبال کنید".
 *
 * The addresses come from the homepage settings, the same list the sidebars
 * use, so there is only one place to edit them.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! techrato_social_links() ) {
	return;
}
?>
<section class="social-follow-section">
	<div class="container">
		<div class="social-follow-banner">
			<div class="social-follow-copy">
				<span class="eyebrow"><?php esc_html_e( 'تکراتو در شبکه‌های اجتماعی', 'techrato' ); ?></span>
				<h2><?php echo esc_html( techrato_home_option( 'follow', 'title' ) ); ?></h2>
				<p><?php echo esc_html( techrato_home_option( 'follow', 'text' ) ); ?></p>
			</div>

			<?php techrato_social_icon_row( 'social-icon-row--banner' ); ?>

		</div>
	</div>
</section>
