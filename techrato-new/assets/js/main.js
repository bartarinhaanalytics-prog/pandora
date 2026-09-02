/**
 * Techrato — front-end behaviour.
 *
 * Everything here is optional: with JavaScript off the site still navigates,
 * searches and paginates, because each control is a real link or form first.
 */
( function () {
	'use strict';

	/**
	 * Runs a piece of behaviour, and keeps going if it throws. Everything used
	 * to live in one block: a single error took the whole page's behaviour
	 * down with it.
	 */
	function guard( fn ) {
		try {
			fn();
		} catch ( err ) {
			if ( window.console && window.console.error ) {
				window.console.error( 'techrato:', err );
			}
		}
	}

	function start() {
	var data = window.techratoData || {};

	/* ---------------------------------------------------------------
	 * Mobile menu
	 * ------------------------------------------------------------- */
	guard( function () {
		var toggle   = document.querySelector( '.js-menu-toggle' );
		var panel    = document.getElementById( 'mobile-menu-panel' );
		var backdrop = document.querySelector( '.js-menu-backdrop' );
		var close    = document.querySelector( '.js-menu-close' );

		if ( ! toggle || ! panel ) {
			return;
		}

		function setOpen( open ) {
			document.body.classList.toggle( 'menu-open', open );
			panel.classList.toggle( 'is-open', open );
			panel.setAttribute( 'aria-hidden', open ? 'false' : 'true' );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( backdrop ) {
				backdrop.hidden = ! open;
			}
		}

		toggle.addEventListener( 'click', function () {
			setOpen( ! panel.classList.contains( 'is-open' ) );
		} );

		if ( close ) {
			close.addEventListener( 'click', function () { setOpen( false ); } );
		}
		if ( backdrop ) {
			backdrop.addEventListener( 'click', function () { setOpen( false ); } );
		}

		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) {
				setOpen( false );
			}
		} );

		// Sub-menus fold open in the drawer rather than hovering.
		panel.addEventListener( 'click', function ( e ) {
			var item = e.target.closest( '.menu-item-has-children > a' );
			if ( ! item ) {
				return;
			}
			var li = item.parentNode;
			if ( ! li.classList.contains( 'is-open' ) ) {
				e.preventDefault();
			}
			li.classList.toggle( 'is-open' );
		} );
	} );

	/* ---------------------------------------------------------------
	 * Search overlay
	 * ------------------------------------------------------------- */
	guard( function () {
		var toggle  = document.querySelector( '.js-search-toggle' );
		var overlay = document.querySelector( '.js-search-overlay' );
		var close   = document.querySelector( '.js-search-close' );

		if ( ! toggle || ! overlay ) {
			return;
		}

		function setOpen( open ) {
			overlay.hidden = ! open;
			document.body.classList.toggle( 'search-open', open );
			if ( open ) {
				var field = overlay.querySelector( 'input[type="search"]' );
				if ( field ) {
					field.focus();
				}
			}
		}

		toggle.addEventListener( 'click', function () { setOpen( overlay.hidden ); } );
		if ( close ) {
			close.addEventListener( 'click', function () { setOpen( false ); } );
		}
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) {
				setOpen( false );
			}
		} );
	} );

	/* ---------------------------------------------------------------
	 * Mega menu
	 *
	 * Hover opens it, and it stays open for a moment after the pointer
	 * leaves so the cursor can travel down into the panel without it
	 * snapping shut. Clicking the chevron pins it open.
	 * ------------------------------------------------------------- */
	guard( function () {
		var items = document.querySelectorAll( '.nav-item.has-mega' );
		if ( ! items.length || ! window.matchMedia( '(min-width: 961px)' ).matches ) {
			return;
		}

		items.forEach( function ( item ) {
			var timer = null;

			function open() {
				window.clearTimeout( timer );
				items.forEach( function ( other ) {
					if ( other !== item ) {
						other.classList.remove( 'is-open', 'is-pinned' );
					}
				} );
				item.classList.add( 'is-open' );
			}

			function scheduleClose() {
				window.clearTimeout( timer );
				timer = window.setTimeout( function () {
					if ( ! item.classList.contains( 'is-pinned' ) ) {
						item.classList.remove( 'is-open' );
					}
				}, 220 );
			}

			item.addEventListener( 'mouseenter', open );
			item.addEventListener( 'mouseleave', scheduleClose );

			var chevron = item.querySelector( '.nav-chevron' );
			if ( chevron ) {
				chevron.addEventListener( 'click', function ( e ) {
					e.preventDefault();
					e.stopPropagation();
					var pinned = item.classList.toggle( 'is-pinned' );
					item.classList.toggle( 'is-open', pinned );
				} );
			}
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( ! e.target.closest( '.nav-item.has-mega' ) ) {
				items.forEach( function ( item ) {
					item.classList.remove( 'is-open', 'is-pinned' );
				} );
			}
		} );
	} );

	/* ---------------------------------------------------------------
	 * Load more, in place
	 *
	 * The click is taken over here in every case: the page must not move at
	 * all. Placeholder cards appear under the list straight away, and the real
	 * posts fade in over them when they arrive.
	 * ------------------------------------------------------------- */

	/**
	 * Where to post to. Printed into the markup, localized into a variable and
	 * finally worked out from the address bar — because a plugin that combines
	 * or defers scripts can take either of the first two away, and a button
	 * that silently gives up looks broken.
	 */
	function feedEndpoint( feed ) {
		if ( feed && feed.dataset.ajax ) {
			return feed.dataset.ajax;
		}

		if ( data.ajaxUrl ) {
			return data.ajaxUrl;
		}

		// WordPress always prints its own address in the head; admin-ajax sits
		// next to it. This keeps working on an installation in a subfolder.
		var link = document.querySelector( 'link[rel="https://api.w.org/"]' );

		if ( link && link.href ) {
			return link.href.replace( /wp-json\/?.*$/, '' ) + 'wp-admin/admin-ajax.php';
		}

		return window.location.origin + '/wp-admin/admin-ajax.php';
	}

	/**
	 * Placeholder cards, cloned from a real one so every list gets the shape
	 * it actually uses. Text and pictures are stripped and the stylesheet
	 * paints the shimmering bars.
	 */
	function addSkeletons( list, count ) {
		var sample = list.querySelector( 'article, .card-list-row, .news-card' );

		if ( ! sample ) {
			return [];
		}

		// The real card is measured first, so a placeholder takes exactly the
		// room the post will take and nothing shifts when they swap over.
		var tall = Math.round( sample.getBoundingClientRect().height );
		var shots = [].slice.call( sample.querySelectorAll( 'img' ) ).map( function ( img ) {
			var box = img.getBoundingClientRect();
			return { w: Math.round( box.width ), h: Math.round( box.height ) };
		} );

		// A transparent pixel: an <img> with no source draws a broken-image mark.
		var blank = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
		var made = [];
		var i;

		for ( i = 0; i < count; i++ ) {
			var ghost = sample.cloneNode( true );

			ghost.removeAttribute( 'id' );
			ghost.classList.add( 'feed-skeleton' );
			ghost.setAttribute( 'aria-hidden', 'true' );

			if ( tall > 0 ) {
				ghost.style.minHeight = tall + 'px';
			}

			[].slice.call( ghost.querySelectorAll( 'img' ) ).forEach( function ( img, n ) {
				img.removeAttribute( 'srcset' );
				img.removeAttribute( 'sizes' );
				img.setAttribute( 'alt', '' );
				img.src = blank;

				if ( shots[ n ] && shots[ n ].h > 0 ) {
					img.style.width  = shots[ n ].w + 'px';
					img.style.height = shots[ n ].h + 'px';
				}
			} );

			ghost.querySelectorAll( 'a' ).forEach( function ( a ) {
				a.removeAttribute( 'href' );
			} );

			list.appendChild( ghost );
			made.push( ghost );
		}

		return made;
	}

	document.addEventListener( 'click', function ( e ) {
		var more = e.target.closest ? e.target.closest( '.js-load-more' ) : null;

		if ( ! more ) {
			return;
		}

		var feed = more.closest( '.js-feed' );
		var list = feed && feed.querySelector( '.js-post-list' );

		if ( ! list || ! window.fetch ) {
			return;
		}

		// From here the page stays exactly where it is.
		e.preventDefault();

		if ( more.classList.contains( 'is-busy' ) ) {
			return;
		}

		var next = parseInt( list.dataset.paged, 10 ) + 1;
		var max  = parseInt( list.dataset.max, 10 );
		var per  = parseInt( list.dataset.per, 10 ) || 6;

		if ( ! next || ( max && next > max ) ) {
			return;
		}

		more.classList.add( 'is-busy' );
		var label = more.textContent;
		more.textContent = 'در حال بارگذاری…';

		var ghosts = addSkeletons( list, per );

		var body = new URLSearchParams( {
			action: 'techrato_load_posts',
			term:   list.dataset.term || 0,
			paged:  next,
			per:    list.dataset.per || 0,
			card:   list.dataset.card || 'news',
			days:   list.dataset.days || 0,
			sort:   list.dataset.sort || 'date'
		} );

		function clearGhosts() {
			ghosts.forEach( function ( ghost ) {
				if ( ghost.parentNode ) {
					ghost.parentNode.removeChild( ghost );
				}
			} );
			ghosts = [];
		}

		window.fetch( feedEndpoint( feed ), { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( r ) { return r.json(); } )
			.then( function ( res ) {
				if ( ! res || ! res.success || ! res.data ) {
					throw new Error( 'bad response' );
				}

				var holder = document.createElement( 'div' );
				holder.innerHTML = res.data.html;

				var fresh = [].slice.call( holder.children );

				clearGhosts();

				// Added underneath what is already there. Never replacing it.
				fresh.forEach( function ( card, i ) {
					card.classList.add( 'feed-entering' );
					card.style.animationDelay = ( i * 45 ) + 'ms';
					list.appendChild( card );
				} );

				list.dataset.paged = res.data.paged;
				list.dataset.max   = res.data.maxPages;

				if ( res.data.paged >= res.data.maxPages ) {
					more.remove();
				} else {
					more.textContent = label;
					more.classList.remove( 'is-busy' );
				}
			} )
			.catch( function () {
				clearGhosts();
				more.classList.remove( 'is-busy' );

				var tries = ( parseInt( more.dataset.tries, 10 ) || 0 ) + 1;
				more.dataset.tries = tries;

				var href = more.getAttribute( 'href' );

				// Twice in a row means something on this connection is wrong,
				// not a passing hiccup. The plain link still shows the posts —
				// the page it opens keeps everything already on screen — so the
				// reader is never left with a button that does nothing.
				if ( tries >= 2 && href ) {
					window.location.href = href;
					return;
				}

				more.textContent = 'دوباره تلاش کنید';

				window.setTimeout( function () {
					more.textContent = label;
				}, 2500 );
			} );
	} );

	/* ---------------------------------------------------------------
	 * Landing on the first new post
	 *
	 * When the button falls back to its plain link, the address carries a
	 * #more-N marker. Pictures finish loading after the browser has already
	 * jumped, which pushes the list down, so the jump is made once more when
	 * everything has settled.
	 * ------------------------------------------------------------- */
	guard( function () {
		if ( ! /^#more-\d+$/.test( window.location.hash ) ) {
			return;
		}

		var target = document.getElementById( window.location.hash.slice( 1 ) );

		if ( ! target ) {
			return;
		}

		window.addEventListener( 'load', function () {
			window.setTimeout( function () {
				target.scrollIntoView();
			}, 60 );
		} );
	} );

	/* ---------------------------------------------------------------
	 * Likes and saved posts
	 *
	 * The same post has two sets of buttons: the row under the title and
	 * the floating dock. Both are kept in step, so a tap on either one
	 * updates the other.
	 * ------------------------------------------------------------- */
	guard( function () {
		var likes = [].slice.call( document.querySelectorAll( '.js-like-btn' ) );
		var busy  = false;

		function paintLikes( count, liked ) {
			likes.forEach( function ( btn ) {
				var el = btn.querySelector( '.article-like-count' );
				if ( el && null !== count ) {
					el.textContent = count;
				}
				btn.classList.toggle( 'is-active', !! liked );
				btn.setAttribute( 'aria-pressed', liked ? 'true' : 'false' );
			} );
		}

		if ( likes.length && data.ajaxUrl ) {
			likes.forEach( function ( like ) {
				like.addEventListener( 'click', function () {
					if ( busy ) {
						return;
					}
					busy = true;

					window.fetch( data.ajaxUrl, {
						method: 'POST',
						credentials: 'same-origin',
						body: new URLSearchParams( {
							action:  'techrato_toggle_like',
							post_id: like.dataset.postId,
							nonce:   data.nonce || ''
						} )
					} )
						.then( function ( r ) { return r.json(); } )
						.then( function ( res ) {
							if ( res && res.success ) {
								paintLikes( res.data.count, res.data.liked );
							}
						} )
						.catch( function () {} )
						.then( function () { busy = false; } );
				} );
			} );
		}

		// Saved posts live in the browser only; nothing is sent anywhere.
		var saves = [].slice.call( document.querySelectorAll( '.js-save-btn' ) );
		if ( ! saves.length ) {
			return;
		}

		var key = 'techrato_saved';

		function read() {
			try {
				return JSON.parse( window.localStorage.getItem( key ) || '[]' );
			} catch ( e ) {
				return [];
			}
		}

		var id = saves[0].dataset.postId;

		function paintSaves( on ) {
			saves.forEach( function ( btn ) {
				btn.classList.toggle( 'is-active', on );
				btn.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
			} );
		}

		paintSaves( read().indexOf( id ) !== -1 );

		saves.forEach( function ( save ) {
			save.addEventListener( 'click', function () {
				var list = read();
				var at   = list.indexOf( id );

				if ( at === -1 ) {
					list.push( id );
				} else {
					list.splice( at, 1 );
				}

				try {
					window.localStorage.setItem( key, JSON.stringify( list ) );
				} catch ( e ) {}

				paintSaves( at === -1 );
			} );
		} );
	} );

	/* ---------------------------------------------------------------
	 * Share button
	 *
	 * Uses the phone's own share sheet where there is one, and quietly
	 * copies the address to the clipboard everywhere else.
	 * ------------------------------------------------------------- */
	guard( function () {
	document.querySelectorAll( '.js-share-btn' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var share = { title: document.title, url: window.location.href };

			if ( navigator.share ) {
				navigator.share( share ).catch( function () {} );
				return;
			}

			if ( navigator.clipboard ) {
				navigator.clipboard.writeText( share.url ).then( function () {
					btn.classList.add( 'is-active' );
					window.setTimeout( function () { btn.classList.remove( 'is-active' ); }, 1200 );
				} ).catch( function () {} );
			}
		} );
	} );
	} );

	/* ---------------------------------------------------------------
	 * Floating action dock
	 *
	 * Appears once the reader has scrolled past the top of the article
	 * and steps aside again when the related-posts block arrives.
	 * ------------------------------------------------------------- */
	guard( function () {
		var dock = document.querySelector( '.article-action-dock' );

		if ( ! dock ) {
			return;
		}

		var related = document.querySelector( '.related-section' );

		function update() {
			dock.classList.toggle( 'is-visible', window.scrollY > 220 );

			if ( related ) {
				var height = window.innerHeight || document.documentElement.clientHeight;
				dock.classList.toggle( 'is-related-hidden', related.getBoundingClientRect().top <= height * 0.92 );
			}
		}

		window.addEventListener( 'scroll', update, { passive: true } );
		window.addEventListener( 'resize', update );
		update();
	} );

	/* ---------------------------------------------------------------
	 * Reading navigation
	 *
	 * Built from the article's own h2 headings, so nothing has to be
	 * written by hand in the editor. With no headings the whole panel
	 * simply stays hidden.
	 * ------------------------------------------------------------- */
	guard( function () {
		var root    = document.querySelector( '.js-reading-nav' );
		var content = document.querySelector( '.article-content' );

		if ( ! root || ! content ) {
			return;
		}

		var list     = root.querySelector( '.reading-nav__list' );
		var counter  = root.querySelector( '.reading-nav__counter' );
		var rail     = root.querySelector( '.reading-nav__rail-progress' );
		var toggle   = root.querySelector( '.js-reading-toggle' );
		var index    = root.querySelector( '.reading-nav__mobile-index' );
		var bar      = root.querySelector( '.reading-nav__mobile-progress > span' );
		var headings = [].slice.call( content.querySelectorAll( 'h2' ) );

		if ( ! list || headings.length < 2 ) {
			return;
		}

		root.hidden = false;

		function fa( n ) {
			return Number( n ).toLocaleString( 'fa-IR' );
		}

		function slug( text, i ) {
			var clean = text.trim().replace( /\s+/g, '-' )
				.replace( /[^؀-ۿa-zA-Z0-9\-_]/g, '' )
				.replace( /-+/g, '-' ).replace( /^-|-$/g, '' );

			return clean || ( 'section-' + ( i + 1 ) );
		}

		headings.forEach( function ( h, i ) {
			if ( ! h.id ) {
				var base = slug( h.textContent, i );
				var id   = base;
				var n    = 2;

				while ( document.getElementById( id ) ) {
					id = base + '-' + n++;
				}

				h.id = id;
			}

			var label = h.textContent.trim();
			var btn   = document.createElement( 'button' );

			btn.type      = 'button';
			btn.className = 'reading-nav__item';
			btn.setAttribute( 'aria-label', label );
			btn.innerHTML = '<span class="reading-nav__dot" aria-hidden="true"></span><span class="reading-nav__label"></span>';
			btn.querySelector( '.reading-nav__label' ).textContent = label;

			btn.addEventListener( 'click', function () {
				window.scrollTo( {
					top: h.getBoundingClientRect().top + window.scrollY - 26,
					behavior: 'smooth'
				} );

				if ( window.matchMedia( '(max-width:650px)' ).matches ) {
					root.classList.remove( 'is-open' );
					if ( toggle ) {
						toggle.setAttribute( 'aria-expanded', 'false' );
					}
				}
			} );

			list.appendChild( btn );
		} );

		var items = [].slice.call( list.querySelectorAll( '.reading-nav__item' ) );

		function active( i ) {
			i = Math.max( 0, Math.min( i, headings.length - 1 ) );

			items.forEach( function ( el, n ) {
				el.classList.toggle( 'is-active', n === i );
				el.classList.toggle( 'is-passed', n < i );

				if ( n === i ) {
					el.setAttribute( 'aria-current', 'true' );
				} else {
					el.removeAttribute( 'aria-current' );
				}
			} );

			var text = fa( i + 1 ) + ' / ' + fa( headings.length );

			if ( counter ) {
				counter.textContent = text;
			}
			if ( index ) {
				index.textContent = text;
			}
		}

		function update() {
			var anchor = Math.max( 90, window.innerHeight * 0.28 );
			var at     = 0;

			headings.forEach( function ( h, n ) {
				if ( h.getBoundingClientRect().top <= anchor ) {
					at = n;
				}
			} );

			active( at );

			var first  = headings[0].getBoundingClientRect().top + window.scrollY;
			var bottom = content.getBoundingClientRect().bottom + window.scrollY;
			var start  = Math.max( 0, first - window.innerHeight * 0.28 );
			var stop   = Math.max( start + 1, bottom - window.innerHeight * 0.72 );
			var done   = Math.max( 0, Math.min( 1, ( window.scrollY - start ) / ( stop - start ) ) ) * 100;

			if ( rail ) {
				rail.style.height = done + '%';
			}
			if ( bar ) {
				bar.style.width = done + '%';
			}
		}

		var waiting = false;

		function request() {
			if ( waiting ) {
				return;
			}
			waiting = true;
			window.requestAnimationFrame( function () {
				update();
				waiting = false;
			} );
		}

		if ( toggle ) {
			toggle.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				var open = ! root.classList.contains( 'is-open' );
				root.classList.toggle( 'is-open', open );
				toggle.setAttribute( 'aria-expanded', String( open ) );
			} );
		}

		document.addEventListener( 'click', function ( e ) {
			if ( window.innerWidth <= 650 && root.classList.contains( 'is-open' ) && ! root.contains( e.target ) ) {
				root.classList.remove( 'is-open' );
				if ( toggle ) {
					toggle.setAttribute( 'aria-expanded', 'false' );
				}
			}
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) {
				root.classList.remove( 'is-open' );
				if ( toggle ) {
					toggle.setAttribute( 'aria-expanded', 'false' );
				}
			}
		} );

		// On phones the panel shares its corner with the dock, so it only
		// shows itself while the dock is on screen.
		var dock = document.querySelector( '.article-action-dock' );

		function syncWithDock() {
			if ( ! dock ) {
				return;
			}

			var visible = dock.classList.contains( 'is-visible' ) && ! dock.classList.contains( 'is-related-hidden' );
			root.classList.toggle( 'is-dock-visible', visible );

			if ( ! visible ) {
				root.classList.remove( 'is-open' );
				if ( toggle ) {
					toggle.setAttribute( 'aria-expanded', 'false' );
				}
			}
		}

		if ( dock && window.MutationObserver ) {
			new window.MutationObserver( syncWithDock ).observe( dock, { attributes: true, attributeFilter: [ 'class' ] } );
		}

		window.addEventListener( 'scroll', function () { request(); syncWithDock(); }, { passive: true } );
		window.addEventListener( 'resize', function () { request(); syncWithDock(); } );

		active( 0 );
		update();
		syncWithDock();
	} );

	/* ---------------------------------------------------------------
	 * Reading progress bar (phones only)
	 * ------------------------------------------------------------- */
	guard( function () {
		var progress = document.querySelector( '.mobile-reading-progress' );
		var article  = document.querySelector( '.article-content' );
		var dock     = document.querySelector( '.article-action-dock' );

		if ( ! progress || ! article || ! dock ) {
			return;
		}

		var fill = progress.querySelector( '.mobile-reading-progress__fill' );

		if ( ! fill ) {
			return;
		}

		function update() {
			var small = window.matchMedia( '(max-width:650px)' ).matches;
			var shown = small && dock.classList.contains( 'is-visible' ) && ! dock.classList.contains( 'is-related-hidden' );

			progress.classList.toggle( 'is-dock-visible', shown );

			if ( ! small ) {
				fill.style.width = '0%';
				return;
			}

			var top    = window.scrollY + article.getBoundingClientRect().top;
			var start  = top - Math.min( window.innerHeight * 0.28, 180 );
			var stop   = top + article.offsetHeight - window.innerHeight * 0.72;
			var range  = Math.max( 1, stop - start );
			var done   = Math.max( 0, Math.min( 1, ( window.scrollY - start ) / range ) );

			fill.style.width = ( done * 100 ).toFixed( 2 ) + '%';
		}

		var waiting = false;

		function request() {
			if ( waiting ) {
				return;
			}
			waiting = true;
			window.requestAnimationFrame( function () {
				update();
				waiting = false;
			} );
		}

		if ( window.MutationObserver ) {
			new window.MutationObserver( request ).observe( dock, { attributes: true, attributeFilter: [ 'class' ] } );
		}

		window.addEventListener( 'scroll', request, { passive: true } );
		window.addEventListener( 'resize', request );
		update();
	} );

	/* ---------------------------------------------------------------
	 * View counter
	 *
	 * Sent from the browser because page caching means PHP never runs
	 * for most visitors.
	 * ------------------------------------------------------------- */
	guard( function () {
		if ( ! data.postId || ! data.ajaxUrl ) {
			return;
		}

		window.setTimeout( function () {
			window.fetch( data.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: new URLSearchParams( { action: 'techrato_count_view', post_id: data.postId } )
			} ).catch( function () {} );
		}, 2000 );
	} );

	}

	// A caching plugin may move this file into the head, where the markup does
	// not exist yet. Waiting for the document means it works from either place.
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () { guard( start ); } );
	} else {
		guard( start );
	}
} )();
