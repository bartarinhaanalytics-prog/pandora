<?php
/**
 * Reusable helper functions used across templates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site logo content — just the <img> (or the text+icon fallback), never a
 * wrapping <a>, so callers can put it inside their own single <a class="site-logo">.
 * the_custom_logo() outputs its own <a class="custom-logo-link">, which
 * nested inside another <a> is invalid HTML and breaks layout in some
 * browsers (they close the outer anchor early, so the logo escapes its
 * flex container) — this avoids that entirely.
 */
function techrato_site_logo() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'custom-logo' ) );
		return;
	}
	?>
	<span><?php bloginfo( 'name' ); ?></span>
	<span class="logo-mark">
		<svg viewBox="0 0 24 24" fill="none"><rect x="3" y="12" width="4" height="9" rx="1" fill="currentColor"/><rect x="10" y="7" width="4" height="14" rx="1" fill="currentColor"/><rect x="17" y="3" width="4" height="18" rx="1" fill="currentColor"/></svg>
	</span>
	<?php
}

/**
 * Persian-ish relative time, e.g. "۴ ساعت قبل".
 * Falls back gracefully; keeps Latin digits to match the approved design.
 */
function techrato_time_ago( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$from    = get_post_time( 'U', true, $post_id );
	$to      = current_time( 'timestamp', true );
	$diff    = max( 0, $to - $from );

	if ( $diff < MINUTE_IN_SECONDS ) {
		return __( 'همین حالا', 'techrato' );
	} elseif ( $diff < HOUR_IN_SECONDS ) {
		$val = floor( $diff / MINUTE_IN_SECONDS );
		/* translators: %s: number of minutes */
		return sprintf( __( '%s دقیقه قبل', 'techrato' ), $val );
	} elseif ( $diff < DAY_IN_SECONDS ) {
		$val = floor( $diff / HOUR_IN_SECONDS );
		/* translators: %s: number of hours */
		return sprintf( __( '%s ساعت قبل', 'techrato' ), $val );
	} elseif ( $diff < 30 * DAY_IN_SECONDS ) {
		$val = floor( $diff / DAY_IN_SECONDS );
		/* translators: %s: number of days */
		return sprintf( __( '%s روز قبل', 'techrato' ), $val );
	}
	return date_i18n( 'j F Y', $from );
}

/**
 * Small clock + relative-time + comment-count meta row, used on every card.
 */
function techrato_card_meta( $post_id = null, $show_comments = true ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	?>
	<div class="meta">
		<span class="meta-item">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
			<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>"><?php echo esc_html( techrato_time_ago( $post_id ) ); ?></time>
		</span>
		<?php if ( $show_comments ) : ?>
		<span class="meta-item">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
			<?php echo esc_html( get_comments_number( $post_id ) ); ?>
		</span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Breadcrumb trail: خانه > دسته‌بندی > عنوان جاری
 */
function techrato_breadcrumbs() {
	$trail = techrato_breadcrumb_trail();
	if ( count( $trail ) < 2 ) {
		return;
	}

	$last = count( $trail ) - 1;

	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'مسیر صفحه', 'techrato' ) . '">';
	foreach ( $trail as $position => $crumb ) {
		if ( $position ) {
			echo '<span class="sep">/</span>';
		}
		$name = $position === $last ? wp_trim_words( $crumb['name'], 6 ) : $crumb['name'];
		if ( $position === $last ) {
			echo '<span class="current">' . esc_html( $name ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $name ) . '</a>';
		}
	}
	echo '</nav>';
}

/**
 * Query posts from a category by slug; gracefully falls back to the site's
 * latest posts when the category doesn't exist yet or has no content —
 * keeps a freshly installed site from showing broken/empty sections.
 *
 * @return WP_Query
 */
function techrato_query_by_slug( $slug, $count = 5, $exclude = array() ) {
	$args = array(
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'post__not_in'        => $exclude,
	);

	$term = get_category_by_slug( $slug );
	if ( $term ) {
		$args['cat'] = $term->term_id;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() && $term ) {
		unset( $args['cat'] );
		$query = new WP_Query( $args );
	}

	return $query;
}

