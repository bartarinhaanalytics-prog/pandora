<?php
/**
 * فرم جستجو.
 *
 * @package Aramesh
 */

?>
<form role="search" method="get" class="aramesh-search d-flex gap-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="visually-hidden" for="s"><?php esc_html_e( 'جستجو', 'aramesh' ); ?></label>
	<input type="search" id="s" class="form-control" placeholder="<?php esc_attr_e( 'جستجو در سایت…', 'aramesh' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" required>
	<button type="submit" class="btn btn-primary flex-shrink-0" aria-label="<?php esc_attr_e( 'جستجو', 'aramesh' ); ?>"><?php echo aramesh_icon( 'search', 20 ); ?></button>
</form>
