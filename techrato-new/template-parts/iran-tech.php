<?php
/**
 * "آخرین اخبار فناوری ایران" — one lead story plus a short list.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_exclude = isset( $args['exclude'] ) ? (array) $args['exclude'] : array();
$techrato_term    = techrato_home_term( 'iran', 'cat', array( 'technology' ) );

$techrato_iran = new WP_Query( array(
	'posts_per_page'      => (int) techrato_home_option( 'iran', 'count' ) + 1,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'post__not_in'        => $techrato_exclude,
	'cat'                 => $techrato_term ? (int) $techrato_term->term_id : '',
) );

if ( ! $techrato_iran->have_posts() ) {
	return;
}
?>
<section class="iran-tech-section">
	<div class="container">
		<?php
		techrato_section_heading(
			__( 'فناوری در ایران', 'techrato' ),
			techrato_home_option( 'iran', 'title' ),
			$techrato_term ? get_category_link( $techrato_term->term_id ) : techrato_more_url( 'more_link_iran', $techrato_iran, 'technology' ),
			__( 'اخبار بیشتر', 'techrato' )
		);
		?>

		<div class="iran-tech-grid">
			<?php
			$techrato_index = 0;
			while ( $techrato_iran->have_posts() ) :
				$techrato_iran->the_post();

				if ( 0 === $techrato_index ) :
					?>
					<article class="iran-tech-lead">
						<div class="iran-tech-lead__image">
							<?php echo techrato_thumb( 'techrato-hero' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<div class="surface-notch surface-notch--white small-notch">
								<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
							</div>
						</div>
						<div class="iran-tech-lead__content">
							<span><?php echo esc_html( $techrato_term ? $techrato_term->name : techrato_primary_category() ); ?></span>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
							<small><?php echo esc_html( techrato_time_ago() ); ?></small>
						</div>
					</article>
					<div class="iran-tech-list">
					<?php
				else :
					get_template_part( 'template-parts/card', 'iran-item' );
				endif;

				$techrato_index++;
			endwhile;
			wp_reset_postdata();

			if ( $techrato_index > 1 ) {
				echo '</div>';
			}
			?>
		</div>
	</div>
</section>