/**
 * Fetch posts flagged from the editor's "انتخاب‌های تحریریه" box in the post
 * editor. Those checkboxes are provided by the site's Techrato Core plugin and
 * store a "1" in post meta, so we read the same keys rather than duplicating
 * the UI — every post already flagged keeps working untouched.
 *
 * Falls back to the newest posts when nothing is flagged yet, so a section
 * never renders empty.
 *
 * @param string $meta_key Meta key set by the editorial checkbox.
 * @param int    $count    How many posts to fetch.
 * @param array  $exclude  Post IDs already shown elsewhere on the page.
 * @return WP_Query
 */
function techrato_query_by_flag( $meta_key, $count = 5, $exclude = array() ) {
	$args = array(
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'post__not_in'        => $exclude,
		// Accept any truthy value rather than an exact "1": the plugin that
		// owns these checkboxes may store 1, "1", "on", "yes" or "true"
		// depending on its version, and an exact match silently returns
		// nothing — which looks like the checkbox does nothing at all.
		'meta_query'          => array(
			array(
				'key'     => $meta_key,
				'value'   => array( '1', 'on', 'yes', 'true' ),
				'compare' => 'IN',
			),
		),
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		unset( $args['meta_query'] );
		$query = new WP_Query( $args );
	}

	return $query;
}

/**
 * Target for a section's "مشاهده مطالب بیشتر" link.
 *
 * Each section links to the continuation of that section, resolved in order:
 *   1. the category picked for it in Customizer > تنظیمات تکراتو > لینک‌ها
 *   2. the category slug the section is built around, when the site has it
 *   3. the category most of the posts on display belong to
 *   4. the site's posts page, when one is assigned
 *
 * @param string        $setting   Theme-mod key holding an explicit category ID.
 * @param WP_Query|null $query     The query whose posts the section is showing.
 * @param string        $slug_hint Category slug the section is built around.
 * @return string
 */
/**
 * Image attached to a category/term by whichever plugin the site uses.
 *
 * Category images aren't part of WordPress core, so every plugin stores them
 * somewhere different. Rather than betting on one, look through the keys the
 * common plugins use — including "Categories Images", which most Persian
 * sites use — and return the first hit.
 *
 * @param int|null $term_id Defaults to the queried term.
 * @param string   $size    Image size.
 * @return string Ready-to-print <img> markup, or '' when the term has no image.
 */
function techrato_term_image( $term_id = null, $size = 'large', &$found_via = null ) {
	$found_via = '';
	$term_id = $term_id ? (int) $term_id : (int) get_queried_object_id();
	if ( ! $term_id ) {
		return '';
	}

	$alt = '';
	$term = get_term( $term_id );
	if ( $term instanceof WP_Term ) {
		$alt = $term->name;
	}

	// Attachment IDs kept in term meta.
	$id_keys = apply_filters( 'techrato_term_image_meta_keys', array(
		'techrato_term_image_id', // the theme's own field, set on the category screen
		'thumbnail_id',
		'z_taxonomy_image_id',
		'category_image_id',
		'taxonomy_image_id',
		'category_thumbnail_id',
		'_thumbnail_id',
	) );
	foreach ( $id_keys as $key ) {
		$attachment_id = (int) get_term_meta( $term_id, $key, true );
		if ( $attachment_id ) {
			$html = wp_get_attachment_image( $attachment_id, $size, false, array(
				'alt'     => $alt,
				'loading' => 'lazy',
			) );
			if ( $html ) {
				$found_via = 'term meta "' . $key . '"';
				return $html;
			}
		}
	}

	// Plain URLs kept in term meta, or in an option by older plugin versions.
	$url_keys = apply_filters( 'techrato_term_image_url_keys', array(
		'category_image',
		'taxonomy_image',
		'cat_image',
		'category_image_url',
		'image',
	) );
	$url = '';
	foreach ( $url_keys as $key ) {
		$value = get_term_meta( $term_id, $key, true );
		if ( is_string( $value ) && '' !== $value ) {
			$url   = $value;
			$found_via = 'term meta "' . $key . '"';
			break;
		}
	}
	if ( ! $url && function_exists( 'z_taxonomy_image_url' ) ) {
		$url = (string) z_taxonomy_image_url( $term_id, $size, false );
		if ( $url ) {
			$found_via = 'the Categories Images plugin';
		}
	}
	if ( ! $url ) {
		$url = (string) get_option( 'z_taxonomy_image' . $term_id, '' );
		if ( $url ) {
			$found_via = 'option "z_taxonomy_image' . $term_id . '"';
		}
	}

	$url = apply_filters( 'techrato_term_image_url', $url, $term_id );

	if ( ! $url ) {
		return '';
	}

	return sprintf(
		'<img src="%s" alt="%s" loading="lazy">',
		esc_url( $url ),
		esc_attr( $alt )
	);
}

