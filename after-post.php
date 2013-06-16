<?php 

/**
 * Handles the structure for the after-post widget
 *
 * @author Bit51
 * 
 */

if ( is_active_sidebar( 'after-post' ) ) {

	echo '<div id="after-post">' . PHP_EOL;
	echo '<h3>You might also like</h3>' . PHP_EOL;
	dynamic_sidebar( 'after-post' );
	echo '</div>' . PHP_EOL;
	
}
