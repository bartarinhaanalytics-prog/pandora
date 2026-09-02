<?php
/**
 * Generic static page template.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main id="primary">
	<div class="container">
		<?php techrato_breadcrumbs(); ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'page-content' ); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
