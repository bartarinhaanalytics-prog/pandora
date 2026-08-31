<?php
/**
 * "ما را در شبکه‌های اجتماعی دنبال کنید".
 *
 * The links come from the "فوتر - شبکه‌های اجتماعی" menu, so the same list
 * feeds the footer and this banner and there is only one place to edit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_socials = array();

if ( has_nav_menu( 'social' ) ) {
	$techrato_items = wp_get_nav_menu_items( get_nav_menu_locations()['social'] );
	foreach ( (array) $techrato_items as $techrato_item ) {
		if ( empty( $techrato_item->url ) ) {
			continue;
		}
		$techrato_socials[] = array( 'title' => $techrato_item->title, 'url' => $techrato_item->url );
	}
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

			<?php if ( $techrato_socials ) : ?>
				<div class="social-follow-links">
					<?php foreach ( $techrato_socials as $techrato_social ) : ?>
						<a class="social-follow-link" href="<?php echo esc_url( $techrato_social['url'] ); ?>" target="_blank" rel="noopener">
							<span><?php echo esc_html( $techrato_social['title'] ); ?></span>
							<?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php elseif ( techrato_home_option( 'follow', 'url' ) ) : ?>
				<a class="social-share-button" href="<?php echo esc_url( techrato_home_option( 'follow', 'url' ) ); ?>"
					aria-label="<?php esc_attr_e( 'شبکه‌های اجتماعی تکراتو', 'techrato' ); ?>">
					<?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
