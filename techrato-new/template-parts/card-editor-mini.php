<?php
/**
 * The small cards under the editor's lead pick. The image is a direct child,
 * matching what the stylesheet sizes.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$techrato_cat = techrato_primary_category();
?>
<article>
	<?php echo techrato_thumb( 'techrato-list' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<div>
		<?php if ( $techrato_cat ) : ?>
			<span><?php echo esc_html( $techrato_cat ); ?></span>
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	</div>
</article>
