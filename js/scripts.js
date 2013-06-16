jQuery( document ).ready( function() {

	//prepend menu icon
	jQuery( '#logo' ).after(
		'<div id="menu-icon">Menu</div>'
	);

	//toggle nav
	jQuery( '#menu-icon' ).on( 'click', function() {

		jQuery( "#menu-main-menu" ).slideToggle( 'slow' );
		jQuery( this ).toggleClass( 'active' );

	} );

	//hide the menu button wien we resize the menu
	jQuery( window ).resize( function() {

		if ( jQuery( window ).width() >= 800 ) {

			jQuery( '#menu-main-menu' ).removeStyle( 'display' );

		}

	} );

} );

/**
 * Removes inline styles from element
 *
 * @param  string	style	Name of style to remove
 * @return string	Inline styles without removed element
 */
( function( jQuery ) {

	jQuery.fn.removeStyle = function( style ) {

		var search = new RegExp( style + '[^;]+;?', 'g' );

		return this.each( function() {

			jQuery( this ).attr( 'style', function( i, style ) {

				try {
					return style.replace( search, '' );
				} catch ( e ) {
					return '';
				}

			} );

		} );
	};

} ( jQuery ) );
