<?php
/**
 * "ما را در شبکه‌های اجتماعی دنبال کنید" banner.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
			<a class="social-share-button" href="<?php echo esc_url( techrato_home_option( 'follow', 'url' ) ); ?>"
				aria-label="<?php esc_attr_e( 'شبکه‌های اجتماعی تکراتو', 'techrato' ); ?>">
				<?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
		</div>
	</div>
</section>
