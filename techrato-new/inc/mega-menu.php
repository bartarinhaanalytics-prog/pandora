<?php
/**
 * Desktop mega menu.
 *
 * Each top-level menu item that has children drops a panel holding the
 * category's name and description, its sub-categories, and its newest post.
 * Everything is rendered in PHP rather than fetched by JavaScript, so it sits
 * inside the page WordPress caches and costs the visitor nothing.
 *
 * The markup deliberately mirrors the design: <div class="nav-item"> wrappers
 * instead of a <ul>, because the stylesheet is written against that shape.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The category a menu item points at.
 *
 * Category items carry it directly. Custom links are matched against the last
 * segment of their URL, which is what most hand-built menus actually contain.
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
 * The newest post of a category, as the panel's featured card.
 *
 * Kept in a short transient: without it every page load would run one query per
 * top-level menu item, which is wasted work on a site this size.
 *
 * @param WP_Term $term Category.
 * @return string
 */
function techrato_mega_feature( $term ) {
	$key    = 'techrato_megafeat_' . $term->term_id;
	$cached = get_transient( $key );
	if ( false !== $cached ) {
		return $cached;
	}

	$query = new WP_Query( array(
		'cat'                    => (int) $term->term_id,
		'posts_per_page'         => 1,
		'post_status'            => 'publish',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
	) );

	$html = '';

	if ( $query->have_posts() ) {
		$query->the_post();

		$image = has_post_thumbnail()
			? get_the_post_thumbnail( null, 'techrato-card', array( 'loading' => 'lazy', 'alt' => '' ) )
			: '<img src="' . esc_url( TECHRATO_URI . '/assets/images/placeholder.svg' ) . '" alt="">';

		$cats  = get_the_category();
		$label = isset( $cats[0] ) ? $cats[0]->name : $term->name;

		$html = sprintf(
			'<a class="mega-feature" href="%s">%s<span>%s</span><h4>%s</h4></a>',
			esc_url( get_permalink() ),
			$image,
			esc_html( $label ),
			esc_html( wp_trim_words( get_the_title(), 14, '…' ) )
		);

		wp_reset_postdata();
	}

	set_transient( $key, $html, 15 * MINUTE_IN_SECONDS );

	return $html;
}

/**
 * Publishing or editing a post makes the cached panel stale.
 *
 * @param int $post_id Post being saved.
 */
function techrato_mega_flush( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || 'post' !== get_post_type( $post_id ) ) {
		return;
	}

	foreach ( get_the_category( $post_id ) as $term ) {
		delete_transient( 'techrato_megafeat_' . $term->term_id );
		if ( $term->parent ) {
			delete_transient( 'techrato_megafeat_' . $term->parent );
		}
	}
}
add_action( 'save_post', 'techrato_mega_flush' );
add_action( 'deleted_post', 'techrato_mega_flush' );

/**
 * The chevron drawn next to a top-level item that opens a panel.
 *
 * @return string
 */
function techrato_mega_chevron() {
	return '<span class="nav-chevron" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M7 9.5l5 5 5-5"></path></svg></span>';
}

/**
 * Builds the navigation in the shape the stylesheet expects.
 */
class Techrato_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * The depth-0 item being rendered, so start_lvl/end_lvl know which
	 * category the panel belongs to.
	 *
	 * @var WP_Post|null
	 */
	protected $top_item = null;

	/**
	 * No <ul> wrappers: the design uses plain divs.
	 *
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth ) {
			return;
		}

		$term  = $this->top_item ? techrato_mega_item_term( $this->top_item ) : null;
		$name  = $term ? $term->name : ( $this->top_item ? $this->top_item->title : '' );
		$desc  = $term ? wp_strip_all_tags( term_description( $term->term_id, $term->taxonomy ) ) : '';

		$output .= '<div class="mega-menu"><div class="mega-menu__inner">';
		$output .= '<div class="mega-menu__title">';
		$output .= '<span>' . esc_html__( 'دسته‌بندی', 'techrato' ) . '</span>';
		$output .= '<h3>' . esc_html( $name ) . '</h3>';
		if ( $desc ) {
			$output .= '<p>' . esc_html( wp_trim_words( $desc, 24, '…' ) ) . '</p>';
		}
		$output .= '</div>';
		$output .= '<div class="mega-menu__links">';
	}

	/**
	 * @param string   $output Passed by reference.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth ) {
			return;
		}

		$output .= '</div>';

		if ( $this->top_item ) {
			$term = techrato_mega_item_term( $this->top_item );
			if ( $term ) {
				$output .= techrato_mega_feature( $term );
			}
		}

		$output .= '</div></div>';
	}

	/**
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 * @param int      $id     Menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$url   = ! empty( $item->url ) ? $item->url : '#';
		$title = $item->title;

		if ( 0 === $depth ) {
			$this->top_item = $item;

			$classes = array( 'nav-item' );
			$item_classes = isset( $item->classes ) ? (array) $item->classes : array();
			if ( in_array( 'menu-item-has-children', $item_classes, true ) ) {
				$classes[] = 'has-mega';
			}
			if ( ! empty( $item->current ) || ! empty( $item->current_item_ancestor ) ) {
				$classes[] = 'is-current';
			}

			$output .= '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">';
			$output .= '<a href="' . esc_url( $url ) . '">' . esc_html( $title );
			if ( in_array( 'has-mega', $classes, true ) ) {
				$output .= techrato_mega_chevron();
			}
			$output .= '</a>';

			return;
		}

		// Sub-categories are plain links inside the panel.
		$output .= '<a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a>';
	}

	/**
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div>';
		}
	}
}

/**
 * Footer columns are a stack of bare links, not a list, so the default walker's
 * <ul>/<li> wrappers would fight the stylesheet.
 */
class Techrato_Footer_Link_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}

	/**
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Menu args.
	 * @param int      $id     Menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( 0 !== $depth ) {
			return;
		}

		$output .= sprintf(
			'<a href="%s">%s</a>',
			esc_url( ! empty( $item->url ) ? $item->url : '#' ),
			esc_html( $item->title )
		);
	}
}
