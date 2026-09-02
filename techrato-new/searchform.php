<?php
/**
 * Search form, shaped like the hero's search box.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="search-box" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="techrato-search"><?php esc_html_e( 'جستجو', 'techrato' ); ?></label>
	<input type="search" id="techrato-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'در تکراتو جستجو کنید...', 'techrato' ); ?>">
	<button type="submit"><?php esc_html_e( 'جستجو', 'techrato' ); ?></button>
</form>
