<?php
/**
 * قالب پیش‌فرض (fallback).
 *
 * @package Aramesh
 */

get_header();
?>
<div class="container section">
	<div class="row g-4">
		<div class="col-lg-8">
			<?php if ( have_posts() ) : ?>
				<div class="row g-4">
					<?php
					while ( have_posts() ) :
						the_post();
						echo '<div class="col-md-6">';
						aramesh_render_article_card( get_the_ID() );
						echo '</div>';
					endwhile;
					?>
				</div>
				<?php aramesh_pagination(); ?>
			<?php else : ?>
				<div class="card-soft p-5 text-center">
					<h2><?php esc_html_e( 'چیزی یافت نشد.', 'aramesh' ); ?></h2>
				</div>
			<?php endif; ?>
		</div>
		<aside class="col-lg-4">
			<?php get_sidebar(); ?>
		</aside>
	</div>
</div>
<?php
get_footer();
