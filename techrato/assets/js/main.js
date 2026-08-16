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
