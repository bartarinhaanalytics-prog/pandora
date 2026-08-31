<?php
/**
 * SEO output: meta description, canonical, Open Graph / Twitter cards and
 * schema.org JSON-LD.
 *
 * The whole file stands down the moment a real SEO plugin is active, so
 * re-enabling Yoast or Rank Math never produces two of every tag.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * True when an SEO plugin is already emitting these tags.
 */
function techrato_seo_handled_by_plugin() {
	$handled = defined( 'WPSEO_VERSION' )        // Yoast SEO
		|| defined( 'RANK_MATH_VERSION' )        // Rank Math
		|| defined( 'AIOSEO_VERSION' )           // All in One SEO
		|| defined( 'SEOPRESS_VERSION' )         // SEOPress
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' )
		|| defined( 'SLIM_SEO_VERSION' );

	return (bool) apply_filters( 'techrato_seo_handled_by_plugin', $handled );
}

/**
 * Canonical URL of the page being viewed.
 */
function techrato_seo_url() {
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_category() || is_tag() || is_tax() ) {
		$link = get_term_link( get_queried_object() );
		return is_wp_error( $link ) ? home_url( '/' ) : $link;
	}
	if ( is_search() ) {
		return get_search_link();
	}
	if ( is_author() ) {
		return get_author_posts_url( (int) get_queried_object_id() );
	}
	if ( is_year() ) {
		return get_year_link( (int) get_query_var( 'year' ) );
	}

	return home_url( '/' );
}

/**
 * A description for the current page, trimmed to a length search engines
 * actually display.
 */
function techrato_seo_description() {
	$text = '';

	if ( is_singular() ) {
		$text = get_the_excerpt();
		if ( ! $text ) {
			$text = get_post_field( 'post_content', get_queried_object_id() );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$text = ( $term instanceof WP_Term && ! empty( $term->description ) ) ? $term->description : '';
		if ( ! $text && $term instanceof WP_Term ) {
			/* translators: 1: category name 2: site name */
			$text = sprintf(
				__( 'جدیدترین اخبار، بررسی‌ها و راهنمای خرید %1$s در %2$s.', 'techrato' ),
				$term->name,
				get_bloginfo( 'name' )
			);
		}
	} elseif ( is_search() ) {
		/* translators: %s: search term */
		$text = sprintf( __( 'نتایج جستجو برای «%s» در تکراتو.', 'techrato' ), get_search_query() );
	} else {
		// Home page: the description set in the Customizer wins, then the
		// site tagline. Never let it fall through to nothing — a homepage with
		// no description is the one search engines write themselves.
		$text = (string) get_theme_mod( 'seo_home_description', '' );
		if ( ! $text ) {
			$text = (string) get_bloginfo( 'description' );
		}
		if ( ! $text ) {
			/* translators: %s: site name */
			$text = sprintf(
				__( '%s؛ جدیدترین اخبار تکنولوژی، بررسی گوشی و لپ‌تاپ، راهنمای خرید و آموزش.', 'techrato' ),
				get_bloginfo( 'name' )
			);
		}
	}

	$text = wp_strip_all_tags( strip_shortcodes( (string) $text ), true );
	$text = preg_replace( '/\s+/u', ' ', $text );
	$text = trim( (string) $text );

	if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) > 158 ) {
		$text = rtrim( mb_substr( $text, 0, 155 ) ) . '…';
	}

	return apply_filters( 'techrato_seo_description', $text );
}

/**
 * Best sharing image for the current page.
 */
function techrato_seo_image() {
	$url = '';

	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $src ) {
			return array( $src[0], (int) $src[1], (int) $src[2] );
		}
	}

	if ( ( is_category() || is_tag() || is_tax() ) && function_exists( 'techrato_term_image' ) ) {
		$html = techrato_term_image( null, 'full' );
		if ( ! $html && function_exists( 'techrato_extract_first_image' ) ) {
			$term = get_queried_object();
			if ( $term instanceof WP_Term && ! empty( $term->description ) ) {
				list( $html ) = techrato_extract_first_image( $term->description );
			}
		}
		if ( $html && preg_match( '#src=["\']([^"\']+)["\']#i', $html, $m ) ) {
			$url = $m[1];
		}
	}

	if ( ! $url ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$src = wp_get_attachment_image_src( $logo_id, 'full' );
			if ( $src ) {
				return array( $src[0], (int) $src[1], (int) $src[2] );
			}
		}
	}

	$url = apply_filters( 'techrato_seo_image', $url );

	return $url ? array( $url, 0, 0 ) : array( '', 0, 0 );
}

