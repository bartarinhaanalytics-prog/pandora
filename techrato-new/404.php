<?php
/**
 * Nothing here page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main id="primary">
	<section class="hero-pattern hero-section">
		<div class="container">
			<div class="hero-heading">
				<span class="eyebrow"><?php esc_html_e( 'خطای ۴۰۴', 'techrato' ); ?></span>
				<h2><?php esc_html_e( 'این صفحه پیدا نشد', 'techrato' ); ?></h2>
				<p><?php esc_html_e( 'ممکن است آدرس اشتباه باشد یا مطلب حذف شده باشد. می‌توانید جستجو کنید یا سری به تازه‌ترین مطالب بزنید.', 'techrato' ); ?></p>
				<form class="search-box" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="search" name="s" placeholder="<?php esc_attr_e( 'در تکراتو جستجو کنید...', 'techrato' ); ?>">
					<button type="submit"><?php esc_html_e( 'جستجو', 'techrato' ); ?></button>
				</form>
			</div>
		</div>
	</section>

	<?php
	$techrato_recent = new WP_Query( array( 'posts_per_page' => 4, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
	if ( $techrato_recent->have_posts() ) :
		?>
		<section class="section section-white latest-section">
			<div class="container">
				<?php techrato_section_heading( __( 'تازه منتشر شده', 'techrato' ), __( 'آخرین اخبار', 'techrato' ) ); ?>
				<div class="latest-grid">
					<?php while ( $techrato_recent->have_posts() ) : $techrato_recent->the_post(); ?>
						<?php get_template_part( 'template-parts/card', 'news' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
