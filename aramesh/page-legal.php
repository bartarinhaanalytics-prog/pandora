<?php
/**
 * Template Name: قوانین و حریم خصوصی
 * صفحه ۱۷ — قوانین، حریم خصوصی، شرایط خرید.
 *
 * @package Aramesh
 */

get_header();

$tabs = apply_filters(
	'aramesh_legal_tabs',
	array(
		'terms'   => array( __( 'شرایط استفاده', 'aramesh' ), __( 'با استفاده از این سایت، شرایط زیر را می‌پذیرید. این متن نمونه است و باید با متن حقوقی واقعی جایگزین شود.', 'aramesh' ) ),
		'privacy' => array( __( 'حریم خصوصی', 'aramesh' ), __( 'اطلاعات شما تنها برای ارائه خدمات و احراز هویت استفاده می‌شود. این متن نمونه است.', 'aramesh' ) ),
		'refund'  => array( __( 'بازگشت وجه', 'aramesh' ), __( 'به دلیل ماهیت دیجیتال محتوا، شرایط بازگشت وجه مطابق قوانین اعلام‌شده است. این متن نمونه است.', 'aramesh' ) ),
		'content' => array( __( 'حقوق محتوای آموزشی', 'aramesh' ), __( 'تمام محتوای آموزشی متعلق به مالک سایت است و بازنشر آن مجاز نیست. این متن نمونه است.', 'aramesh' ) ),
	)
);

$page_content = get_the_content();
?>
<section class="hero pb-3">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center mt-2" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'اسناد', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php the_title(); ?></h1>
			<p class="text-secondary small"><?php printf( esc_html__( 'آخرین به‌روزرسانی: %s', 'aramesh' ), esc_html( get_the_modified_date() ) ); ?></p>
		</div>
	</div>
</section>

<section class="section-sm pt-2">
	<div class="container" style="max-width:900px">
		<?php if ( trim( wp_strip_all_tags( $page_content ) ) ) : ?>
			<div class="article-body mx-auto">
				<?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
			</div>
		<?php else : ?>
			<div class="row g-4">
				<div class="col-lg-4">
					<div class="card-soft p-3">
						<nav class="account-nav">
							<?php $i = 0; foreach ( $tabs as $key => $tab ) : $i++; ?>
								<a href="#legal-<?php echo esc_attr( $key ); ?>" class="<?php echo 1 === $i ? 'is-active' : ''; ?>"><?php echo esc_html( $tab[0] ); ?></a>
							<?php endforeach; ?>
						</nav>
					</div>
				</div>
				<div class="col-lg-8">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<div id="legal-<?php echo esc_attr( $key ); ?>" class="card-soft p-4 p-md-5 mb-4">
							<h2 class="h4 mb-3"><?php echo esc_html( $tab[0] ); ?></h2>
							<div class="article-body"><?php echo wpautop( esc_html( $tab[1] ) ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
