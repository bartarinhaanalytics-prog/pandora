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

	<?php get_template_part( 'template-parts/quick-categories' ); ?>

	<div class="container">

		<!-- ===== HERO ===== -->
		<section class="block hero-section">
			<?php
			// The big hero card uses "تیتر اصلی صفحه نخست"; the smaller cards
			// beside it use "نوشته شاخص". Both come from the editorial
			// checkboxes on the post editor screen.
			$hero_featured = techrato_query_by_flag( '_featured_one_post_tc', 1, $shown_ids );
			$shown_ids     = array_merge( $shown_ids, wp_list_pluck( $hero_featured->posts, 'ID' ) );

			$hero_thumbs = techrato_query_by_flag( '_featured_post_tc', 4, $shown_ids );
			$shown_ids   = array_merge( $shown_ids, wp_list_pluck( $hero_thumbs->posts, 'ID' ) );
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
			$editors = techrato_query_by_flag( '_editor_suggestion_tc', 6, $shown_ids );
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

		<!-- ===== SIDEBAR + LATEST TECH NEWS ===== -->
		<section class="block">
			<div class="layout-with-sidebar">

				<div>
					<div class="section-title">
						<span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/></svg></span>
						<h2><?php esc_html_e( 'آخرین اخبار', 'techrato' ); ?></h2>
						<span class="bar"></span>
					</div>
					<?php
					// Tabs and load-more are handled by the shared feed box, the
					// same one the category archive uses.
					$news_tabs  = techrato_news_tabs();
					$news_first = $news_tabs[0];
					$news_term  = $news_first['term'] ? (int) $news_first['term']->term_id : 0;

					$news_query = new WP_Query( array(
						'posts_per_page'      => 4,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'cat'                 => $news_term ? $news_term : '',
					) );

					$news_feed_tabs = array();
					foreach ( $news_tabs as $tab ) {
						$news_feed_tabs[] = array(
							'term_id' => $tab['term'] ? (int) $tab['term']->term_id : 0,
							'label'   => $tab['label'],
							'url'     => $tab['term']
								? get_category_link( $tab['term']->term_id )
								: techrato_more_url( 'more_link_latest', $news_query ),
							'current' => $tab === $news_first,
						);
					}
					?>
					<div class="widget list-widget">
						<?php
						get_template_part( 'template-parts/feed-box', null, array(
							'tabs'      => $news_feed_tabs,
							'query'     => $news_query,
							'term_id'   => $news_term,
							'card'      => 'list-row',
							'card_args' => array( 'tags' => true, 'excerpt' => true ),
							'more_url'  => $news_feed_tabs[0]['url'],
						) );
						?>
					</div>
				</div>

				<aside>
					<?php
					// Newest posts from the category chosen for the sidebar
					// (Customizer > تنظیمات تکراتو > باکس‌های صفحه اصلی).
					$side_term  = techrato_box_term( 'box_sidebar_cat', array( 'mobile', 'mobiles', 'phone', 'smartphone' ) );
					$side_id    = $side_term ? (int) $side_term->term_id : 0;
					$side_query = new WP_Query( array(
						'posts_per_page'      => 3,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'cat'                 => $side_id ? $side_id : '',
					) );
					?>
					<div class="widget">
						<h3 class="widget-title"><?php echo esc_html( $side_term ? $side_term->name : __( 'موبایل', 'techrato' ) ); ?></h3>
						<?php
						get_template_part( 'template-parts/feed-box', null, array(
							'query'    => $side_query,
							'term_id'  => $side_id,
							'card'     => 'horizontal',
							'more_url' => $side_term ? get_category_link( $side_id ) : techrato_more_url( 'more_link_learning', $side_query ),
						) );
						?>
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
				<?php
				$popular = techrato_query_popular( 3, 7, $shown_ids );
				$shown_ids = array_merge( $shown_ids, wp_list_pluck( $popular->posts, 'ID' ) );

				get_template_part( 'template-parts/feed-box', null, array(
					'query'      => $popular,
					'term_id'    => 0,
					'card'       => 'horizontal',
					'list_class' => 'most-viewed-grid',
					'days'       => 7,
					'sort'       => 'views',
					'more_url'   => techrato_more_url( 'more_link_popular', $popular ),
				) );
				?>
			</div>
		</section>

		<?php get_template_part( 'template-parts/social-banner' ); ?>

		<!-- ===== IRAN TECH NEWS ===== -->
		<?php $iran_feature = techrato_query_by_slug( 'technology', 1, $shown_ids ); ?>
		<section class="block">
			<div class="section-title">
				<h2><?php esc_html_e( 'آخرین اخبار فناوری ایران', 'techrato' ); ?></h2>
				<span class="bar"></span>
				<a class="more" href="<?php echo esc_url( techrato_more_url( 'more_link_iran', $iran_feature, 'technology' ) ); ?>">« <?php esc_html_e( 'محتوای بیشتر', 'techrato' ); ?></a>
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
