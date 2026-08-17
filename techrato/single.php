<?php
/**
 * Single post template.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<main id="primary">
	<div class="container">

		<?php techrato_breadcrumbs(); ?>

		<div class="layout-with-sidebar">

			<article <?php post_class(); ?>>

				<header class="article-header">
					<div class="article-header-grid">
						<div class="article-header-info">
							<h1><?php the_title(); ?></h1>

							<div class="meta" style="margin-bottom:6px;">
								<span class="meta-item">
									<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
									<?php the_author(); ?>
								</span>
								<?php techrato_card_meta(); ?>
							</div>

							<?php
							$techrato_post_id  = get_the_ID();
							$techrato_likes    = (int) get_post_meta( $techrato_post_id, 'techrato_likes', true );
							$techrato_is_liked = ! empty( $_COOKIE[ 'techrato_liked_' . $techrato_post_id ] );
							$techrato_is_saved = false; // Saved posts live in the browser's localStorage; JS applies this on load.
							?>
							<div class="article-actions">
								<button type="button" class="action js-like-btn<?php echo $techrato_is_liked ? ' is-liked' : ''; ?>" data-post-id="<?php echo esc_attr( $techrato_post_id ); ?>">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
									<span class="count"><?php echo esc_html( $techrato_likes ); ?></span>
								</button>
								<span class="action">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
									<?php comments_number( __( 'بدون دیدگاه', 'techrato' ), __( '۱ دیدگاه', 'techrato' ), __( '% دیدگاه', 'techrato' ) ); ?>
								</span>
								<span class="action">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 10.5 6.8-3.9M8.6 13.5l6.8 3.9"/></svg>
									<?php esc_html_e( 'اشتراک‌گذاری', 'techrato' ); ?>
								</span>
								<button type="button" class="action js-save-btn<?php echo $techrato_is_saved ? ' is-saved' : ''; ?>" data-post-id="<?php echo esc_attr( $techrato_post_id ); ?>">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 4h12v17l-6-4-6 4V4Z"/></svg>
									<span><?php esc_html_e( 'ذخیره', 'techrato' ); ?></span>
								</button>
							</div>
						</div>

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="article-featured"><?php the_post_thumbnail( 'techrato-hero' ); ?></div>
						<?php endif; ?>
					</div>
				</header>

				<div class="article-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'صفحات:', 'techrato' ),
						'after'  => '</div>',
					) );
					?>
				</div>

				<?php
				$tags = get_the_tags();
				if ( $tags ) :
					?>
					<div class="article-tags">
						<?php foreach ( $tags as $tag ) : ?>
							<a class="pill" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="author-box">
					<div class="avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 56 ); ?></div>
					<div>
						<div class="name"><?php the_author(); ?></div>
						<p class="bio"><?php echo esc_html( wp_trim_words( get_the_author_meta( 'description' ), 20 ) ); ?></p>
						<a class="link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php esc_html_e( 'مشاهده کلیه مطالب منتشر شده', 'techrato' ); ?> ←</a>
					</div>
				</div>

				<div class="comments-section">
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div>

				<?php get_template_part( 'template-parts/social-banner' ); ?>

				<!-- ===== RELATED POSTS ===== -->
				<section class="block">
					<div class="section-title">
						<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg></span>
						<h2><?php esc_html_e( 'مطالب مشابه', 'techrato' ); ?></h2>
						<span class="bar"></span>
					</div>
					<?php
					$cats = get_the_category();
					$related_args = array(
						'posts_per_page'      => 4,
						'post__not_in'        => array( get_the_ID() ),
						'ignore_sticky_posts' => true,
					);
					if ( ! empty( $cats ) ) {
						$related_args['cat'] = $cats[0]->term_id;
					}
					$related = new WP_Query( $related_args );
					if ( $related->have_posts() ) :
						?>
						<div class="grid-4">
							<?php while ( $related->have_posts() ) : $related->the_post(); ?>
								<?php get_template_part( 'template-parts/card', 'vertical' ); ?>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					<?php else : ?>
						<?php techrato_empty_card_notice(); ?>
					<?php endif; ?>
				</section>

			</article>

			<aside>
				<div class="widget">
					<h3 class="widget-title"><?php esc_html_e( 'جدید ترین اخبار تکنولوژی', 'techrato' ); ?></h3>
					<?php
					$sb = techrato_query_by_slug( 'technology', 4, array( get_the_ID() ) );
					if ( $sb->have_posts() ) :
						while ( $sb->have_posts() ) : $sb->the_post();
							get_template_part( 'template-parts/card', 'horizontal' );
						endwhile;
						wp_reset_postdata();
					else :
						techrato_empty_card_notice();
					endif;
					?>
				</div>

				<?php get_template_part( 'template-parts/promo-follow' ); ?>

				<?php if ( is_active_sidebar( 'sidebar-article' ) ) : ?>
					<?php dynamic_sidebar( 'sidebar-article' ); ?>
				<?php endif; ?>
			</aside>

		</div>
	</div>

	<?php get_template_part( 'template-parts/app-showcase', '', array( 'exclude' => array( get_the_ID() ) ) ); ?>
</main>

<?php get_footer(); ?>
