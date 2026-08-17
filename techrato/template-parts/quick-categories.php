<?php
/**
 * Mobile-only quick category pill row, shown just below the homepage hero.
 * Reuses the primary menu so it always matches the header nav content;
 * hidden on desktop via CSS (the header's own nav already covers that).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="quick-categories">
	<div class="container">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'quick-categories-nav',
				'depth'          => 1,
			) );
		} else {
			techrato_fallback_primary_menu( 'quick-categories-nav' );
		}
		?>
	</div>
</div>
