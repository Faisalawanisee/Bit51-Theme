<?php
/*
 WARNING: This file is part of the core Genesis framework. DO NOT edit
 this file under any circumstances. Please do all modifications
 in the form of a child theme.
 */

/**
 * This file handles the search results page.
 *
 * This file is a core Genesis file and should not be edited.
 *
 * @category Genesis
 * @package  Templates
 * @author   StudioPress
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL-2.0+
 * @link     http://www.studiopress.com/themes/genesis
 */

// Show only the post exceprt on search (for Relevanssi)
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
add_action( 'genesis_post_content', 'bit51_do_post_excerpt' );

function bit51_do_post_excerpt() {
	the_excerpt();
}

genesis();