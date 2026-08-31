<?php
/**
 * Homepage.
 *
 * Every block is switched on or off from Customizer > صفحه نخست تکراتو, and
 * $shown_ids stops the same post appearing twice down the page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$shown_ids = array();
?>

<main id="primary">

	<!-- ===== HERO ===== -->
	<?php if ( techrato_home_shows( 'hero' ) ) : ?>
	<section class="hero-pattern hero-section">
		<div class="container">
			<div class="hero-heading">
				<span class="eyebrow"><?php echo esc_html( techrato_home_option( 'hero', 'eyebrow' ) ); ?></span>
				<h1><?php echo wp_kses_post( techrato_home_option( 'hero', 'headline' ) ); ?></h1>
				<p><?php echo esc_html( techrato_home_option( 'hero', 'subline' ) ); ?></p>
				<form class="search-box" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
						placeholder="<?php esc_attr_e( 'در تکراتو جستجو کنید...', 'techrato' ); ?>">
					<button type="submit"><?php esc_html_e( 'جستجو', 'techrato' ); ?></button>
				</form>
			</div>

			<?php
			// The lead card uses "تیتر اصلی صفحه نخست"; the stories beside it use
			// "نوشته شاخص". Both come from the checkboxes on the post editor.
			$hero_lead = techrato_query_by_flag( '_featured_one_post_tc', 1, $shown_ids );
			$shown_ids = array_merge( $shown_ids, wp_list_pluck( $hero_lead->posts, 'ID' ) );

			$hero_side = techrato_query_by_flag( '_featured_post_tc', techrato_home_option( 'hero', 'count' ), $shown_ids );
			$shown_ids = array_merge( $shown_ids, wp_list_pluck( $hero_side->posts, 'ID' ) );
			?>
			<div class="lead-grid">
				<?php
				if ( $hero_lead->have_posts() ) {
					while ( $hero_lead->have_posts() ) {
						$hero_lead->the_post();
						get_template_part( 'template-parts/card', 'feature' );
					}
					wp_reset_postdata();
				}
				?>
				<div class="side-stories">
					<?php
					if ( $hero_side->have_posts() ) {
						while ( $hero_side->have_posts() ) {
							$hero_side->the_post();
							get_template_part( 'template-parts/card', 'side-story' );
						}
						wp_reset_postdata();
					}
					?>
				</div>
			</div>

			<?php get_template_part( 'template-parts/trend-bar' ); ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- ===== AD: زیر هیرو ===== -->
	<?php if ( techrato_ads_has( 'home_top' ) ) : ?>
		<section class="section ad-section"><div class="container">
			<?php techrato_ads_render_banners( 'home_top' ); ?>
		</div></section>
	<?php endif; ?>

	<!-- ===== پیشنهادهای سردبیر ===== -->
	<?php if ( techrato_home_shows( 'editors' ) ) : ?>
	<?php
	$editors = techrato_query_by_flag( '_editor_suggestion_tc', techrato_home_option( 'editors', 'count' ), $shown_ids );
	if ( $editors->have_posts() ) :
		?>
	<section class="editor-section">
		<div class="container">
			<?php
			techrato_section_heading(
				__( 'انتخاب تحریریه', 'techrato' ),
				techrato_home_option( 'editors', 'title' ),
				techrato_more_url( 'more_link_editors', $editors ),
				__( 'مطالب بیشتر', 'techrato' ),
				'section-heading--dark'
			);

			$editor_index = 0;
			while ( $editors->have_posts() ) :
				$editors->the_post();
				$shown_ids[] = get_the_ID();

				if ( 0 === $editor_index ) :
					?>
					<div class="editor-feature">
						<article class="editor-feature__image">
							<?php echo techrato_thumb( 'techrato-hero' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div class="surface-notch surface-notch--dark">
								<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
							</div>
						</article>
						<div class="editor-feature__content">
							<?php $editor_cat = techrato_primary_category(); ?>
							<?php if ( $editor_cat ) : ?>
								<span class="category-pill orange-pill"><?php echo esc_html( $editor_cat ); ?></span>
							<?php endif; ?>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<span class="story-time"><?php echo esc_html( techrato_time_ago() ); ?></span>
						</div>
					</div>
					<div class="editor-mini-grid">
					<?php
				else :
					get_template_part( 'template-parts/card', 'editor-mini' );
				endif;

				$editor_index++;
			endwhile;
			wp_reset_postdata();

			if ( $editor_index > 1 ) {
				echo '</div>';
			}
			?>
		</div>
	</section>
	<?php endif; ?>
	<?php endif; ?>

	<!-- ===== دسته‌بندی‌ها ===== -->
	<?php if ( techrato_home_shows( 'quick' ) ) : ?>
		<?php get_template_part( 'template-parts/category-grid' ); ?>
	<?php endif; ?>

	<!-- ===== آخرین اخبار ===== -->
	<?php if ( techrato_home_shows( 'latest' ) ) : ?>
	<section class="section section-white latest-section">
		<div class="container">
			<?php
			$news_query = new WP_Query( array(
				'posts_per_page'      => techrato_home_option( 'latest', 'count' ),
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			) );

			techrato_section_heading(
				__( 'تازه منتشر شده', 'techrato' ),
				techrato_home_option( 'latest', 'title' ),
				techrato_more_url( 'more_link_latest', $news_query ),
				__( 'مشاهده همه', 'techrato' )
			);

			get_template_part( 'template-parts/feed-box', null, array(
				'query'      => $news_query,
				'term_id'    => 0,
				'card'       => 'news',
				'list_class' => 'latest-grid',
				'ads'        => 'latest_native',
				'more_url'   => techrato_more_url( 'more_link_latest', $news_query ),
			) );
			?>

			<?php // Two 300x120 banners. The design has no sidebar, so they run as
			// a row under the listing instead. ?>
			<?php techrato_ads_render_banners( 'sidebar' ); ?>
		</div>
	</section>
	<?php endif; ?>

	<!-- ===== شبکه‌های اجتماعی ===== -->
	<?php if ( techrato_home_shows( 'banner' ) ) : ?>
		<?php get_template_part( 'template-parts/social-banner' ); ?>
	<?php endif; ?>

	<!-- ===== فناوری ایران ===== -->
	<?php if ( techrato_home_shows( 'iran' ) ) : ?>
		<?php get_template_part( 'template-parts/iran-tech', null, array( 'exclude' => $shown_ids ) ); ?>
	<?php endif; ?>

	<!-- ===== موضوعات منتخب ===== -->
	<?php if ( techrato_home_shows( 'columns' ) ) : ?>
		<?php get_template_part( 'template-parts/topic-columns' ); ?>
	<?php endif; ?>

	<!-- ===== معرفی اپلیکیشن‌ها ===== -->
	<?php if ( techrato_home_shows( 'apps' ) ) : ?>
		<?php get_template_part( 'template-parts/app-showcase' ); ?>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
