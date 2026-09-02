<?php
/**
 * Main blog listing template (also the fallback when no other template matches).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part( 'template-parts/archive-body' );
get_footer();
