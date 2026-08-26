<?php
/**
 * مقاله تکی (Page 14).
 *
 * @package Aramesh
 */

get_header();

while ( have_posts() ) :
	the_post();
	$processed = aramesh_toc_and_content( apply_filters( 'the_content', get_the_content() ) );
	$cats      = get_the_category();
	?>
	<article <?php post_class(); ?>>
		<section class="hero pb-2">
			<div class="container" style="max-width:900px">
				<?php aramesh_breadcrumb(); ?>
				<div class="text-center mt-2">
					<?php if ( ! empty( $cats ) ) : ?>
						<a class="badge-soft mb-3 d-inline-flex" href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
					<?php endif; ?>
					<h1 class="mb-3"><?php the_title(); ?></h1>
					<div class="meta-row justify-content-center">
						<span><?php echo aramesh_icon( 'users', 16 ); ?> <?php the_author(); ?></span>
						<span><?php echo aramesh_icon( 'calendar', 16 ); ?> <?php echo esc_html( get_the_date() ); ?></span>
						<span><?php echo aramesh_icon( 'clock', 16 ); ?> <?php printf( esc_html__( '%d دقیقه مطالعه', 'aramesh' ), aramesh_reading_time() ); ?></span>
					</div>
				</div>
			</div>
		</section>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="container" style="max-width:960px">
				<div class="ratio ratio-16x9 rounded-card overflow-hidden mb-4"><?php the_post_thumbnail( 'aramesh-cover', array( 'style' => 'object-fit:cover;width:100%;height:100%' ) ); ?></div>
			</div>
		<?php endif; ?>

		<section class="section-sm pt-0">
			<div class="container" style="max-width:820px">
				<?php if ( $processed['toc'] ) : ?>
					<div class="mb-4"><?php echo wp_kses_post( $processed['toc'] ); ?></div>
				<?php endif; ?>

				<div class="article-body">
					<?php echo wp_kses_post( $processed['html'] ); ?>
				</div>

				<?php
				wp_link_pages( array( 'before' => '<div class="mt-3">' . esc_html__( 'صفحات:', 'aramesh' ), 'after' => '</div>' ) );
				$tags = get_the_tag_list( '<div class="chip-row mt-4">', '', '</div>' );
				if ( $tags ) {
					echo wp_kses_post( str_replace( 'rel="tag"', 'class="chip" rel="tag"', $tags ) );
				}
				?>

				<!-- share -->
				<div class="d-flex align-items-center gap-2 mt-4">
					<span class="text-secondary small"><?php esc_html_e( 'اشتراک‌گذاری:', 'aramesh' ); ?></span>
					<a class="footer-social" href="https://t.me/share/url?url=<?php echo rawurlencode( get_permalink() ); ?>" target="_blank" rel="noopener" aria-label="تلگرام"><span><?php echo aramesh_icon( 'telegram', 18 ); ?></span></a>
					<a class="footer-social" href="https://api.whatsapp.com/send?text=<?php echo rawurlencode( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener" aria-label="واتساپ"><span><?php echo aramesh_icon( 'send', 18 ); ?></span></a>
				</div>

				<!-- author box -->
				<div class="author-box mt-5">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 72, '', '', array( 'class' => 'rounded-circle' ) ); ?>
					<div>
						<div class="fw-bold"><?php the_author(); ?></div>
						<p class="text-secondary small m-0"><?php echo esc_html( get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) : aramesh_option( 'doctor_title', 'روانشناس و درمانگر' ) ); ?></p>
					</div>
				</div>

				<!-- related courses -->
				<?php
				$rc = new WP_Query( array( 'post_type' => 'course', 'posts_per_page' => 2 ) );
				if ( $rc->have_posts() ) :
					?>
					<div class="mt-5">
						<h2 class="h5 mb-3"><?php esc_html_e( 'دوره‌های پیشنهادی', 'aramesh' ); ?></h2>
						<div class="row g-4">
							<?php while ( $rc->have_posts() ) : $rc->the_post(); echo '<div class="col-md-6">'; aramesh_render_course_card( get_the_ID() ); echo '</div>'; endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- newsletter -->
				<div class="lead-capture mt-5">
					<h2 class="h5 mb-2"><?php esc_html_e( 'مطالب تازه را دریافت کنید', 'aramesh' ); ?></h2>
					<?php echo do_shortcode( '[aramesh_lead]' ); ?>
				</div>

				<?php
				if ( comments_open() || get_comments_number() ) {
					echo '<div class="mt-5">';
					comments_template();
					echo '</div>';
				}
				?>
			</div>
		</section>
	</article>
	<?php
endwhile;

get_footer();
