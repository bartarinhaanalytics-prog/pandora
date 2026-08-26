<?php
/**
 * هدر قالب.
 *
 * @package Aramesh
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="visually-hidden-focusable btn btn-primary position-absolute top-0 start-0 m-2" href="#main" style="z-index:2000"><?php esc_html_e( 'رفتن به محتوا', 'aramesh' ); ?></a>

<header class="site-header">
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<div class="d-flex align-items-center">
				<?php aramesh_logo(); ?>
			</div>

			<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#aramesh-nav" aria-controls="aramesh-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'باز/بستن منو', 'aramesh' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse justify-content-center" id="aramesh-nav">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'navbar-nav gap-lg-1 align-items-lg-center',
							'menu_id'        => 'primary-menu',
							'depth'          => 2,
							'fallback_cb'    => 'aramesh_primary_menu_fallback',
							'link_before'    => '',
							'walker'         => new Aramesh_Nav_Walker(),
						)
					);
				} else {
					aramesh_primary_menu_fallback();
				}
				?>
			</div>

			<div class="header-cta d-none d-lg-flex align-items-center">
				<?php if ( is_user_logged_in() ) : ?>
					<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'account' ) ); ?>">
						<?php echo aramesh_icon( 'users', 18 ); ?>
						<?php esc_html_e( 'حساب من', 'aramesh' ); ?>
					</a>
				<?php else : ?>
					<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'login' ) ); ?>">
						<?php esc_html_e( 'ورود / حساب من', 'aramesh' ); ?>
					</a>
				<?php endif; ?>
				<a class="btn btn-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>">
					<?php echo aramesh_icon( 'arrow-left', 18 ); ?>
					<?php esc_html_e( 'مشاهده دوره‌ها', 'aramesh' ); ?>
				</a>
			</div>
		</div>
	</nav>
</header>

<main id="main" class="site-main">
