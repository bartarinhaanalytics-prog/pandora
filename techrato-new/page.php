<?php
/**
 * Static page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main id="primary">
	<section class="article-page">
		<div class="container">
			<?php techrato_breadcrumbs(); ?>
			<div class="article-shell">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class( 'article-main' ); ?>>
						<header class="article-header">
							<h1 class="article-title"><?php the_title(); ?></h1>
						</header>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="article-hero"><?php the_post_thumbnail( 'techrato-hero' ); ?></div>
						<?php endif; ?>
						<div class="article-content">
							<?php
							the_content();
							wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'صفحات:', 'techrato' ),
								'after'  => '</div>',
							) );
							?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		</div>
	</section>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<section class="comments-section" id="comments">
			<div class="container"><?php comments_template(); ?></div>
		</section>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
