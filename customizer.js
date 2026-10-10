/**
 * Live preview wiring for the Customizer.
 */
( function ( api ) {
	'use strict';

	api( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			document.querySelectorAll( '.brand-name' ).forEach( function ( el ) {
				el.textContent = to;
			} );
		} );
	} );

	api( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			document.querySelectorAll( '.ft-col--about p' ).forEach( function ( el, i ) {
				if ( i === 0 ) {
					el.textContent = to;
				}
			} );
		} );
	} );

	api( 'ttb_accent', function ( value ) {
		value.bind( function ( to ) {
			var id = 'ttb-accent-live';
			var tag = document.getElementById( id );
			if ( ! tag ) {
				tag = document.createElement( 'style' );
				tag.id = id;
				document.head.appendChild( tag );
			}
			tag.textContent = ':root{--brand:' + to + '}:root[data-theme="dark"]{--brand:' + to + '}';
		} );
	} );
}( window.wp.customize ) );
