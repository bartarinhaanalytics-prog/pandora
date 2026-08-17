(function () {
	'use strict';

	var root = document.documentElement;
	var THEME_KEY = 'techrato-theme';

	/* ---- Dark / light toggle (icon appears both in the topbar and the mobile drawer) ---- */
	function applyStoredTheme() {
		var stored = window.localStorage.getItem( THEME_KEY );
		if ( stored ) {
			root.setAttribute( 'data-theme', stored );
		}
	}
	applyStoredTheme();

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

	/* ---- Like toggle (single-post page) ---- */
	document.querySelectorAll( '.js-like-btn' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			if ( btn.disabled || typeof techratoData === 'undefined' ) {
				return;
			}
			var postId = btn.getAttribute( 'data-post-id' );
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
						btn.classList.toggle( 'is-liked', !! res.data.liked );
						if ( countEl ) {
							countEl.textContent = res.data.count;
						}
					}
				} )
				.finally( function () {
					btn.disabled = false;
				} );
		} );
	} );

	/* ---- Tab UI (visual state only — wire up to real queries as content grows) ---- */
	document.querySelectorAll( '.tabs' ).forEach( function ( group ) {
		group.querySelectorAll( 'button, a' ).forEach( function ( tab ) {
			tab.addEventListener( 'click', function ( e ) {
				if ( tab.tagName === 'BUTTON' ) {
					e.preventDefault();
				}
				group.querySelectorAll( '.is-active' ).forEach( function ( el ) {
					el.classList.remove( 'is-active' );
				} );
				tab.classList.add( 'is-active' );
			} );
		} );
	} );
} )();
