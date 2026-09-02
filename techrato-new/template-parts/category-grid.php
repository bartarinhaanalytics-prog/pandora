<?php
/**
 * The category tiles. Uses the picture set on each category when there is one,
 * and the outline icon from the design when there is not.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_terms = get_categories( array(
	'orderby'    => 'count',
	'order'      => 'DESC',
	'number'     => (int) techrato_home_option( 'quick', 'count' ),
	'parent'     => 0,
	'hide_empty' => true,
) );

if ( ! $techrato_terms ) {
	return;
}
?>
<section class="section section-white pattern-section">
	<div class="container">
		<?php techrato_section_heading( __( 'موضوعات منتخب', 'techrato' ), techrato_home_option( 'quick', 'title' ) ); ?>

		<div class="category-grid">
			<?php foreach ( $techrato_terms as $techrato_term ) : ?>
				<a class="category-card" href="<?php echo esc_url( get_category_link( $techrato_term->term_id ) ); ?>">
					<span class="category-icon" aria-hidden="true"><?php echo techrato_term_icon( $techrato_term->term_id ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3><?php echo esc_html( $techrato_term->name ); ?></h3>
					<b><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></b>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
