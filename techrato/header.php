<?php
/**
 * The header for the Techrato theme.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl" data-theme="dark">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'رفتن به محتوای اصلی', 'techrato' ); ?></a>

<header class="site-header">
	<!-- Desktop topbar: logo on the right, icon cluster on the left -->
	<div class="container topbar topbar-desktop">
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php techrato_site_logo(); ?>
		</a>

		<div class="topbar-icons">
			<a class="icon-btn" href="<?php echo esc_url( wp_login_url() ); ?>" aria-label="<?php esc_attr_e( 'ورود', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
			</a>
			<span class="topbar-divider"></span>
			<button class="icon-btn js-theme-toggle" aria-label="<?php esc_attr_e( 'تغییر پوسته روشن/تیره', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
			</button>
			<button class="icon-btn js-search-toggle" aria-label="<?php esc_attr_e( 'جستجو', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
		</div>
	</div>

	<!-- Mobile topbar: hamburger alone on the right, search + logo grouped on the left -->
	<div class="container topbar topbar-mobile">
		<button class="icon-btn js-menu-toggle" aria-label="<?php esc_attr_e( 'منو', 'techrato' ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
		</button>
		<span class="topbar-mobile-brand">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php techrato_site_logo(); ?>
			</a>
			<button class="icon-btn js-search-toggle" aria-label="<?php esc_attr_e( 'جستجو', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
		</span>
	</div>

	<!-- Search — inline dropdown on desktop, full-screen overlay on mobile -->
	<div class="search-panel js-search-panel" hidden>
		<div class="search-panel-head container">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php techrato_site_logo(); ?>
			</a>
			<button class="icon-btn js-search-toggle" aria-label="<?php esc_attr_e( 'بستن', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 6l12 12M18 6 6 18"/></svg>
			</button>
		</div>
		<div class="container">
			<button class="back-link js-search-toggle" type="button">
				<?php esc_html_e( 'بازگشت', 'techrato' ); ?>
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 6 6 6-6 6"/></svg>
			</button>
			<?php get_search_form(); ?>

			<div class="section-title" style="margin-top:26px;">
				<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18"/></svg></span>
				<h2 style="font-size:15px;"><?php esc_html_e( 'بیشترین کلمات کلیدی جستجو شده', 'techrato' ); ?></h2>
				<span class="bar"></span>
			</div>
			<?php
			if ( has_nav_menu( 'search-suggestions' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'search-suggestions',
					'container'      => false,
					'menu_class'     => 'suggestions-nav',
					'depth'          => 1,
				) );
			} else {
				techrato_fallback_search_suggestions();
			}
			?>
		</div>
	</div>

	<!-- Primary nav — inline bar on desktop, full-screen drawer on mobile -->
	<nav class="main-nav-wrap js-mobile-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'منوی اصلی', 'techrato' ); ?>">
		<div class="drawer-head container">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php techrato_site_logo(); ?>
			</a>
			<button class="icon-btn js-menu-toggle" aria-label="<?php esc_attr_e( 'بستن منو', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 6l12 12M18 6 6 18"/></svg>
			</button>
		</div>
		<div class="drawer-toolbar container">
			<button class="icon-btn js-theme-toggle" aria-label="<?php esc_attr_e( 'تغییر پوسته روشن/تیره', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
			</button>
			<span class="topbar-divider"></span>
			<button class="icon-btn js-search-toggle" aria-label="<?php esc_attr_e( 'جستجو', 'techrato' ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
			<a class="login-link" href="<?php echo esc_url( wp_login_url() ); ?>">
				<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
				<?php esc_html_e( 'ورود به حساب کاربری', 'techrato' ); ?>
			</a>
		</div>
		<div class="container">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'main-nav',
					'depth'          => 0,
				) );
			} else {
				techrato_fallback_primary_menu();
			}
			?>
			<ul class="main-nav drawer-contact">
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'ارتباط با تکراتو', 'techrato' ); ?></a></li>
			</ul>
		</div>
	</nav>

	<?php if ( has_nav_menu( 'trending' ) ) : ?>
	<div class="trending-bar">
		<div class="container">
			<span class="label"><?php esc_html_e( 'پرطرفدارها:', 'techrato' ); ?></span>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'trending',
				'container'      => false,
				'menu_class'     => 'trending-nav',
				'depth'          => 1,
			) );
			?>
		</div>
	</div>
	<?php endif; ?>
</header>
