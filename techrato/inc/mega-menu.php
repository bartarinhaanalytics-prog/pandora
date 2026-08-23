<?php
/**
 * Desktop mega menu.
 *
 * Each top-level menu item with children drops a panel holding its
 * sub-categories on one row and the newest posts of that category on the row
 * below. The post row is rendered by a walker rather than by JavaScript, so
 * it is in the page WordPress caches and costs the visitor nothing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * How many posts each panel shows.
 *
 * @return int
 */
function techrato_mega_post_count() {
	return (int) apply_filters( 'techrato_mega_post_count', 5 );
}

/**
 * The category a menu item points at.
 *
 * Category items carry it directly. Custom links are matched against their URL,
 * which is what most menus built by hand actually contain.
 *
 * @param WP_Post $item Nav menu item.
 * @return WP_Term|null
 */
function techrato_mega_item_term( $item ) {
	if ( isset( $item->object, $item->object_id ) && 'category' === $item->object ) {
		$term = get_term( (int) $item->object_id, 'category' );
		if ( $term instanceof WP_Term ) {
			return $term;
		}
	}

	if ( empty( $item->url ) ) {
		return null;
	}

	$path = wp_parse_url( $item->url, PHP_URL_PATH );
	if ( ! $path ) {
		return null;
	}

	$segments = array_values( array_filter( explode( '/', trim( $path, '/' ) ) ) );
	if ( ! $segments ) {
		return null;
	}

	$term = get_category_by_slug( end( $segments ) );

	return $term ? $term : null;
}

/**
 * The newest posts of a category, rendered as the panel's bottom row.
 *
 * Kept in a short transient: without it every page load would run one query
 * per top-level menu item, which is wasted work on a site this size.
 *
 * @param WP_Term $term Category.
 * @return string
 */
function techrato_mega_posts_row( $term ) {
	$count = techrato_mega_post_count();
	$key   = 'techrato_mega_' . $term->term_id . '_' . $count;

	$cached = get_transient( $key );
	if ( false !== $cached ) {
		return $cached;
	}

	$query = new WP_Query( array(
		'cat'                    => (int) $term->term_id,
		'posts_per_page'         => $count,
		'post_status'            => 'publish',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
	) );

	$html = '';

	if ( $query->have_posts() ) {
		$html .= '<div class="mega-posts">';
		while ( $query->have_posts() ) {
			$query->the_post();

			$thumb = has_post_thumbnail()
				? get_the_post_thumbnail( null, 'techrato-list', array( 'loading' => 'lazy', 'alt' => '' ) )
				: '<img src="' . esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ) . '" alt="">';

			$html .= sprintf(
				'<a class="mega-post" href="%s"><span class="mega-post-thumb">%s</span><span class="mega-post-title">%s</span></a>',
				esc_url( get_permalink() ),
				$thumb,
				esc_html( wp_trim_words( get_the_title(), 9, '…' ) )
			);
		}
		$html .= '</div>';
		wp_reset_postdata();
	}

	set_transient( $key, $html, 15 * MINUTE_IN_SECONDS );

	return $html;
}

/**
 * Publishing or editing a post makes the cached rows stale.
 *
 * @param int $post_id Post being saved.
 */
function techrato_mega_flush( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || 'post' !== get_post_type( $post_id ) ) {
		return;
	}

	$count = techrato_mega_post_count();
	foreach ( get_the_category( $post_id ) as $term ) {
		delete_transient( 'techrato_mega_' . $term->term_id . '_' . $count );
		if ( $term->parent ) {
			delete_transient( 'techrato_mega_' . $term->parent . '_' . $count );
		}
	}
}
add_action( 'save_post', 'techrato_mega_flush' );
add_action( 'deleted_post', 'techrato_mega_flush' );

/**
 * Wraps each sub-menu in a panel and appends the category's newest posts.
 */
class Techrato_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * The depth-0 item currently being rendered, so start_lvl/end_lvl know
	 * which category the panel belongs to.
	 *
	 * @var WP_Post|null
	 */
	protected $top_item = null;

	/**
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 * @param int      $id     Menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( 0 === $depth ) {
			$this->top_item = $item;
		}
		parent::start_el( $output, $item, $depth, $args, $id );
	}

	/**
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '<div class="mega-panel">';
		}
		parent::start_lvl( $output, $depth, $args );
	}

	/**
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		parent::end_lvl( $output, $depth, $args );

		if ( 0 !== $depth ) {
			return;
		}

		if ( $this->top_item ) {
			$term = techrato_mega_item_term( $this->top_item );
			if ( $term ) {
				$output .= techrato_mega_posts_row( $term );
			}
		}

		$output .= '</div>';
	}
}
