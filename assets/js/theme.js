/**
 * Tech Trend Bytes — front end behaviour.
 *
 * Five small things, no framework, no jQuery: the appearance switcher, the mobile menu, the
 * search panel, the reading progress bar and the contents toggle. Everything degrades to a
 * working page if this file fails to load.
 */
( function () {
	'use strict';

	var root = document.documentElement;

	/* ----------------------------------------------------------------
	   Appearance: Light, Dark, or follow the system.

	   The header script has already applied the saved choice before first paint. This adds the
	   button behaviour and keeps System honest by listening for the OS switching over.
	   ---------------------------------------------------------------- */
	var MODES = [ 'system', 'light', 'dark' ];
	var LABELS = { system: 'System', light: 'Light', dark: 'Dark' };
	var mq = window.matchMedia( '(prefers-color-scheme: dark)' );

	function readMode() {
		try {
			var v = localStorage.getItem( 'ttb-appearance' );
			return MODES.indexOf( v ) > -1 ? v : 'system';
		} catch ( e ) {
			return 'system';
		}
	}

	function applyMode( mode ) {
		var dark = mode === 'dark' || ( mode === 'system' && mq.matches );

		root.setAttribute( 'data-appearance', mode );
		root.setAttribute( 'data-theme', dark ? 'dark' : 'light' );

		try {
			localStorage.setItem( 'ttb-appearance', mode );
		} catch ( e ) {}

		var btn = document.getElementById( 'appearance' );
		if ( btn ) {
			btn.setAttribute( 'data-mode', mode );
			btn.setAttribute( 'title', 'Appearance: ' + LABELS[ mode ] );
			btn.setAttribute( 'aria-label', 'Appearance: ' + LABELS[ mode ] + '. Click to change.' );
			var text = btn.querySelector( '.appearance-text' );
			if ( text ) {
				text.textContent = LABELS[ mode ];
			}
		}
	}

	applyMode( readMode() );

	if ( mq.addEventListener ) {
		mq.addEventListener( 'change', function () {
			if ( readMode() === 'system' ) {
				applyMode( 'system' );
			}
		} );
	}

	var appearanceBtn = document.getElementById( 'appearance' );
	if ( appearanceBtn ) {
		appearanceBtn.addEventListener( 'click', function () {
			applyMode( MODES[ ( MODES.indexOf( readMode() ) + 1 ) % MODES.length ] );
		} );
	}

	/* ----------------------------------------------------------------
	   Mobile menu
	   ---------------------------------------------------------------- */
	var burger = document.querySelector( '.hd-burger' );
	var nav = document.getElementById( 'nav' );

	if ( burger && nav ) {
		burger.addEventListener( 'click', function () {
			var open = burger.getAttribute( 'aria-expanded' ) === 'true';
			burger.setAttribute( 'aria-expanded', String( ! open ) );
			document.body.classList.toggle( 'nav-open', ! open );
		} );

		nav.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( 'a' ) ) {
				burger.setAttribute( 'aria-expanded', 'false' );
				document.body.classList.remove( 'nav-open' );
			}
		} );
	}

	/* ----------------------------------------------------------------
	   Search panel
	   ---------------------------------------------------------------- */
	var searchBtn = document.getElementById( 'search-open' );
	var searchPanel = document.getElementById( 'search-panel' );

	if ( searchBtn && searchPanel ) {
		searchBtn.addEventListener( 'click', function () {
			var open = searchBtn.getAttribute( 'aria-expanded' ) === 'true';
			searchBtn.setAttribute( 'aria-expanded', String( ! open ) );
			searchPanel.hidden = open;

			if ( ! open ) {
				var input = searchPanel.querySelector( 'input[type="search"]' );
				if ( input ) {
					input.focus();
				}
			}
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && ! searchPanel.hidden ) {
				searchPanel.hidden = true;
				searchBtn.setAttribute( 'aria-expanded', 'false' );
				searchBtn.focus();
			}
		} );
	}

	/* ----------------------------------------------------------------
	   Reading progress

	   Measured against the article itself rather than the whole document, so the bar reaches
	   the end when the story ends, not when the footer does.
	   ---------------------------------------------------------------- */
	var bar = document.querySelector( '.progress-bar' );
	var prose = document.querySelector( '.prose' );

	if ( bar && prose ) {
		var ticking = false;

		var update = function () {
			var box = prose.getBoundingClientRect();
			var start = window.scrollY + box.top;
			var span = box.height - window.innerHeight * 0.4;
			var pct = span > 0 ? ( window.scrollY - start ) / span : 0;

			bar.style.transform = 'scaleX(' + Math.min( 1, Math.max( 0, pct ) ) + ')';
			ticking = false;
		};

		var onScroll = function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		};

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll, { passive: true } );
		update();
	}

	/* ----------------------------------------------------------------
	   Contents list: collapsed by default on a phone, open on a laptop
	   ---------------------------------------------------------------- */
	var tocBtn = document.querySelector( '.toc-btn' );
	var tocList = document.getElementById( 'toc-list' );

	if ( tocBtn && tocList ) {
		if ( window.matchMedia( '(max-width: 760px)' ).matches ) {
			tocBtn.setAttribute( 'aria-expanded', 'false' );
			tocList.hidden = true;
		}

		tocBtn.addEventListener( 'click', function () {
			var open = tocBtn.getAttribute( 'aria-expanded' ) === 'true';
			tocBtn.setAttribute( 'aria-expanded', String( ! open ) );
			tocList.hidden = open;
		} );
	}

	/* ----------------------------------------------------------------
	   Header shadow once the page has moved
	   ---------------------------------------------------------------- */
	var hd = document.getElementById( 'hd' );
	if ( hd ) {
		var stick = function () {
			hd.classList.toggle( 'is-stuck', window.scrollY > 8 );
		};
		window.addEventListener( 'scroll', stick, { passive: true } );
		stick();
	}
}() );
