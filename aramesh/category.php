<?php
/**
 * دسته مقاله (Page 13).
 *
 * @package Aramesh
 */

get_header();
?>
<section class="hero pb-3">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center mt-2" style="max-width:760px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'دسته‌بندی مقالات', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php single_cat_title(); ?></h1>
			<?php if ( category_description() ) : ?>
				<div class="lead-soft"><?php echo wp_kses_post( category_description() ); ?></div>
			<?php else : ?>
				<p class="lead-soft"><?php printf( esc_html__( 'جدیدترین مقالات در موضوع «%s».', 'aramesh' ), single_cat_title( '', false ) ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="section-sm pt-0">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-8">
				<?php if ( have_posts() ) : ?>
					<div class="row g-4">
						<?php while ( have_posts() ) : the_post(); echo '<div class="col-md-6">'; aramesh_render_article_card( get_the_ID() ); echo '</div>'; endwhile; ?>
					</div>
					<?php aramesh_pagination(); ?>
				<?php else : ?>
					<div class="card-soft p-5 text-center"><h2 class="h5"><?php esc_html_e( 'مقاله‌ای یافت نشد.', 'aramesh' ); ?></h2></div>
				<?php endif; ?>

				<!-- دوره‌های مرتبط -->
				<?php
				$related_courses = new WP_Query( array( 'post_type' => 'course', 'posts_per_page' => 3 ) );
				if ( $related_courses->have_posts() ) :
					?>
					<div class="mt-5">
						<h2 class="h5 mb-3"><?php esc_html_e( 'دوره‌های مرتبط', 'aramesh' ); ?></h2>
						<div class="row g-4">
							<?php while ( $related_courses->have_posts() ) : $related_courses->the_post(); echo '<div class="col-md-4">'; aramesh_render_course_card( get_the_ID() ); echo '</div>'; endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<aside class="col-lg-4"><?php get_sidebar(); ?></aside>
		</div>
	</div>
</section>
<?php
get_footer();
