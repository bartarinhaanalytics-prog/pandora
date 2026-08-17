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
			<?php echo esc_html( techrato_time_ago( $post_id ) ); ?>
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
	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'مسیر صفحه', 'techrato' ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'خانه', 'techrato' ) . '</a>';

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! empty( $term->parent ) ) {
			$parent = get_term( $term->parent, $term->taxonomy );
			if ( $parent && ! is_wp_error( $parent ) ) {
				echo '<span class="sep">/</span><a href="' . esc_url( get_term_link( $parent ) ) . '">' . esc_html( $parent->name ) . '</a>';
			}
		}
		echo '<span class="sep">/</span><span class="current">' . esc_html( single_term_title( '', false ) ) . '</span>';
	} elseif ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			echo '<span class="sep">/</span><a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
		}
		echo '<span class="sep">/</span><span class="current">' . esc_html( wp_trim_words( get_the_title(), 6 ) ) . '</span>';
	} elseif ( is_page() ) {
		echo '<span class="sep">/</span><span class="current">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_search() ) {
		echo '<span class="sep">/</span><span class="current">' . esc_html__( 'نتایج جستجو', 'techrato' ) . '</span>';
	} elseif ( is_404() ) {
		echo '<span class="sep">/</span><span class="current">' . esc_html__( 'صفحه پیدا نشد', 'techrato' ) . '</span>';
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
 * Bookmark/save button overlaid on a card thumbnail (top-left corner).
 * Purely client-side placeholder for now — wire up to a real "save for
 * later" endpoint (user meta / localStorage) when that feature is needed.
 */
function techrato_save_button( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	?>
	<button class="save-btn" data-post-id="<?php echo esc_attr( $post_id ); ?>" aria-label="<?php esc_attr_e( 'ذخیره', 'techrato' ); ?>">
		<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 4h12v17l-6-4-6 4V4Z"/></svg>
	</button>
	<?php
}

/**
 * Print a social "follow" icon link (generic outward-arrow icon, matches design).
 */
function techrato_social_icon( $url ) {
	if ( empty( $url ) ) {
		return;
	}
	?>
	<a class="icon-btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 17 17 7M9 7h8v8"/></svg>
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
