<?php
/**
 * نتایج جستجو (Page 18) — در میان مقالات و دوره‌ها.
 *
 * @package Aramesh
 */

get_header();

$term      = get_search_query();
$course_q  = new WP_Query( array( 'post_type' => 'course', 'posts_per_page' => 6, 's' => $term ) );
$post_q    = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6, 's' => $term ) );
$has_any   = $course_q->have_posts() || $post_q->have_posts();
?>
<section class="hero pb-3">
	<div class="container">
		<div class="text-center" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'جستجو', 'aramesh' ); ?></span>
			<h1 class="mb-3"><?php printf( esc_html__( 'نتایج برای: «%s»', 'aramesh' ), esc_html( $term ) ); ?></h1>
			<div style="max-width:520px;margin-inline:auto"><?php get_search_form(); ?></div>
		</div>
	</div>
</section>

<section class="section-sm pt-2">
	<div class="container">
		<?php if ( ! $has_any ) : ?>
			<div class="card-soft p-5 text-center">
				<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'search', 36 ); ?></div>
				<h2 class="h5"><?php esc_html_e( 'نتیجه‌ای یافت نشد.', 'aramesh' ); ?></h2>
				<p class="text-secondary"><?php esc_html_e( 'عبارت دیگری را امتحان کنید یا از منوی زیر استفاده کنید.', 'aramesh' ); ?></p>
				<div class="d-flex gap-2 justify-content-center mt-2">
					<a class="btn btn-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'دوره‌ها', 'aramesh' ); ?></a>
					<a class="btn btn-outline-primary" href="<?php echo esc_url( aramesh_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'مقالات', 'aramesh' ); ?></a>
				</div>
			</div>
		<?php else : ?>

			<?php if ( $course_q->have_posts() ) : ?>
				<h2 class="h5 mb-3"><?php echo aramesh_icon( 'video', 20 ); ?> <?php esc_html_e( 'دوره‌ها', 'aramesh' ); ?></h2>
				<div class="row g-4 mb-5">
					<?php while ( $course_q->have_posts() ) : $course_q->the_post(); echo '<div class="col-md-6 col-lg-4">'; aramesh_render_course_card( get_the_ID() ); echo '</div>'; endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>

			<?php if ( $post_q->have_posts() ) : ?>
				<h2 class="h5 mb-3"><?php echo aramesh_icon( 'book', 20 ); ?> <?php esc_html_e( 'مقالات', 'aramesh' ); ?></h2>
				<div class="row g-4">
					<?php while ( $post_q->have_posts() ) : $post_q->the_post(); echo '<div class="col-md-6 col-lg-4">'; aramesh_render_article_card( get_the_ID() ); echo '</div>'; endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>

		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
