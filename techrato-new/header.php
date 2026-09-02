<?php
/**
 * Site header: brand, tools, desktop navigation with mega panels, and the
 * mobile menu the hamburger opens.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="container header-top">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="brand-mark">T</div>
				<div class="brand-copy">
					<strong><?php bloginfo( 'name' ); ?></strong>
					<span><?php bloginfo( 'description' ); ?></span>
				</div>
			</a>
		<?php endif; ?>

		<div class="header-tools">
			<button type="button" class="icon-button desktop-search-button js-search-toggle" aria-label="<?php esc_attr_e( 'جستجو', 'techrato' ); ?>">
				<svg class="icon icon-search" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"></circle><path d="M16 16l4 4"></path></svg>
			</button>

			<button type="button" class="mobile-menu-toggle js-menu-toggle"
				aria-controls="mobile-menu-panel" aria-expanded="false"
				aria-label="<?php esc_attr_e( 'باز کردن منو', 'techrato' ); ?>">
				<span></span><span></span><span></span>
			</button>

			<a class="login-button" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'ورود', 'techrato' ); ?></a>
		</div>
	</div>

	<nav class="main-nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'techrato' ); ?>">
		<div class="container nav-inner">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 2,
					'walker'         => new Techrato_Mega_Menu_Walker(),
				) );
			} else {
				techrato_fallback_primary_menu();
			}
			?>
		</div>
	</nav>
</header>

<?php // The search panel the magnifier opens. Hidden until asked for. ?>
<div class="search-overlay js-search-overlay" hidden>
	<div class="container search-overlay__inner">
		<?php get_search_form(); ?>
		<button type="button" class="search-overlay__close js-search-close" aria-label="<?php esc_attr_e( 'بستن جستجو', 'techrato' ); ?>">&times;</button>
	</div>
</div>

<div class="mobile-menu-backdrop js-menu-backdrop" hidden></div>

<div class="mobile-menu-panel" id="mobile-menu-panel" aria-hidden="true">
	<div class="mobile-menu__top">
		<button type="button" class="mobile-menu-close js-menu-close" aria-label="<?php esc_attr_e( 'بستن منو', 'techrato' ); ?>">
			<span><?php esc_html_e( 'بازگشت', 'techrato' ); ?></span>
			<b><svg class="icon icon-back" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6"></path></svg></b>
		</button>
	</div>

	<div class="mobile-menu__body">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-menu__list',
				'depth'          => 2,
			) );
		}
		?>
	</div>
</div>
