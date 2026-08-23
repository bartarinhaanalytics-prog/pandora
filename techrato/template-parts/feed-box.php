<?php
/**
 * A box of posts that can switch category tabs and load more posts in place.
 *
 * Used by the homepage boxes and the category archive so both behave the same
 * way. Everything the browser needs sits in data attributes on the list, so
 * the JavaScript stays generic and several boxes can live on one page.
 *
 * $args:
 *   'tabs'       array  Tabs from techrato_archive_tabs().
 *   'query'      WP_Query  Posts for the first render.
 *   'term_id'    int    Category the box is showing (0 = every category).
 *   'card'       string  Card template slug: list-row, horizontal, vertical…
 *   'card_args'  array  Extra args passed to the card.
 *   'list_class' string Extra class on the list wrapper (e.g. a grid).
 *   'more_url'   string Where the more link goes when JavaScript is off.
 *   'days'       int    Restrict to posts from the last N days.
 *   'sort'       string 'date' or 'views'.
 *   'empty_text' string Notice shown when there is nothing to list.
 *   'ads'        string Ad slot key whose native ads are placed in the list.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$feed = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'tabs'       => array(),
		'query'      => null,
		'term_id'    => 0,
		'card'       => 'list-row',
		'card_args'  => array(),
		'list_class' => '',
		'more_url'   => '',
		'days'       => 0,
		'sort'       => 'date',
		'empty_text' => '',
		'push_url'   => false,
		'ads'        => '',
	)
);

$feed_query = $feed['query'];
if ( ! $feed_query instanceof WP_Query ) {
	return;
}

// Load-more must page with the same size as the first render, or page two
// would skip or repeat posts.
$feed_per   = (int) $feed_query->get( 'posts_per_page' );
$feed_max   = (int) $feed_query->max_num_pages;
$feed_has   = $feed_query->have_posts();
$feed_more  = $feed_has && $feed_max > 1 && $feed['more_url'];
$feed_uid   = wp_unique_id( 'feed-' );

// Native ads sit at fixed places in the first render only. Load-more appends
// posts underneath, so re-inserting them there would stack ads down the page.
$feed_ads = ( 'latest_native' === $feed['ads'] && function_exists( 'techrato_ads_latest_map' ) )
	? techrato_ads_latest_map()
	: array();
?>
<div class="js-feed feed-box"<?php echo $feed['push_url'] ? ' data-push-url="1"' : ''; ?>>

	<?php if ( $feed['tabs'] ) : ?>
		<div class="tabs js-archive-tabs" style="margin-bottom:16px;" role="tablist">
			<?php foreach ( $feed['tabs'] as $feed_tab ) : ?>
				<a href="<?php echo esc_url( $feed_tab['url'] ); ?>"
					data-term="<?php echo esc_attr( $feed_tab['term_id'] ); ?>"
					<?php echo ! empty( $feed_tab['current'] ) ? ' class="is-active" aria-current="page"' : ''; ?>><?php echo esc_html( $feed_tab['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="js-post-list <?php echo esc_attr( $feed['list_class'] ); ?>"
		id="<?php echo esc_attr( $feed_uid ); ?>"
		data-term="<?php echo esc_attr( $feed['term_id'] ); ?>"
		data-paged="1"
		data-max="<?php echo esc_attr( $feed_max ); ?>"
		data-per="<?php echo esc_attr( $feed_per ); ?>"
		data-card="<?php echo esc_attr( $feed['card'] ); ?>"
		data-days="<?php echo esc_attr( $feed['days'] ); ?>"
		data-sort="<?php echo esc_attr( $feed['sort'] ); ?>"
		aria-live="polite">
		<?php
		if ( $feed_has ) {
			$feed_slot = 0;
			while ( $feed_query->have_posts() ) {
				$feed_query->the_post();

				// The ad takes the slot it was sold, and the post that would
				// have been there moves down one — no article is lost.
				$feed_slot++;
				while ( isset( $feed_ads[ $feed_slot ] ) ) {
					techrato_ads_native_row( $feed_ads[ $feed_slot ] );
					unset( $feed_ads[ $feed_slot ] );
					$feed_slot++;
				}

				get_template_part( 'template-parts/card', $feed['card'], $feed['card_args'] );
			}
			wp_reset_postdata();

			// A short list still shows the ads that were sold for it.
			foreach ( $feed_ads as $feed_ad ) {
				techrato_ads_native_row( $feed_ad );
			}
		} else {
			techrato_empty_card_notice( $feed['empty_text'] ? $feed['empty_text'] : null );
		}
		?>
	</div>

	<?php if ( $feed_more ) : ?>
		<?php // A real link, so it still goes somewhere useful without JavaScript. ?>
		<a class="more-link js-load-more" href="<?php echo esc_url( $feed['more_url'] ); ?>"><?php esc_html_e( 'مشاهده مطالب بیشتر', 'techrato' ); ?></a>
	<?php endif; ?>

</div>
