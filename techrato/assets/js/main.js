(function () {
	'use strict';

	var root = document.documentElement;
	var THEME_KEY = 'techrato-theme';

	/* ---- Dark / light toggle (icon appears both in the topbar and the mobile drawer).
	   The initial theme is already applied by a blocking inline script in <head>
	   (before first paint) — this only handles the click. ---- */
	document.querySelectorAll( '.js-theme-toggle' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var current = root.getAttribute( 'data-theme' ) === 'light' ? 'light' : 'dark';
			var next = current === 'dark' ? 'light' : 'dark';
			root.setAttribute( 'data-theme', next );
			window.localStorage.setItem( THEME_KEY, next );
		} );
	} );

	var searchPanel = document.querySelector( '.js-search-panel' );
	var mobileNav = document.querySelector( '.js-mobile-nav' );

	function isMobileOverlay() {
		return window.innerWidth <= 960;
	}

	function updateScrollLock() {
		var locked = isMobileOverlay() && (
			( searchPanel && ! searchPanel.hasAttribute( 'hidden' ) ) ||
			( mobileNav && mobileNav.classList.contains( 'is-open' ) )
		);
		document.body.classList.toggle( 'no-scroll', !! locked );
	}

	/* ---- Search overlay/dropdown toggle (icon in topbar, drawer toolbar, and the panel's own close/back controls) ---- */
	if ( searchPanel ) {
		document.querySelectorAll( '.js-search-toggle' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var isHidden = searchPanel.hasAttribute( 'hidden' );
				if ( isHidden ) {
					if ( mobileNav ) {
						mobileNav.classList.remove( 'is-open' );
					}
					searchPanel.removeAttribute( 'hidden' );
					var input = searchPanel.querySelector( 'input[type="search"]' );
					if ( input ) {
						input.focus();
					}
				} else {
					searchPanel.setAttribute( 'hidden', '' );
				}
				updateScrollLock();
			} );
		} );
	}

	/* ---- Mobile menu drawer toggle (hamburger + drawer's own close button) ---- */
	if ( mobileNav ) {
		document.querySelectorAll( '.js-menu-toggle' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var willOpen = ! mobileNav.classList.contains( 'is-open' );
				if ( willOpen && searchPanel ) {
					searchPanel.setAttribute( 'hidden', '' );
				}
				mobileNav.classList.toggle( 'is-open' );
				updateScrollLock();
			} );
		} );
	}

	window.addEventListener( 'resize', updateScrollLock );

	/* ---- Mobile drawer accordion: tap a parent item to reveal its sub-categories (desktop nav is untouched) ---- */
	if ( mobileNav ) {
		mobileNav.querySelectorAll( '.main-nav > li.menu-item-has-children > a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				if ( window.innerWidth > 960 ) {
					return;
				}
				e.preventDefault();
				var li = link.parentElement;
				var wasExpanded = li.classList.contains( 'is-expanded' );
				li.parentElement.querySelectorAll( ':scope > li.is-expanded' ).forEach( function ( openLi ) {
					openLi.classList.remove( 'is-expanded' );
				} );
				if ( ! wasExpanded ) {
					li.classList.add( 'is-expanded' );
				}
			} );
		} );
	}

	/* ---- Bookmark/save toggle (thumbnail overlay buttons + the single-post action button) ---- */
	var SAVED_KEY = 'techrato-saved-posts';

	function getSavedPosts() {
		try {
			return JSON.parse( window.localStorage.getItem( SAVED_KEY ) ) || [];
		} catch ( e ) {
			return [];
		}
	}

	function setSaveButtonsState( postId, saved ) {
		document.querySelectorAll( '.js-save-btn[data-post-id="' + postId + '"]' ).forEach( function ( btn ) {
			btn.classList.toggle( 'is-saved', saved );
		} );
	}

	var savedPosts = getSavedPosts();
	savedPosts.forEach( function ( id ) {
		setSaveButtonsState( id, true );
	} );

	document.querySelectorAll( '.js-save-btn' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var postId = btn.getAttribute( 'data-post-id' );
			if ( ! postId ) {
				return;
			}
			var saved = getSavedPosts();
			var index = saved.indexOf( postId );
			var nowSaved;
			if ( index === -1 ) {
				saved.push( postId );
				nowSaved = true;
			} else {
				saved.splice( index, 1 );
				nowSaved = false;
			}
			window.localStorage.setItem( SAVED_KEY, JSON.stringify( saved ) );
			setSaveButtonsState( postId, nowSaved );
		} );
	} );

	/* ---- Like toggle (single-post page). The server (post meta + a cookie)
	   is the source of truth for the count, but the "did I like this" visual
	   state is also mirrored in localStorage so it still shows correctly even
	   if a page-cache plugin serves a stale copy of the server-rendered HTML. ---- */
	var LIKED_KEY = 'techrato-liked-posts';

	function getLikedPosts() {
		try {
			return JSON.parse( window.localStorage.getItem( LIKED_KEY ) ) || [];
		} catch ( e ) {
			return [];
		}
	}

	document.querySelectorAll( '.js-like-btn' ).forEach( function ( btn ) {
		var postId = btn.getAttribute( 'data-post-id' );
		if ( postId && getLikedPosts().indexOf( postId ) !== -1 ) {
			btn.classList.add( 'is-liked' );
		}

		btn.addEventListener( 'click', function () {
			if ( btn.disabled || typeof techratoData === 'undefined' ) {
				return;
			}
			var countEl = btn.querySelector( '.count' );
			btn.disabled = true;

			var body = new window.URLSearchParams();
			body.set( 'action', 'techrato_toggle_like' );
			body.set( 'nonce', techratoData.nonce );
			body.set( 'post_id', postId );

			window.fetch( techratoData.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString(),
			} )
				.then( function ( res ) { return res.json(); } )
				.then( function ( res ) {
					if ( res && res.success ) {
						var liked = !! res.data.liked;
						btn.classList.toggle( 'is-liked', liked );
						if ( countEl ) {
							countEl.textContent = res.data.count;
						}
						var liked_posts = getLikedPosts();
						var index = liked_posts.indexOf( postId );
						if ( liked && index === -1 ) {
							liked_posts.push( postId );
						} else if ( ! liked && index !== -1 ) {
							liked_posts.splice( index, 1 );
						}
						window.localStorage.setItem( LIKED_KEY, JSON.stringify( liked_posts ) );
					}
				} )
				.finally( function () {
					btn.disabled = false;
				} );
		} );
	} );

	/* ---- Tabs: highlight the tab and show its panel ---- */
	document.querySelectorAll( '.tabs' ).forEach( function ( group ) {
		var scope = group.parentElement;

		group.querySelectorAll( 'button, a' ).forEach( function ( tab ) {
			tab.addEventListener( 'click', function ( e ) {
				if ( tab.tagName === 'BUTTON' ) {
					e.preventDefault();
				}

				group.querySelectorAll( '.is-active' ).forEach( function ( el ) {
					el.classList.remove( 'is-active' );
					el.setAttribute( 'aria-selected', 'false' );
				} );
				tab.classList.add( 'is-active' );
				tab.setAttribute( 'aria-selected', 'true' );

				var target = tab.getAttribute( 'data-panel' );
				if ( ! target || ! scope ) {
					return;
				}
				scope.querySelectorAll( '.news-tab-panel' ).forEach( function ( panel ) {
					panel.classList.toggle( 'is-active', panel.id === target );
				} );
			} );
		} );
	} );
} )();
