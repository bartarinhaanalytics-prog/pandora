<?php
/**
 * "موضوعات منتخب" — three columns, each a banner story plus a short list.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$techrato_columns = array(
	array( techrato_home_option( 'columns', 'title1' ), techrato_home_term( 'columns', 'cat1', array( 'business' ) ) ),
	array( techrato_home_option( 'columns', 'title2' ), techrato_home_term( 'columns', 'cat2', array( 'car' ) ) ),
	array( techrato_home_option( 'columns', 'title3' ), techrato_home_term( 'columns', 'cat3', array( 'ai' ) ) ),
);

$techrato_count = (int) techrato_home_option( 'columns', 'count' );
?>
<section class="topic-columns-section">
	<div class="container">
		<?php techrato_section_heading( __( 'بیشتر از تکراتو', 'techrato' ), __( 'موضوعات منتخب', 'techrato' ) ); ?>

		<div class="topic-columns">
			<?php foreach ( $techrato_columns as $techrato_column ) : ?>
				<?php
				list( $techrato_label, $techrato_term ) = $techrato_column;

				$techrato_query = new WP_Query( array(
					'posts_per_page'      => $techrato_count,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'cat'                 => $techrato_term ? (int) $techrato_term->term_id : '',
				) );

				if ( ! $techrato_query->have_posts() ) {
					continue;
				}
				?>
				<section class="topic-column-card">
					<h3 class="topic-column-title"><?php echo esc_html( $techrato_label ); ?></h3>
					<?php
					$techrato_i = 0;
					while ( $techrato_query->have_posts() ) :
						$techrato_query->the_post();

						if ( 0 === $techrato_i ) :
							?>
							<article class="topic-banner">
								<?php echo techrato_thumb( 'techrato-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<div class="topic-banner__overlay"></div>
								<div class="topic-banner__content">
									<span><?php echo esc_html( $techrato_term ? $techrato_term->name : techrato_primary_category() ); ?></span>
									<h4><?php the_title(); ?></h4>
								</div>
								<div class="topic-notch">
									<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"><?php echo techrato_arrow_icon(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
								</div>
							</article>
							<div class="topic-news-list">
							<?php
						else :
							?>
							<a href="<?php the_permalink(); ?>">
								<?php echo str_replace( '<img', '<img class="topic-news-thumb"', techrato_thumb( 'techrato-thumb' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<b><?php echo esc_html( wp_trim_words( get_the_title(), 12, '…' ) ); ?></b>
							</a>
							<?php
						endif;

						$techrato_i++;
					endwhile;
					wp_reset_postdata();

					if ( $techrato_i > 1 ) {
						echo '</div>';
					}
					?>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
</section>
