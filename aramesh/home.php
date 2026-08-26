<?php
/**
 * مجله / همه مقالات (Page 12) — صفحه مطالب.
 *
 * @package Aramesh
 */

get_header();

$cats = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => true, 'number' => 8 ) );
?>
<section class="hero pb-3">
	<div class="container">
		<div class="text-center" style="max-width:720px;margin-inline:auto">
			<span class="eyebrow"><?php esc_html_e( 'مجله', 'aramesh' ); ?></span>
			<h1 class="mb-2"><?php esc_html_e( 'مقالات روان‌شناسی و رشد فردی', 'aramesh' ); ?></h1>
			<p class="lead-soft"><?php esc_html_e( 'مطالبی کاربردی برای شناخت بهتر خود و بهبود کیفیت زندگی.', 'aramesh' ); ?></p>
			<div class="mt-3" style="max-width:520px;margin-inline:auto"><?php get_search_form(); ?></div>
		</div>
	</div>
</section>

<?php if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) : ?>
<section class="pb-3">
	<div class="container">
		<div class="chip-row justify-content-center">
			<a class="chip is-active" href="<?php echo esc_url( aramesh_page_url( 'blog' ) ); ?>"><?php esc_html_e( 'همه', 'aramesh' ); ?></a>
			<?php foreach ( $cats as $cat ) : ?>
				<a class="chip" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section-sm pt-0">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-8">
				<?php if ( have_posts() ) : ?>
					<?php
					$first = true;
					while ( have_posts() ) :
						the_post();
						if ( $first && ! is_paged() ) :
							$first = false;
							// Editor pick / featured بزرگ.
							?>
							<article class="a-card hover-lift mb-4">
								<div class="row g-0">
									<div class="col-md-6">
										<a class="a-card__media h-100 d-block" href="<?php the_permalink(); ?>" style="min-height:240px">
											<?php echo has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'aramesh-cover', array( 'style' => 'width:100%;height:100%;object-fit:cover' ) ) : '<span class="ph-media w-100 h-100">' . aramesh_icon( 'book', 40 ) . '</span>'; ?>
										</a>
									</div>
									<div class="col-md-6">
										<div class="a-card__body h-100 justify-content-center p-4">
											<span class="badge-soft badge-accent align-self-start"><?php esc_html_e( 'پیشنهاد سردبیر', 'aramesh' ); ?></span>
											<h2 class="h4 m-0"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
											<p class="a-card__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?></p>
											<div class="a-card__meta">
												<span><?php echo aramesh_icon( 'calendar', 16 ); ?> <?php echo esc_html( get_the_date() ); ?></span>
												<span><?php echo aramesh_icon( 'clock', 16 ); ?> <?php printf( esc_html__( '%d دقیقه', 'aramesh' ), aramesh_reading_time() ); ?></span>
											</div>
										</div>
									</div>
								</div>
							</article>
							<?php
						else :
							echo '<div class="mb-4">';
							// از کارت شبکه‌ای برای بقیه استفاده می‌کنیم اما تک‌ستونه.
							?>
							<article class="a-card hover-lift">
								<div class="row g-0">
									<div class="col-sm-5">
										<a class="a-card__media h-100 d-block" href="<?php the_permalink(); ?>" style="min-height:180px">
											<?php echo has_post_thumbnail() ? get_the_post_thumbnail( get_the_ID(), 'aramesh-card', array( 'style' => 'width:100%;height:100%;object-fit:cover' ) ) : '<span class="ph-media w-100 h-100">' . aramesh_icon( 'book', 32 ) . '</span>'; ?>
										</a>
									</div>
									<div class="col-sm-7">
										<div class="a-card__body">
											<h3 class="a-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
											<p class="a-card__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?></p>
											<div class="a-card__meta">
												<span><?php echo aramesh_icon( 'calendar', 16 ); ?> <?php echo esc_html( get_the_date() ); ?></span>
												<span><?php echo aramesh_icon( 'clock', 16 ); ?> <?php printf( esc_html__( '%d دقیقه', 'aramesh' ), aramesh_reading_time() ); ?></span>
											</div>
										</div>
									</div>
								</div>
							</article>
							<?php
							echo '</div>';
						endif;
					endwhile;
					?>
					<?php aramesh_pagination(); ?>
				<?php else : ?>
					<div class="card-soft p-5 text-center"><h2 class="h5"><?php esc_html_e( 'هنوز مقاله‌ای منتشر نشده است.', 'aramesh' ); ?></h2></div>
				<?php endif; ?>
			</div>
			<aside class="col-lg-4"><?php get_sidebar(); ?></aside>
		</div>
	</div>
</section>

<?php
get_footer();
