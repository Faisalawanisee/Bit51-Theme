<?php

add_action( 'genesis_meta', 'bit51_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function bit51_home_genesis_meta() {
	
	global $paged;
	
	if( $paged < 1 ) {

		if ( is_active_sidebar( 'intro-left' ) || is_active_sidebar( 'intro-center' ) || is_active_sidebar( 'into-right' ) ) {
			add_action( 'genesis_before_content', 'bit51_home_loop_helper', 1 );
		}

	}
	
}

function bit51_home_loop_helper() {
		
		echo '<div id="home-featured">';

		if ( is_active_sidebar( 'intro-left' ) ) {
		
			genesis_widget_area( 'intro-left', array( 
			
				'before'	=>	'<div class="intro-left widget-area"><div class="inner">',
				'after'		=>	'<div class="clear"></div></div></div><!-- end .intro-left -->' 
			
			) );

		}

		if ( is_active_sidebar( 'intro-left' ) || is_active_sidebar( 'into-right' ) ) {
			$centerclass = 'intro-center';
		} else {
			$centerclass = 'intro-full';
		}
		genesis_widget_area( 'intro-center', array( 
		
			'before'	=> 	'<div class="' . $centerclass . ' widget-area"><div class="inner">', 
			'after'		=>	'<div class="clear"></div></div></div><!-- end .intro-center -->' 
		
		) );

		if ( is_active_sidebar( 'intro-right' ) ) {
		
			genesis_widget_area( 'intro-right', array(
			 
				'before'	=>	'<div class="intro-right widget-area"><div class="inner">', 
				'after'		=>	'<div class="clear"></div></div></div><!-- end .intro-right -->' 
				
			) );

		}

		echo '<div class="clear"></div>';
		echo '</div>';
		
}

genesis();