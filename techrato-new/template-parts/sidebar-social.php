<?php
/**
 * The dark "follow us" card. The article and category columns style it
 * differently, so the caller says which shape it wants.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_style = isset( $args['style'] ) && 'category' === $args['style'] ? 'category' : 'article';

// One link is enough here; the full list lives in the homepage banner and the
// footer. The first social menu item is the one people actually follow.
$techrato_url   = techrato_home_option( 'follow', 'url' );
$techrato_label = __( 'دنبال کردن تکراتو', 'techrato' );

if ( ! $techrato_url && has_nav_menu( 'social' ) ) {
	$techrato_locations = get_nav_menu_locations();
	$techrato_items     = isset( $techrato_locations['social'] ) ? wp_get_nav_menu_items( $techrato_locations['social'] ) : array();
	if ( ! empty( $techrato_items[0]->url ) ) {
		$techrato_url = $techrato_items[0]->url;
	}
}

if ( ! $techrato_url ) {
	return;
}

if ( 'category' === $techrato_style ) :
	?>
	<section class="category-side-card category-social-card">
		<span class="eyebrow"><?php esc_html_e( 'شبکه‌های اجتماعی', 'techrato' ); ?></span>
		<h3><?php esc_html_e( 'تکراتو را دنبال کنید', 'techrato' ); ?></h3>
		<p><?php echo esc_html( techrato_home_option( 'follow', 'text' ) ); ?></p>
		<a class="category-social-button" href="<?php echo esc_url( $techrato_url ); ?>" target="_blank" rel="noopener">
			<span><?php echo esc_html( $techrato_label ); ?></span>
			<?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</a>
	</section>
	<?php
else :
	?>
	<section class="sidebar-card sidebar-social">
		<h3><?php esc_html_e( 'تکراتو در شبکه‌های اجتماعی', 'techrato' ); ?></h3>
		<p><?php echo esc_html( techrato_home_option( 'follow', 'text' ) ); ?></p>
		<a href="<?php echo esc_url( $techrato_url ); ?>" target="_blank" rel="noopener">
			<span><?php echo esc_html( $techrato_label ); ?></span>
			<?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</a>
	</section>
	<?php
endif;
