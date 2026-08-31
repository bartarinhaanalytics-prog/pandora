<?php
/**
 * A row in the Iranian-tech list beside that section's lead story.
 *
 * The image is a direct child on purpose: the stylesheet sizes
 * `.iran-tech-item > img`, and wrapping it in a link breaks that.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="iran-tech-item">
	<?php echo techrato_thumb( 'techrato-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	<div>
		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<small><?php echo esc_html( techrato_time_ago() ); ?></small>
	</div>
</article>