/**
 * Pull the first <img> out of a block of HTML.
 *
 * Sites often put the category picture inside the category description rather
 * than in a plugin field, which lands it above the text. Lifting it out lets
 * the header place it beside the description instead.
 *
 * @param string $html
 * @return array array( image markup, remaining html )
 */
function techrato_extract_first_image( $html ) {
	if ( ! $html || false === strpos( $html, '<img' ) ) {
		return array( '', $html );
	}

	if ( ! preg_match( '#<img\b[^>]*>#i', $html, $match ) ) {
		return array( '', $html );
	}

	$image = $match[0];
	$quoted = preg_quote( $image, '#' );

	// Take the surrounding link with it, then clear the paragraph it leaves
	// behind, so the description doesn't start with an empty gap.
	$rest = preg_replace( '#<a\b[^>]*>\s*' . $quoted . '\s*</a>#i', '', $html, 1 );
	if ( null === $rest || $rest === $html ) {
		$rest = preg_replace( '#' . $quoted . '#', '', $html, 1 );
	}
	$rest = preg_replace( '#<p>(\s|&nbsp;|<br\s*/?>)*</p>#i', '', (string) $rest );

	return array( $image, trim( (string) $rest ) );
}

/**
 * Administrator-only hint listing what a term actually has stored, shown when
 * ?techrato_debug=1 is on the URL and no category image could be found. Which
 * key holds the image depends on the plugin the site uses, and this is the
 * quickest way to see it.
 */
