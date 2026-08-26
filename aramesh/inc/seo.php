<?php
/**
 * سئو: OpenGraph/Twitter، canonical و داده‌ساختاریافته (JSON-LD).
 *
 * اگر افزونه سئویی مثل Yoast/RankMath فعال باشد، این ماژول برای پرهیز از تداخل
 * خروجی OpenGraph را غیرفعال می‌کند و فقط schemaهای اختصاصی دوره را نگه می‌دارد.
 *
 * @package Aramesh
 */

defined( 'ABSPATH' ) || exit;

/**
 * آیا افزونه سئوی شناخته‌شده فعال است؟
 */
function aramesh_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'SEOPress' );
}

/**
 * متادیتای OpenGraph/Twitter و canonical.
 */
function aramesh_meta_tags() {
	if ( aramesh_seo_plugin_active() ) {
		return;
	}

	$title = wp_get_document_title();
	$url   = home_url( add_query_arg( array(), $GLOBALS['wp']->request ? $GLOBALS['wp']->request : '' ) );
	if ( is_singular() ) {
		$url = get_permalink();
	}

	$desc = get_bloginfo( 'description' );
	if ( is_singular() ) {
		$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', get_the_ID() ) ), 30, '…' );
		if ( $excerpt ) {
			$desc = $excerpt;
		}
	} elseif ( is_category() || is_tax() ) {
		$td = term_description();
		if ( $td ) {
			$desc = wp_strip_all_tags( $td );
		}
	}

	$image = '';
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( get_the_ID(), 'aramesh-cover' );
	} elseif ( has_custom_logo() ) {
		$image = wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' );
	}

	echo "\n<!-- Aramesh SEO -->\n";
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	} else {
		echo '<meta name="twitter:card" content="summary">' . "\n";
	}
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	echo "<!-- /Aramesh SEO -->\n";
}
add_action( 'wp_head', 'aramesh_meta_tags', 5 );

/**
 * چاپ یک بلوک JSON-LD.
 */
function aramesh_jsonld( $data ) {
	if ( empty( $data ) ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

/**
 * داده‌ساختاریافته صفحه.
 */
function aramesh_structured_data() {
	// Person برای دکتر (در صفحه اصلی و درباره).
	if ( is_front_page() || is_page_template( 'page-about.php' ) ) {
		aramesh_jsonld(
			array(
				'@context' => 'https://schema.org',
				'@type'    => 'Person',
				'name'     => aramesh_brand_name(),
				'jobTitle' => aramesh_option( 'doctor_title', 'روانشناس' ),
				'url'      => home_url( '/' ),
				'sameAs'   => array_values( array_filter( array( aramesh_option( 'instagram' ), aramesh_option( 'youtube' ), aramesh_option( 'telegram' ) ) ) ),
			)
		);
	}

	// Course.
	if ( is_singular( 'course' ) ) {
		$cid   = get_the_ID();
		$price = aramesh_course_price( $cid );
		$data  = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Course',
			'name'        => get_the_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ),
			'provider'    => array(
				'@type' => 'Person',
				'name'  => get_post_meta( $cid, '_aramesh_teacher', true ) ?: aramesh_brand_name(),
			),
			'url'         => get_permalink(),
		);
		if ( ! $price['is_free'] ) {
			$data['offers'] = array(
				'@type'         => 'Offer',
				'price'         => $price['effective'],
				'priceCurrency' => 'IRR',
				'availability'  => 'https://schema.org/InStock',
				'url'           => get_permalink(),
			);
		}
		aramesh_jsonld( $data );
	}

	// Article.
	if ( is_singular( 'post' ) ) {
		aramesh_jsonld(
			array(
				'@context'         => 'https://schema.org',
				'@type'            => 'Article',
				'headline'         => get_the_title(),
				'datePublished'    => get_the_date( 'c' ),
				'dateModified'     => get_the_modified_date( 'c' ),
				'author'           => array( '@type' => 'Person', 'name' => get_the_author() ),
				'publisher'        => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ) ),
				'mainEntityOfPage' => get_permalink(),
				'image'            => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'aramesh-cover' ) : '',
			)
		);
	}

	// Breadcrumb.
	$crumbs = aramesh_breadcrumb_items();
	if ( count( $crumbs ) > 1 ) {
		$elements = array();
		foreach ( $crumbs as $i => $c ) {
			$el = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => $c['name'],
			);
			if ( ! empty( $c['url'] ) ) {
				$el['item'] = $c['url'];
			}
			$elements[] = $el;
		}
		aramesh_jsonld(
			array(
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $elements,
			)
		);
	}
}
add_action( 'wp_head', 'aramesh_structured_data', 6 );

/**
 * چاپ FAQ schema — فقط وقتی FAQ واقعی و قابل‌مشاهده داریم.
 * از داخل تمپلیت‌ها با آرایه‌ای از {q,a} صدا زده می‌شود.
 */
function aramesh_faq_schema( $faqs ) {
	if ( empty( $faqs ) || aramesh_seo_plugin_active() ) {
		return;
	}
	$entities = array();
	foreach ( $faqs as $faq ) {
		if ( empty( $faq['q'] ) ) {
			continue;
		}
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $faq['q'],
			'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $faq['a'] ),
		);
	}
	if ( $entities ) {
		aramesh_jsonld(
			array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $entities,
			)
		);
	}
}
