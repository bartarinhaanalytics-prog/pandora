<?php
/**
 * Single post.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();

	$techrato_id    = get_the_ID();
	$techrato_likes = (int) get_post_meta( $techrato_id, 'techrato_likes', true );
	$techrato_liked = ! empty( $_COOKIE[ 'techrato_liked_' . $techrato_id ] ); // phpcs:ignore WordPress.Security.NonceVerification
	?>

	<main id="primary">
		<section class="article-page">
			<div class="container">
				<?php techrato_breadcrumbs(); ?>

				<div class="article-shell">
					<article <?php post_class( 'article-main' ); ?>>
						<div class="article-lead">
							<div class="article-lead__copy">
								<header class="article-header">
									<?php $techrato_cat = techrato_primary_category(); ?>
									<?php if ( $techrato_cat ) : ?>
										<span class="story-category"><?php echo esc_html( $techrato_cat ); ?></span>
									<?php endif; ?>
									<h1 class="article-title"><?php the_title(); ?></h1>

									<div class="article-meta-row">
										<div class="article-meta">
											<span><?php the_author(); ?></span><i></i>
											<span><?php echo esc_html( techrato_time_ago() ); ?></span><i></i>
											<span><?php comments_number( __( 'بدون دیدگاه', 'techrato' ), __( '۱ دیدگاه', 'techrato' ), __( '% دیدگاه', 'techrato' ) ); ?></span>
										</div>

										<div class="article-actions article-actions--under-title">
											<div class="article-actions__group article-actions__group--right">
												<button type="button" class="article-action article-action-like js-like-btn<?php echo $techrato_liked ? ' is-liked' : ''; ?>"
													data-post-id="<?php echo esc_attr( $techrato_id ); ?>" aria-label="<?php esc_attr_e( 'پسندیدن', 'techrato' ); ?>">
													<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
													<span class="article-action-count article-like-count"><?php echo esc_html( $techrato_likes ); ?></span>
												</button>
												<a class="article-action article-action-comment" href="#comments" aria-label="<?php esc_attr_e( 'دیدگاه', 'techrato' ); ?>">
													<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 1 1 17 0z"/></svg>
													<span class="article-action-count article-comment-count"><?php echo esc_html( get_comments_number() ); ?></span>
												</a>
											</div>
											<div class="article-actions__group article-actions__group--left">
												<button type="button" class="article-action article-action-save js-save-btn" data-post-id="<?php echo esc_attr( $techrato_id ); ?>" aria-label="<?php esc_attr_e( 'ذخیره', 'techrato' ); ?>">
													<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 4h12v17l-6-4-6 4V4Z"/></svg>
												</button>
											</div>
										</div>
									</div>
								</header>
							</div>

							<?php if ( has_post_thumbnail() ) : ?>
								<div class="article-hero"><?php the_post_thumbnail( 'techrato-hero' ); ?></div>
							<?php endif; ?>
						</div>

						<?php // One banner right under the like/comment row. ?>
						<?php techrato_ads_render_banners( 'single_top' ); ?>

						<div class="article-content">
							<?php
							the_content();
							wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'صفحات:', 'techrato' ),
								'after'  => '</div>',
							) );
							?>
						</div>

						<?php $techrato_tags = get_the_tags(); ?>
						<?php if ( $techrato_tags ) : ?>
							<div class="article-tags">
								<?php foreach ( $techrato_tags as $techrato_tag ) : ?>
									<a href="<?php echo esc_url( get_tag_link( $techrato_tag ) ); ?>">#<?php echo esc_html( $techrato_tag->name ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php // Two banners at the end of the article, under the tags. ?>
						<?php techrato_ads_render_banners( 'single_bottom' ); ?>
					</article>
				</div>
			</div>
		</section>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<section class="comments-section" id="comments">
				<div class="container">
					<?php comments_template(); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php
		$techrato_cats    = get_the_category();
		$techrato_related = new WP_Query( array(
			'posts_per_page'      => 4,
			'post__not_in'        => array( $techrato_id ),
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'cat'                 => isset( $techrato_cats[0] ) ? (int) $techrato_cats[0]->term_id : '',
		) );
		?>
		<?php if ( $techrato_related->have_posts() ) : ?>
			<section class="related-section">
				<div class="container">
					<?php techrato_section_heading( __( 'پیشنهاد برای مطالعه', 'techrato' ), __( 'مطالب مشابه', 'techrato' ) ); ?>
					<div class="related-grid">
						<?php while ( $techrato_related->have_posts() ) : $techrato_related->the_post(); ?>
							<?php get_template_part( 'template-parts/card', 'related' ); ?>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/app-showcase', null, array( 'exclude' => array( $techrato_id ) ) ); ?>
	</main>

	<?php
endwhile;

get_footer();
