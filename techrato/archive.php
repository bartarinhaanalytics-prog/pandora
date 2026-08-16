<?php
/**
 * Generic archive template (tags, taxonomies, date, author).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
get_template_part( 'template-parts/archive-body' );
get_footer();
