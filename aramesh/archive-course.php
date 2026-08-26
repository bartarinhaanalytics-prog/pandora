<?php
/**
 * آرشیو دوره‌ها (Page 3) — همچنین برای taxonomy course_category استفاده می‌شود.
 *
 * @package Aramesh
 */

get_header();

$is_tax    = is_tax( 'course_category' ) || is_tax( 'topic' );
$active_cat = isset( $_GET['ccat'] ) ? sanitize_title( wp_unslash( $_GET['ccat'] ) ) : '';
$active_sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'newest';
$cats = get_terms( array( 'taxonomy' => 'course_category', 'hide_empty' => false ) );
?>

<section class="hero pb-4">
	<div class="container">
		<?php aramesh_breadcrumb(); ?>
		<div class="text-center mt-2" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'همه دوره‌ها', 'aramesh' ); ?></span>
			<h1 class="mb-2">
				<?php
				if ( $is_tax ) {
					single_term_title();
				} else {
					esc_html_e( 'دوره‌های تخصصی روان‌شناسی', 'aramesh' );
				}
				?>
			</h1>
			<p class="lead-soft">
				<?php
				if ( $is_tax && term_description() ) {
					echo wp_kses_post( term_description() );
				} else {
					esc_html_e( 'مسیر یادگیری خود را از میان دوره‌های علمی و کاربردی انتخاب کنید.', 'aramesh' );
				}
				?>
			</p>
			<form class="d-flex gap-2 mt-3" style="max-width:520px;margin-inline:auto" method="get" action="<?php echo esc_url( aramesh_courses_url() ); ?>">
				<input type="search" name="s" class="form-control" placeholder="<?php esc_attr_e( 'جستجوی دوره…', 'aramesh' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<input type="hidden" name="post_type" value="course">
				<button class="btn btn-primary flex-shrink-0"><?php echo aramesh_icon( 'search', 20 ); ?></button>
			</form>
		</div>
	</div>
</section>

<?php if ( ! $is_tax && ! is_wp_error( $cats ) && ! empty( $cats ) ) : ?>
<section class="pb-3">
	<div class="container">
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
			<div class="chip-row">
				<a class="chip <?php echo '' === $active_cat ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'sort' => $active_sort ), aramesh_courses_url() ) ); ?>"><?php esc_html_e( 'همه', 'aramesh' ); ?></a>
				<?php foreach ( $cats as $cat ) : ?>
					<a class="chip <?php echo $active_cat === $cat->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'ccat' => $cat->slug, 'sort' => $active_sort ), aramesh_courses_url() ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
				<?php endforeach; ?>
			</div>
			<form method="get" action="<?php echo esc_url( aramesh_courses_url() ); ?>" class="d-flex align-items-center gap-2">
				<?php if ( $active_cat ) : ?><input type="hidden" name="ccat" value="<?php echo esc_attr( $active_cat ); ?>"><?php endif; ?>
				<label class="text-secondary small m-0" for="sort"><?php esc_html_e( 'مرتب‌سازی:', 'aramesh' ); ?></label>
				<select name="sort" id="sort" class="form-select" style="width:auto" onchange="this.form.submit()">
					<option value="newest" <?php selected( $active_sort, 'newest' ); ?>><?php esc_html_e( 'جدیدترین', 'aramesh' ); ?></option>
					<option value="popular" <?php selected( $active_sort, 'popular' ); ?>><?php esc_html_e( 'محبوب‌ترین', 'aramesh' ); ?></option>
					<option value="price-asc" <?php selected( $active_sort, 'price-asc' ); ?>><?php esc_html_e( 'ارزان‌ترین', 'aramesh' ); ?></option>
					<option value="price-desc" <?php selected( $active_sort, 'price-desc' ); ?>><?php esc_html_e( 'گران‌ترین', 'aramesh' ); ?></option>
				</select>
			</form>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section-sm pt-0">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="row g-4">
				<?php
				while ( have_posts() ) :
					the_post();
					echo '<div class="col-md-6 col-lg-4">';
					aramesh_render_course_card( get_the_ID() );
					echo '</div>';
				endwhile;
				?>
			</div>
			<?php aramesh_pagination(); ?>
		<?php else : ?>
			<div class="card-soft p-5 text-center">
				<div class="text-primary-dark mb-2"><?php echo aramesh_icon( 'search', 36 ); ?></div>
				<h2 class="h4"><?php esc_html_e( 'دوره‌ای یافت نشد.', 'aramesh' ); ?></h2>
				<p class="text-secondary"><?php esc_html_e( 'فیلترها را تغییر دهید یا همه دوره‌ها را ببینید.', 'aramesh' ); ?></p>
				<a class="btn btn-primary" href="<?php echo esc_url( aramesh_courses_url() ); ?>"><?php esc_html_e( 'همه دوره‌ها', 'aramesh' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="section-sm pt-0 pb-5">
	<div class="container">
		<div class="cta-band">
			<div class="row align-items-center g-3">
				<div class="col-lg-9">
					<h2 class="h4 m-0"><?php esc_html_e( 'در انتخاب دوره مطمئن نیستید؟', 'aramesh' ); ?></h2>
					<p class="m-0" style="opacity:.9"><?php esc_html_e( 'با ما در تماس باشید تا مناسب‌ترین مسیر را پیشنهاد دهیم.', 'aramesh' ); ?></p>
				</div>
				<div class="col-lg-3 text-lg-start">
					<a class="btn btn-primary" href="<?php echo esc_url( aramesh_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'مشاوره انتخاب دوره', 'aramesh' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
