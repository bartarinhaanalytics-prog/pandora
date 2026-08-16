<?php
/**
 * Homepage template.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$shown_ids = array();
?>

<main id="primary">
	<div class="container">

		<!-- ===== HERO ===== -->
		<section class="block" style="padding-top:34px;">
			<?php
			$hero_thumbs = techrato_query_by_slug( 'technology', 4, $shown_ids );
			$hero_ids    = wp_list_pluck( $hero_thumbs->posts, 'ID' );
			$shown_ids   = array_merge( $shown_ids, $hero_ids );

			$hero_featured = techrato_query_by_slug( 'apple', 1, $shown_ids );
			if ( ! $hero_featured->have_posts() ) {
				$hero_featured = new WP_Query( array( 'posts_per_page' => 1, 'post__not_in' => $shown_ids, 'ignore_sticky_posts' => true ) );
			}
			$shown_ids = array_merge( $shown_ids, wp_list_pluck( $hero_featured->posts, 'ID' ) );
			?>
			<div class="hero-grid">
				<div>
					<?php
					if ( $hero_featured->have_posts() ) :
						while ( $hero_featured->have_posts() ) : $hero_featured->the_post();
							get_template_part( 'template-parts/card', 'hero' );
						endwhile;
						wp_reset_postdata();
					else :
						techrato_empty_card_notice();
					endif;
					?>
				</div>
				<div class="hero-thumbs">
					<?php if ( $hero_thumbs->have_posts() ) : ?>
						<?php while ( $hero_thumbs->have_posts() ) : $hero_thumbs->the_post(); ?>
							<?php
							$cats  = get_the_category();
							$badge = isset( $cats[0] ) ? $cats[0]->name : '';
							get_template_part( 'template-parts/card', 'vertical', array( 'variant' => 'compact', 'badge' => $badge ) );
							?>
						<?php endwhile; wp_reset_postdata(); ?>
					<?php else : ?>
						<?php techrato_empty_card_notice(); ?>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- ===== EDITOR'S PICKS ===== -->
		<section class="block">
			<div class="section-title">
				<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg></span>
				<h2><?php esc_html_e( 'پیشنهادهای سردبیر', 'techrato' ); ?></h2>
				<span class="bar"></span>
			</div>
			<?php
			$editors = techrato_query_by_slug( 'ai', 5, $shown_ids );
			if ( $editors->have_posts() ) :
				?>
				<div class="grid-5">
					<?php while ( $editors->have_posts() ) : $editors->the_post(); $shown_ids[] = get_the_ID(); ?>
						<?php get_template_part( 'template-parts/card', 'vertical' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<?php techrato_empty_card_notice(); ?>
			<?php endif; ?>
		</section>

		<!-- ===== SIDEBAR + LATEST TECH NEWS ===== -->
		<section class="block">
			<div class="layout-with-sidebar">

				<div>
					<div class="section-title">
						<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/></svg></span>
						<h2><?php esc_html_e( 'جدیدترین اخبار تکنولوژی', 'techrato' ); ?></h2>
						<span class="bar"></span>
					</div>
					<div class="widget list-widget">
						<div class="tabs" style="margin-bottom:16px;">
							<button class="is-active"><?php esc_html_e( 'همه', 'techrato' ); ?></button>
							<button><?php esc_html_e( 'اپل', 'techrato' ); ?></button>
							<button><?php esc_html_e( 'موبایل', 'techrato' ); ?></button>
							<button><?php esc_html_e( 'اینترنت', 'techrato' ); ?></button>
							<button><?php esc_html_e( 'بازی ویدیویی', 'techrato' ); ?></button>
						</div>
						<?php
						$latest = new WP_Query( array( 'posts_per_page' => 4, 'ignore_sticky_posts' => true ) );
						if ( $latest->have_posts() ) :
							while ( $latest->have_posts() ) : $latest->the_post();
								get_template_part( 'template-parts/card', 'list-row', array( 'tags' => true, 'excerpt' => true ) );
							endwhile;
							wp_reset_postdata();
						else :
							techrato_empty_card_notice();
						endif;
						?>
					</div>
				</div>

				<aside>
					<div class="widget">
						<h3 class="widget-title"><?php esc_html_e( 'مقالات آموزشی تکراتو', 'techrato' ); ?></h3>
						<?php
						$learning = techrato_query_by_slug( 'learning', 3 );
						if ( $learning->have_posts() ) :
							while ( $learning->have_posts() ) : $learning->the_post();
								get_template_part( 'template-parts/card', 'horizontal' );
							endwhile;
							wp_reset_postdata();
							?>
							<a class="more-link" href="<?php echo esc_url( home_url( '/?cat=learning' ) ); ?>"><?php esc_html_e( 'مشاهده مطالب بیشتر', 'techrato' ); ?></a>
						<?php else : ?>
							<?php techrato_empty_card_notice(); ?>
						<?php endif; ?>
					</div>

					<?php get_template_part( 'template-parts/promo-follow' ); ?>
				</aside>
			</div>
		</section>

		<!-- ===== MOST VIEWED ===== -->
		<section class="block" style="padding-top:0;">
			<div class="widget" style="margin-bottom:0;">
				<h3 class="widget-title">
					<svg viewBox="0 0 24 24" width="16" height="16" style="display:inline-block;vertical-align:-3px;margin-inline-start:6px;" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 8-8M21 7v6M21 7h-6"/></svg>
					<?php esc_html_e( 'پربازدید ترین مطالب', 'techrato' ); ?>
				</h3>
				<div class="most-viewed-grid">
					<?php
					$popular = new WP_Query( array(
						'posts_per_page'      => 3,
						'post__not_in'        => $shown_ids,
						'ignore_sticky_posts' => true,
						'orderby'             => 'comment_count',
						'order'               => 'DESC',
					) );
					if ( $popular->have_posts() ) :
						while ( $popular->have_posts() ) : $popular->the_post(); $shown_ids[] = get_the_ID();
							get_template_part( 'template-parts/card', 'horizontal' );
						endwhile;
						wp_reset_postdata();
					else :
						techrato_empty_card_notice();
					endif;
					?>
				</div>
				<a class="more-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'مشاهده مطالب بیشتر', 'techrato' ); ?></a>
			</div>
		</section>

		<?php get_template_part( 'template-parts/social-banner' ); ?>

		<!-- ===== IRAN TECH NEWS ===== -->
		<section class="block">
			<div class="section-title">
				<h2><?php esc_html_e( 'آخرین اخبار فناوری ایران', 'techrato' ); ?></h2>
				<span class="bar"></span>
				<a class="more" href="<?php echo esc_url( home_url( '/' ) ); ?>">« <?php esc_html_e( 'محتوای بیشتر', 'techrato' ); ?></a>
			</div>
			<div class="split-feature">
				<div>
					<?php
					$iran_feature = techrato_query_by_slug( 'technology', 1, $shown_ids );
					if ( $iran_feature->have_posts() ) :
						while ( $iran_feature->have_posts() ) : $iran_feature->the_post(); $shown_ids[] = get_the_ID();
							get_template_part( 'template-parts/card', 'vertical', array( 'variant' => 'feature' ) );
						endwhile;
						wp_reset_postdata();
					else :
						techrato_empty_card_notice();
					endif;
					?>
				</div>
				<div>
					<?php
					$iran_list = new WP_Query( array( 'posts_per_page' => 3, 'post__not_in' => $shown_ids, 'ignore_sticky_posts' => true ) );
					if ( $iran_list->have_posts() ) :
						while ( $iran_list->have_posts() ) : $iran_list->the_post();
							get_template_part( 'template-parts/card', 'list-row', array( 'excerpt' => false ) );
						endwhile;
						wp_reset_postdata();
					else :
						techrato_empty_card_notice();
					endif;
					?>
				</div>
			</div>
		</section>

		<!-- ===== 3-COLUMN RELATED ===== -->
		<section class="block">
			<div class="grid-3">
				<?php
				$columns = array(
					'business' => __( 'اخبار مرتبط با کسب و کار', 'techrato' ),
					'car'      => __( 'اخبار مرتبط با خودرو', 'techrato' ),
					'ai'       => __( 'اخبار مرتبط با هوش مصنوعی', 'techrato' ),
				);
				foreach ( $columns as $slug => $label ) :
					$col_query = techrato_query_by_slug( $slug, 4 );
					?>
					<div>
						<div class="section-title">
							<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg></span>
							<h2 style="font-size:15px;"><?php echo esc_html( $label ); ?></h2>
						</div>
						<?php if ( $col_query->have_posts() ) : ?>
							<?php $first = true; ?>
							<?php while ( $col_query->have_posts() ) : $col_query->the_post(); ?>
								<?php if ( $first ) : ?>
									<?php get_template_part( 'template-parts/card', 'vertical', array( 'variant' => 'feature' ) ); ?>
									<?php $first = false; ?>
								<?php else : ?>
									<?php get_template_part( 'template-parts/card', 'avatar-row' ); ?>
								<?php endif; ?>
							<?php endwhile; wp_reset_postdata(); ?>
						<?php else : ?>
							<?php techrato_empty_card_notice(); ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</section>

	</div>

	<?php get_template_part( 'template-parts/app-showcase' ); ?>

</main>

<?php get_footer(); ?>