/**
 * Title used in sharing cards — the page's own name, without the site suffix
 * that the <title> tag carries.
 */
function techrato_seo_title() {
	if ( is_singular() ) {
		return get_the_title();
	}
	if ( is_category() || is_tag() || is_tax() ) {
		return single_term_title( '', false );
	}
	if ( is_search() ) {
		/* translators: %s: search term */
		return sprintf( __( 'نتایج جستجو برای «%s»', 'techrato' ), get_search_query() );
	}

	return get_bloginfo( 'name' );
}

/**
 * Meta description, canonical, robots and pagination hints.
 */
function techrato_seo_meta() {
	if ( techrato_seo_handled_by_plugin() ) {
		return;
	}

	$description = techrato_seo_description();
	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	// Search results and 404s carry no value in the index.
	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	} else {
		echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n";
	}

	// Core adds the canonical on singular views only.
	if ( ! is_singular() && ! is_search() && ! is_404() ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( techrato_seo_url() ) );
	}

	if ( is_archive() || is_home() ) {
		$prev = get_previous_posts_page_link();
		$next = get_next_posts_page_link();
		$paged = max( 1, (int) get_query_var( 'paged' ) );
		if ( $paged > 1 && $prev ) {
			printf( '<link rel="prev" href="%s">' . "\n", esc_url( $prev ) );
		}
		if ( $paged < (int) $GLOBALS['wp_query']->max_num_pages && $next ) {
			printf( '<link rel="next" href="%s">' . "\n", esc_url( $next ) );
		}
	}
}
add_action( 'wp_head', 'techrato_seo_meta', 2 );

/**
 * Open Graph and Twitter card tags — what Telegram, Twitter and messaging
 * apps read when someone shares a link.
 */
function techrato_seo_social_tags() {
	if ( techrato_seo_handled_by_plugin() ) {
		return;
	}

	$title       = techrato_seo_title();
	$description = techrato_seo_description();
	$url         = techrato_seo_url();
	list( $image, $width, $height ) = techrato_seo_image();

	$tags = array(
		'og:locale'    => get_locale(),
		'og:site_name' => get_bloginfo( 'name' ),
		'og:type'      => is_singular( 'post' ) ? 'article' : 'website',
		'og:title'     => $title,
		'og:url'       => $url,
	);
	if ( $description ) {
		$tags['og:description'] = $description;
	}
	if ( $image ) {
		$tags['og:image'] = $image;
		if ( $width && $height ) {
			$tags['og:image:width']  = (string) $width;
			$tags['og:image:height'] = (string) $height;
		}
	}

	if ( is_singular( 'post' ) ) {
		$tags['article:published_time'] = get_the_date( DATE_W3C );
		$tags['article:modified_time']  = get_the_modified_date( DATE_W3C );
		$categories = get_the_category();
		if ( $categories ) {
			$tags['article:section'] = $categories[0]->name;
		}
	}

	foreach ( $tags as $property => $content ) {
		printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
	}

	if ( is_singular( 'post' ) ) {
		foreach ( (array) get_the_tags() as $tag ) {
			if ( isset( $tag->name ) ) {
				printf( '<meta property="article:tag" content="%s">' . "\n", esc_attr( $tag->name ) );
			}
		}
	}

	$card = array(
		'twitter:card'  => $image ? 'summary_large_image' : 'summary',
		'twitter:title' => $title,
	);
	if ( $description ) {
		$card['twitter:description'] = $description;
	}
	if ( $image ) {
		$card['twitter:image'] = $image;
	}

	foreach ( $card as $name => $content ) {
		printf( '<meta name="%s" content="%s">' . "\n", esc_attr( $name ), esc_attr( $content ) );
	}
}
add_action( 'wp_head', 'techrato_seo_social_tags', 3 );

/**
 * The breadcrumb trail as data, shared by the visible breadcrumb and the
 * BreadcrumbList schema so the two can never disagree.
 *
 * @return array List of array( name, url ); the last item is the current page.
 */