function techrato_term_image_debug( $found_via = '' ) {
	if ( ! isset( $_GET['techrato_debug'] ) || ! current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	$term_id = (int) get_queried_object_id();
	if ( ! $term_id ) {
		return;
	}

	$description = term_description( $term_id );

	echo '<pre style="direction:ltr;text-align:left;background:#111;color:#0f0;padding:14px;border-radius:8px;overflow:auto;font-size:12px;">';
	echo 'TERM ' . (int) $term_id . "\n";
	echo 'image found via   : ' . esc_html( $found_via ? $found_via : 'NOTHING — no image is being rendered by the theme' ) . "\n";
	echo 'description has an <img>: ' . ( false !== strpos( (string) $description, '<img' ) ? 'YES' : 'no' ) . "\n\n";
	echo "stored term meta:\n";

	$meta = get_term_meta( $term_id );
	if ( $meta ) {
		foreach ( $meta as $key => $values ) {
			$value = is_array( $values ) ? reset( $values ) : $values;
			printf( "  %-28s = %s\n", esc_html( $key ), esc_html( is_scalar( $value ) ? (string) $value : wp_json_encode( $value ) ) );
		}
	} else {
		echo "  (this category has no term meta at all)\n";
	}
	echo '</pre>';
}

/**
 * Tabs above the post list on an archive page.
 *
 * On a category these are real links to its sub-categories — or, when it has
 * none, to its siblings — so every tab goes somewhere and paging still works.
 * A term with neither gets no tabs at all rather than buttons that do nothing.
 *
 * @return array List of array( label, url, current ).
 */
function techrato_archive_tabs() {
	if ( ! is_category() && ! is_tax() && ! is_tag() ) {
		return array();
	}

	$term = get_queried_object();
	if ( ! $term instanceof WP_Term ) {
		return array();
	}

	// Only this category's own sub-categories. Falling back to its siblings
	// put another branch's sub-categories on the page — a sub-category of
	// موبایل showing up under تکنولوژی — which is just confusing.
	$children = get_terms( array(
		'taxonomy'   => $term->taxonomy,
		'parent'     => $term->term_id,
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	) );

	if ( is_wp_error( $children ) || ! $children ) {
		return array();
	}

	// The first tab is the category itself. Its term_id drives the query
	// through WordPress's `cat` parameter, which includes posts filed under
	// the sub-categories too — so "همه" really does mean all of them.
	$tabs = array(
		array(
			'term_id' => (int) $term->term_id,
			'label'   => __( 'همه', 'techrato' ),
			'url'     => get_term_link( $term ),
			'current' => true,
		),
	);

	// Tabs only render on a category that has children, so a child tab is
	// never the page currently being viewed.
	foreach ( $children as $child ) {
		$tabs[] = array(
			'term_id' => (int) $child->term_id,
			'label'   => $child->name,
			'url'     => get_term_link( $child ),
			'current' => false,
		);
	}

	return $tabs;
}

/**
 * Posts ordered by how often they were read, limited to a recent window.
 *
 * Views are counted by a small browser beacon rather than in PHP, because the
 * homepage and posts are served from WP Rocket's page cache — PHP never runs
 * for most visitors, so counting server-side would miss almost everyone.
 *
 * Falls back to comment count while no views have been recorded yet, so the
 * box is never empty on a fresh install.
 *
 * @param int   $count   How many posts.
 * @param int   $days    Only posts published within this many days (0 = any).
 * @param array $exclude Post IDs already shown.
 * @return WP_Query
 */
function techrato_query_popular( $count = 3, $days = 7, $exclude = array() ) {
	$args = array(
		'posts_per_page'      => $count,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'post__not_in'        => $exclude,
		'meta_key'            => 'techrato_views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
	);

	if ( $days > 0 ) {
		$args['date_query'] = array(
			array( 'after' => $days . ' days ago' ),
		);
	}

	$query = new WP_Query( $args );

	// No view data yet: keep the window, drop the view ordering.
	if ( ! $query->have_posts() ) {
		unset( $args['meta_key'] );
		$args['orderby'] = 'comment_count';
		$query = new WP_Query( $args );
	}

	// Still nothing published in the window: widen to the newest posts.
	if ( ! $query->have_posts() ) {
		unset( $args['date_query'] );
		$query = new WP_Query( $args );
	}

	return $query;
}

function techrato_more_url( $setting = '', $query = null, $slug_hint = '' ) {

	if ( $setting ) {
		$chosen = (int) get_theme_mod( $setting, 0 );
		if ( $chosen && get_term( $chosen, 'category' ) instanceof WP_Term ) {
			return get_category_link( $chosen );
		}
	}

	if ( $slug_hint ) {
		$term = get_category_by_slug( $slug_hint );
		if ( $term ) {
			return get_category_link( $term->term_id );
		}
	}

	// Follow the posts themselves: whichever category most of the listed
	// posts sit in is where more of the same lives. This keeps the link
	// meaningful on sites whose categories don't match the theme's slugs.
	if ( $query instanceof WP_Query && ! empty( $query->posts ) ) {
		$counts = array();
		foreach ( $query->posts as $listed ) {
			$post_id = is_object( $listed ) ? $listed->ID : (int) $listed;
			foreach ( get_the_category( $post_id ) as $cat ) {
				if ( 'uncategorized' === $cat->slug ) {
					continue;
				}
				$counts[ $cat->term_id ] = isset( $counts[ $cat->term_id ] ) ? $counts[ $cat->term_id ] + 1 : 1;
			}
		}
		if ( $counts ) {
			arsort( $counts );
			return get_category_link( key( $counts ) );
		}
	}

	$posts_page = (int) get_option( 'page_for_posts' );
	if ( $posts_page && 'publish' === get_post_status( $posts_page ) ) {
		return get_permalink( $posts_page );
	}

	return home_url( '/' );
}

/**
 * Bookmark/save button overlaid on a card thumbnail (top-left corner).
 * Purely client-side placeholder for now — wire up to a real "save for
 * later" endpoint (user meta / localStorage) when that feature is needed.
 */
function techrato_save_button( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	?>
	<button type="button" class="save-btn js-save-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" aria-label="<?php esc_attr_e( 'ذخیره', 'techrato' ); ?>">
		<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 4h12v17l-6-4-6 4V4Z"/></svg>
	</button>
	<?php
}

/**
 * Print a social "follow" icon link. Detects the platform from the URL's
 * host so each button gets its real brand icon instead of a generic one.
 */
function techrato_social_icon( $url ) {
	if ( empty( $url ) ) {
		return;
	}

	$host = wp_parse_url( $url, PHP_URL_HOST );
	$host = $host ? strtolower( preg_replace( '/^www\./', '', $host ) ) : '';

	$icons = array(
		'twitter'  => array(
			'match' => array( 'twitter.com', 'x.com' ),
			'label' => __( 'توییتر', 'techrato' ),
			'svg'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 3h3l-7 8 8.2 10h-6.4l-5-6.5L5 21H2l7.5-8.6L1.7 3h6.5l4.5 5.9L18.9 3Zm-1.1 16.2h1.7L7.3 4.7H5.5l12.3 14.5Z"/></svg>',
		),
		'instagram' => array(
			'match' => array( 'instagram.com' ),
			'label' => __( 'اینستاگرام', 'techrato' ),
			'svg'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg>',
		),
		'telegram' => array(
			'match' => array( 't.me', 'telegram.org', 'telegram.me' ),
			'label' => __( 'تلگرام', 'techrato' ),
			'svg'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.5 4.5 3.2 11.4c-1.2.47-1.19 1.12-.22 1.42l4.68 1.46 1.8 5.5c.22.6.38.85.78.85.35 0 .53-.16.75-.38l1.83-1.78 4.1 3.03c.75.42 1.3.2 1.5-.7l2.7-13.1c.3-1.2-.4-1.7-1.14-1.24Zm-3.2 3.15-9.6 6.05-.42 3.98-1.9-5.9 11.9-4.13Z"/></svg>',
		),
	);

	$icon = null;
	foreach ( $icons as $data ) {
		foreach ( $data['match'] as $needle ) {
			if ( $host && false !== strpos( $host, $needle ) ) {
				$icon = $data;
				break 2;
			}
		}
	}

	$label = $icon ? $icon['label'] : __( 'شبکه اجتماعی', 'techrato' );
	$svg   = $icon ? $icon['svg'] : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 17 17 7M9 7h8v8"/></svg>';
	?>
	<a class="icon-btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $label ); ?>">
		<?php echo $svg; // phpcs:ignore -- static, theme-defined SVG markup, not user input. ?>
	</a>
	<?php
}

/**
 * Fallback "coming soon" placeholder card used when a section has no
 * published posts yet (fresh install), so layouts never look broken.
 */
function techrato_empty_card_notice( $text = '' ) {
	$text = $text ? $text : __( 'به‌زودی محتوا در این بخش منتشر می‌شود.', 'techrato' );
	echo '<p style="color:var(--text-dim);font-size:13px;padding:20px 0;">' . esc_html( $text ) . '</p>';
}

/**
 * Custom comment markup, matches the design's avatar/name/date/text layout.
 */
function techrato_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo esc_html( $tag ); ?> <?php comment_class( $depth > 1 ? 'comment is-reply' : 'comment' ); ?> id="comment-<?php comment_ID(); ?>">
		<div class="avatar"><?php echo get_avatar( $comment, 42 ); ?></div>
		<div style="flex:1;min-width:0;">
			<span class="name"><?php comment_author(); ?></span>
			<span class="date"><?php echo esc_html( get_comment_date( 'j F Y' ) ); ?></span>
			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="text"><em><?php esc_html_e( 'دیدگاه شما پس از تایید نمایش داده می‌شود.', 'techrato' ); ?></em></p>
			<?php else : ?>
				<div class="text"><?php comment_text(); ?></div>
			<?php endif; ?>
			<div class="comment-actions">
				<?php
				comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'پاسخ', 'techrato' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
				) ) );
				?>
			</div>
		</div>
	<?php
}
