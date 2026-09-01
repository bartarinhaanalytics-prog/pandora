<?php
/**
 * Author page: who they are on top, everything they have written below.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$techrato_author = get_queried_object();
$techrato_id     = $techrato_author instanceof WP_User ? (int) $techrato_author->ID : 0;
$techrato_bio    = $techrato_id ? get_the_author_meta( 'description', $techrato_id ) : '';
$techrato_site   = $techrato_id ? get_the_author_meta( 'url', $techrato_id ) : '';
$techrato_count  = $techrato_id ? (int) count_user_posts( $techrato_id, 'post' ) : 0;
?>

<main id="primary" class="author-page">

	<section class="category-intro">
		<div class="container">
			<?php techrato_breadcrumbs(); ?>

			<div class="author-hero">
				<div class="author-hero__avatar">
					<?php echo get_avatar( $techrato_id, 132 ); ?>
				</div>

				<div class="author-hero__body">
					<span class="eyebrow"><?php esc_html_e( 'نویسنده', 'techrato' ); ?></span>
					<h1><?php echo esc_html( get_the_author_meta( 'display_name', $techrato_id ) ); ?></h1>

					<?php if ( $techrato_bio ) : ?>
						<p class="author-hero__bio"><?php echo esc_html( $techrato_bio ); ?></p>
					<?php endif; ?>

					<div class="author-hero__facts">
						<span>
							<?php
							/* translators: %s: number of posts */
							printf( esc_html__( '%s مطلب منتشر شده', 'techrato' ), esc_html( number_format_i18n( $techrato_count ) ) );
							?>
						</span>
						<?php if ( $techrato_site ) : ?>
							<i></i>
							<a href="<?php echo esc_url( $techrato_site ); ?>" target="_blank" rel="noopener nofollow"><?php esc_html_e( 'وب‌سایت', 'techrato' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="category-content">
		<div class="container category-layout">
			<div class="category-main-column">
				<?php
				techrato_section_heading(
					__( 'نوشته‌های این نویسنده', 'techrato' ),
					__( 'آخرین مطالب', 'techrato' ),
					'',
					'',
					'category-latest-heading'
				);

				get_template_part( 'template-parts/feed-box', null, array(
					'query'      => $GLOBALS['wp_query'],
					'term_id'    => 0,
					'card'       => 'category-post',
					'list_class' => 'category-post-list',
					'more_url'   => '',
					'empty_text' => __( 'این نویسنده هنوز مطلبی منتشر نکرده است.', 'techrato' ),
					'push_url'   => true,
				) );
				?>
			</div>

			<?php get_template_part( 'template-parts/sidebar-category' ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/app-showcase' ); ?>
</main>

<?php get_footer(); ?>