function techrato_breadcrumb_trail() {
	$trail = array(
		array(
			'name' => __( 'خانه', 'techrato' ),
			'url'  => home_url( '/' ),
		),
	);

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			if ( ! empty( $term->parent ) ) {
				$parent = get_term( $term->parent, $term->taxonomy );
				if ( $parent instanceof WP_Term ) {
					$trail[] = array( 'name' => $parent->name, 'url' => get_term_link( $parent ) );
				}
			}
			$trail[] = array( 'name' => $term->name, 'url' => get_term_link( $term ) );
		}
	} elseif ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		if ( isset( $categories[0] ) ) {
			$trail[] = array( 'name' => $categories[0]->name, 'url' => get_category_link( $categories[0]->term_id ) );
		}
		$trail[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_page() ) {
		$trail[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_search() ) {
		$trail[] = array( 'name' => __( 'نتایج جستجو', 'techrato' ), 'url' => get_search_link() );
	}

	return $trail;
}

/**
 * schema.org JSON-LD. One @graph so the pieces reference each other, which is
 * what Google expects for a news site.
 */
function techrato_seo_schema() {
	if ( techrato_seo_handled_by_plugin() ) {
		return;
	}

	$home      = home_url( '/' );
	$site_name = get_bloginfo( 'name' );
	$org_id    = $home . '#organization';
	$site_id   = $home . '#website';

	$organization = array(
		'@type' => 'NewsMediaOrganization',
		'@id'   => $org_id,
		'name'  => $site_name,
		'url'   => $home,
	);

	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			$organization['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
			);
		}
	}

	$profiles = array();
	foreach ( array( 'social_link_1', 'social_link_2', 'social_link_3' ) as $mod ) {
		$link = get_theme_mod( $mod );
		if ( $link ) {
			$profiles[] = $link;
		}
	}
	if ( $profiles ) {
		$organization['sameAs'] = $profiles;
	}

	$website = array(
		'@type'     => 'WebSite',
		'@id'       => $site_id,
		'url'       => $home,
		'name'      => $site_name,
		'publisher' => array( '@id' => $org_id ),
		'inLanguage' => get_locale(),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => $home . '?s={search_term_string}',
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	$graph = array( $organization, $website );

	if ( is_singular( 'post' ) ) {
		$article = array(
			'@type'            => 'NewsArticle',
			'@id'              => get_permalink() . '#article',
			'headline'         => wp_strip_all_tags( get_the_title() ),
			'mainEntityOfPage' => array( '@type' => 'WebPage', '@id' => get_permalink() ),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author(),
				'url'   => get_author_posts_url( (int) get_the_author_meta( 'ID' ) ),
			),
			'publisher'        => array( '@id' => $org_id ),
			'isPartOf'         => array( '@id' => $site_id ),
			'inLanguage'       => get_locale(),
		);

		$description = techrato_seo_description();
		if ( $description ) {
			$article['description'] = $description;
		}

		list( $image, $width, $height ) = techrato_seo_image();
		if ( $image ) {
			$article['image'] = array(
				'@type'  => 'ImageObject',
				'url'    => $image,
				'width'  => $width ? $width : null,
				'height' => $height ? $height : null,
			);
			$article['image'] = array_filter( $article['image'], function ( $value ) {
				return null !== $value;
			} );
		}

		$categories = get_the_category();
		if ( isset( $categories[0] ) ) {
			$article['articleSection'] = $categories[0]->name;
		}

		$tags = get_the_tags();
		if ( $tags && ! is_wp_error( $tags ) ) {
			$article['keywords'] = implode( ', ', wp_list_pluck( $tags, 'name' ) );
		}

		$graph[] = $article;
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$graph[] = array(
			'@type'       => 'CollectionPage',
			'@id'         => techrato_seo_url(),
			'name'        => techrato_seo_title(),
			'description' => techrato_seo_description(),
			'isPartOf'    => array( '@id' => $site_id ),
			'inLanguage'  => get_locale(),
		);
	}

	$trail = techrato_breadcrumb_trail();
	if ( count( $trail ) > 1 ) {
		$items = array();
		foreach ( $trail as $position => $crumb ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $position + 1,
				'name'     => $crumb['name'],
				'item'     => $crumb['url'],
			);
		}
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => techrato_seo_url() . '#breadcrumb',
			'itemListElement' => $items,
		);
	}

	$json = wp_json_encode(
		array( '@context' => 'https://schema.org', '@graph' => $graph ),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);

	if ( $json ) {
		echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput — wp_json_encode output.
	}
}
add_action( 'wp_head', 'techrato_seo_schema', 4 );
