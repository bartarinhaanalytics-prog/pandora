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

	<?php
	// The homepage is a collection of cards with no headline of its own, which
	// leaves it without an <h1>. This one names the site for search engines
	// and screen readers without altering the design.
	?>
	<h1 class="screen-reader-text">
		<?php
		echo esc_html( get_bloginfo( 'name' ) );
		$techrato_tagline = get_bloginfo( 'description' );
		if ( $techrato_tagline ) {
			echo ' — ' . esc_html( $techrato_tagline );
		}
		?>
	</h1>

	<?php if ( techrato_home_shows( 'quick' ) ) : ?>
		<?php get_template_part( 'template-parts/quick-categories' ); ?>
	<?php endif; ?>

	<div class="container">

		<!-- ===== HERO ===== -->
		<?php if ( techrato_home_shows( 'hero' ) ) : ?>
		<section class="block hero-section">
			<?php
			// The big hero card uses "تیتر اصلی صفحه نخست"; the smaller cards
			// beside it use "نوشته شاخص". Both come from the editorial
			// checkboxes on the post editor screen.
			$hero_featured = techrato_query_by_flag( '_featured_one_post_tc', techrato_home_option( 'hero', 'slides' ), $shown_ids );
			$shown_ids     = array_merge( $shown_ids, wp_list_pluck( $hero_featured->posts, 'ID' ) );

			$hero_thumbs = techrato_query_by_flag( '_featured_post_tc', techrato_home_option( 'hero', 'count' ), $shown_ids );
			$shown_ids   = array_merge( $shown_ids, wp_list_pluck( $hero_thumbs->posts, 'ID' ) );
			?>
			<div class="hero-grid">
				<div>
					<?php if ( $hero_featured->have_posts() ) : ?>
						<?php $hero_total = $hero_featured->post_count; ?>
						<div class="hero-slider js-hero-slider" data-count="<?php echo esc_attr( $hero_total ); ?>">
							<div class="hero-slides">
								<?php
								$hero_index = 0;
								while ( $hero_featured->have_posts() ) :
									$hero_featured->the_post();
									?>
									<div class="hero-slide<?php echo 0 === $hero_index ? ' is-active' : ''; ?>">
										<?php get_template_part( 'template-parts/card', 'hero' ); ?>
									</div>
									<?php
									$hero_index++;
								endwhile;
								wp_reset_postdata();
								?>
							</div>

							<?php if ( $hero_total > 1 ) : ?>
								<button type="button" class="hero-arrow hero-prev" aria-label="<?php esc_attr_e( 'اسلاید قبلی', 'techrato' ); ?>">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m15 6-6 6 6 6"/></svg>
								</button>
								<button type="button" class="hero-arrow hero-next" aria-label="<?php esc_attr_e( 'اسلاید بعدی', 'techrato' ); ?>">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m9 6 6 6-6 6"/></svg>
								</button>
								<div class="hero-dots" role="tablist">
									<?php for ( $hero_dot = 0; $hero_dot < $hero_total; $hero_dot++ ) : ?>
										<button type="button" role="tab"
											class="<?php echo 0 === $hero_dot ? 'is-active' : ''; ?>"
											aria-selected="<?php echo 0 === $hero_dot ? 'true' : 'false'; ?>"
											aria-label="<?php /* translators: %d: slide number */ printf( esc_attr__( 'اسلاید %d', 'techrato' ), $hero_dot + 1 ); ?>"></button>
									<?php endfor; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php else : ?>
						<?php techrato_empty_card_notice(); ?>
					<?php endif; ?>
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
		<?php endif; ?>

		<!-- ===== AD: UNDER THE SLIDESHOW ===== -->
		<?php techrato_ads_render_banners( 'home_top' ); ?>

		<!-- ===== EDITOR'S PICKS ===== -->
		<?php if ( techrato_home_shows( 'editors' ) ) : ?>
		<section class="block">
			<div class="section-title">
				<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg></span>
				<h2><?php echo esc_html( techrato_home_option( 'editors', 'title' ) ); ?></h2>
				<span class="bar"></span>
			</div>
			<?php
			$editors = techrato_query_by_flag( '_editor_suggestion_tc', techrato_home_option( 'editors', 'count' ), $shown_ids );
			if ( $editors->have_posts() ) :
				?>
				<div class="grid-6">
					<?php while ( $editors->have_posts() ) : $editors->the_post(); $shown_ids[] = get_the_ID(); ?>
						<?php get_template_part( 'template-parts/card', 'vertical' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<?php techrato_empty_card_notice(); ?>
			<?php endif; ?>
		</section>
		<?php endif; ?>

		<!-- ===== SIDEBAR + LATEST TECH NEWS ===== -->
		<?php if ( techrato_home_shows( 'latest' ) || techrato_home_shows( 'sidebar' ) || techrato_home_shows( 'follow' ) ) : ?>
		<section class="block">
			<div class="layout-with-sidebar">

				<div>
					<?php if ( techrato_home_shows( 'latest' ) ) : ?>
						<div class="section-title">
							<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/></svg></span>
							<h2><?php echo esc_html( techrato_home_option( 'latest', 'title' ) ); ?></h2>
							<span class="bar"></span>
						</div>
						<?php
						// Newest posts from every category — no tabs, by request.
						$news_query = new WP_Query( array(
							'posts_per_page'      => techrato_home_option( 'latest', 'count' ),
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true,
						) );
						?>
						<div class="widget list-widget">
							<?php
							get_template_part( 'template-parts/feed-box', null, array(
								'query'     => $news_query,
								'term_id'   => 0,
								'card'      => 'list-row',
								'card_args' => array( 'tags' => true, 'excerpt' => true ),
								'ads'       => 'latest_native',
								'more_url'  => techrato_more_url( 'more_link_latest', $news_query ),
							) );
							?>
						</div>
					<?php endif; ?>
				</div>

				<aside<?php echo techrato_ads_has( 'sidebar' ) ? ' class="has-side-ads"' : ''; ?>>
					<?php if ( techrato_home_shows( 'sidebar' ) ) : ?>
						<?php
						$side_term  = techrato_home_term( 'sidebar', 'cat', array( 'smartphone', 'mobile' ) );
						$side_id    = $side_term ? (int) $side_term->term_id : 0;
						$side_title = techrato_home_option( 'sidebar', 'title' );
						$side_query = new WP_Query( array(
							'posts_per_page'      => techrato_home_option( 'sidebar', 'count' ),
							'post_status'         => 'publish',
							'ignore_sticky_posts' => true,
							'cat'                 => $side_id ? $side_id : '',
						) );
						?>
						<div class="widget">
							<h3 class="widget-title"><?php echo esc_html( $side_title ? $side_title : ( $side_term ? $side_term->name : __( 'موبایل', 'techrato' ) ) ); ?></h3>
							<?php
							get_template_part( 'template-parts/feed-box', null, array(
								'query'    => $side_query,
								'term_id'  => $side_id,
								'card'     => 'horizontal',
								'more_url' => $side_term ? get_category_link( $side_id ) : techrato_more_url( 'more_link_learning', $side_query ),
							) );
							?>
						</div>
					<?php endif; ?>

					<?php // Two 300x120 banners, under the sidebar box. ?>
					<?php techrato_ads_render_banners( 'sidebar' ); ?>

					<?php if ( techrato_home_shows( 'follow' ) ) : ?>
						<?php get_template_part( 'template-parts/promo-follow' ); ?>
					<?php endif; ?>
				</aside>
			</div>
		</section>
		<?php endif; ?>

		<!-- ===== MOST VIEWED ===== -->
		<?php if ( techrato_home_shows( 'popular' ) ) : ?>
		<section class="block" style="padding-top:0;">
			<div class="widget" style="margin-bottom:0;">
				<h3 class="widget-title">
					<svg viewBox="0 0 24 24" width="16" height="16" style="display:inline-block;vertical-align:-3px;margin-inline-start:6px;" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l6-6 4 4 8-8M21 7v6M21 7h-6"/></svg>
					<?php echo esc_html( techrato_home_option( 'popular', 'title' ) ); ?>
				</h3>
				<?php
				$popular_days = techrato_home_option( 'popular', 'days' );
				$popular      = techrato_query_popular( techrato_home_option( 'popular', 'count' ), $popular_days, $shown_ids );
				$shown_ids    = array_merge( $shown_ids, wp_list_pluck( $popular->posts, 'ID' ) );

				get_template_part( 'template-parts/feed-box', null, array(
					'query'      => $popular,
					'term_id'    => 0,
					'card'       => 'horizontal',
					'list_class' => 'most-viewed-grid',
					'days'       => $popular_days,
					'sort'       => 'views',
					'more_url'   => techrato_more_url( 'more_link_popular', $popular ),
				) );
				?>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( techrato_home_shows( 'banner' ) ) : ?>
			<?php get_template_part( 'template-parts/social-banner' ); ?>
		<?php endif; ?>

		<!-- ===== IRAN TECH NEWS ===== -->
		<?php if ( techrato_home_shows( 'iran' ) ) : ?>
		<?php
		$iran_term    = techrato_home_term( 'iran', 'cat', array( 'technology' ) );
		$iran_feature = $iran_term
			? new WP_Query( array( 'posts_per_page' => 1, 'post_status' => 'publish', 'cat' => (int) $iran_term->term_id, 'post__not_in' => $shown_ids, 'ignore_sticky_posts' => true ) )
			: techrato_query_by_slug( 'technology', 1, $shown_ids );
		?>
		<section class="block">
			<div class="section-title">
				<h2><?php echo esc_html( techrato_home_option( 'iran', 'title' ) ); ?></h2>
				<span class="bar"></span>
				<a class="more" href="<?php echo esc_url( $iran_term ? get_category_link( $iran_term->term_id ) : techrato_more_url( 'more_link_iran', $iran_feature, 'technology' ) ); ?>">« <?php esc_html_e( 'محتوای بیشتر', 'techrato' ); ?></a>
			</div>
			<div class="split-feature">
				<div>
					<?php
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
					$iran_list = new WP_Query( array(
						'posts_per_page'      => techrato_home_option( 'iran', 'count' ),
						'post_status'         => 'publish',
						'post__not_in'        => $shown_ids,
						'ignore_sticky_posts' => true,
						'cat'                 => $iran_term ? (int) $iran_term->term_id : '',
					) );
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
		<?php endif; ?>

		<!-- ===== 3-COLUMN RELATED ===== -->
		<?php if ( techrato_home_shows( 'columns' ) ) : ?>
		<section class="block">
			<div class="grid-3">
				<?php
				$col_count = techrato_home_option( 'columns', 'count' );
				$columns   = array(
					array( techrato_home_option( 'columns', 'title1' ), techrato_home_term( 'columns', 'cat1', array( 'business' ) ) ),
					array( techrato_home_option( 'columns', 'title2' ), techrato_home_term( 'columns', 'cat2', array( 'car' ) ) ),
					array( techrato_home_option( 'columns', 'title3' ), techrato_home_term( 'columns', 'cat3', array( 'ai' ) ) ),
				);
				foreach ( $columns as $column ) :
					list( $label, $col_term ) = $column;
					$col_query = $col_term
						? new WP_Query( array( 'posts_per_page' => $col_count, 'post_status' => 'publish', 'cat' => (int) $col_term->term_id, 'ignore_sticky_posts' => true ) )
						: new WP_Query( array( 'posts_per_page' => $col_count, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
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
		<?php endif; ?>

	</div>

	<?php if ( techrato_home_shows( 'apps' ) ) : ?>
		<?php get_template_part( 'template-parts/app-showcase' ); ?>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
