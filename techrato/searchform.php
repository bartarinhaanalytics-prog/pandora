<?php
/**
 * Search form.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="techrato-search"><?php esc_html_e( 'جستجو', 'techrato' ); ?></label>
	<input type="search" id="techrato-search" name="s" placeholder="<?php esc_attr_e( 'جستجو در تکراتو…', 'techrato' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
	<button type="submit" class="btn" aria-label="<?php esc_attr_e( 'جستجو', 'techrato' ); ?>">
		<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
	</button>
</form>
