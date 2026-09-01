<?php
/**
 * The dark "follow us" card. The article and category columns style it
 * differently, so the caller says which shape it wants.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_style = isset( $args['style'] ) && 'category' === $args['style'] ? 'category' : 'article';
$techrato_links = techrato_social_links();

if ( ! $techrato_links ) {
	return;
}

if ( 'category' === $techrato_style ) :
	?>
	<section class="category-side-card category-social-card">
		<span class="eyebrow"><?php esc_html_e( 'شبکه‌های اجتماعی', 'techrato' ); ?></span>
		<h3><?php esc_html_e( 'تکراتو را دنبال کنید', 'techrato' ); ?></h3>
		<p><?php echo esc_html( techrato_home_option( 'follow', 'text' ) ); ?></p>
		<?php techrato_social_icon_row( 'social-icon-row--card' ); ?>
	</section>
	<?php
else :
	?>
	<section class="sidebar-card sidebar-social">
		<h3><?php esc_html_e( 'تکراتو در شبکه‌های اجتماعی', 'techrato' ); ?></h3>
		<p><?php echo esc_html( techrato_home_option( 'follow', 'text' ) ); ?></p>
		<?php techrato_social_icon_row( 'social-icon-row--card' ); ?>
	</section>
	<?php
endif;
