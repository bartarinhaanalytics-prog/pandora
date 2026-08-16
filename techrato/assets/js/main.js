(function () {
	'use strict';

	var root = document.documentElement;
	var THEME_KEY = 'techrato-theme';

	/* ---- Dark / light toggle ---- */
	function applyStoredTheme() {
		var stored = window.localStorage.getItem( THEME_KEY );
		if ( stored ) {
			root.setAttribute( 'data-theme', stored );
		}
	}
	applyStoredTheme();

	var themeToggle = document.querySelector( '.js-theme-toggle' );
	if ( themeToggle ) {
		themeToggle.addEventListener( 'click', function () {
			var current = root.getAttribute( 'data-theme' ) === 'light' ? 'light' : 'dark';
			var next = current === 'dark' ? 'light' : 'dark';
			root.setAttribute( 'data-theme', next );
			window.localStorage.setItem( THEME_KEY, next );
		} );
	}

	/* ---- Search panel toggle ---- */
	var searchToggle = document.querySelector( '.js-search-toggle' );
	var searchPanel = document.querySelector( '.js-search-panel' );
	if ( searchToggle && searchPanel ) {
		searchToggle.addEventListener( 'click', function () {
			var isHidden = searchPanel.hasAttribute( 'hidden' );
			if ( isHidden ) {
				searchPanel.removeAttribute( 'hidden' );
				var input = searchPanel.querySelector( 'input[type="search"]' );
				if ( input ) {
					input.focus();
				}
			} else {
				searchPanel.setAttribute( 'hidden', '' );
			}
		} );
	}

	/* ---- Mobile menu toggle ---- */
	var menuToggle = document.querySelector( '.js-menu-toggle' );
	var mobileNav = document.querySelector( '.js-mobile-nav' );
	if ( menuToggle && mobileNav ) {
		menuToggle.addEventListener( 'click', function () {
			mobileNav.classList.toggle( 'is-open' );
		} );
	}

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
