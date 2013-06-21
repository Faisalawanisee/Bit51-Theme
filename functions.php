<?php
// Start the engine
require_once( get_template_directory() . '/lib/init.php' );

// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Bit51 Theme' );
define( 'CHILD_THEME_URL', 'http://bit51.com/' );

// Add Viewport meta tag for mobile browsers
add_action( 'genesis_meta', 'bit51_viewport_meta_tag' );

function bit51_viewport_meta_tag() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

// Add menu JavaScript
add_action( 'wp_enqueue_scripts', 'bit51_scripts' );

function bit51_scripts() {
	wp_enqueue_script(
		'bit51-js',
		get_stylesheet_directory_uri() . '/js/scripts.min.js'
	);
}

//Add FontAwesome
add_action( 'wp_enqueue_scripts', 'bit51_add_my_stylesheet' );
function bit51_add_my_stylesheet() {

	wp_register_style( 'bit51', get_stylesheet_directory_uri() . '/css/main.min.css' );
	wp_enqueue_style( 'bit51' );
	wp_register_style( 'fonts-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css' );
	wp_enqueue_style( 'fonts-awesome' );
	wp_register_style( 'gfonts', 'http://fonts.googleapis.com/css?family=Gudea:400,700|Arvo:700' );
	wp_enqueue_style( 'gfonts' );

}

//Customize read more link
add_filter( 'the_content_more_link', 'bit51_read_more_link' );

function bit51_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '"> [Continue Reading...]</a>';
}

// Modify post meta info
add_filter( 'genesis_post_info', 'bit51_post_info_filter' );

function bit51_post_info_filter( $post_info ) {

	if ( is_home() || ! is_page() || ! is_single() ) {
	
		$post_info = '[post_date format="F jS, Y"] [post_edit] [post_comments zero="Leave a Comment" one="1 Comment" more="% Comments" hide_if_off="false"]';
		
	}
	
	return $post_info;
	
}

// Register widget areas
genesis_register_sidebar( array(
	'id'			=>	'intro-left',
	'name'			=>	__( 'Intro Left', 'bit51' ),
	'description'	=>	__( 'The left column of the homepage featured items.', 'bit51' ),
) );
genesis_register_sidebar( array(
	'id'			=> 	'intro-center',
	'name'			=>	__( 'Intro Center', 'bit51' ),
	'description'	=>	__( 'The left column of the homepage featured items.', 'bit51' ),
) );
genesis_register_sidebar( array(
	'id'			=>	'intro-right',
	'name'			=>	__( 'Intro Right', 'bit51' ),
	'description'	=>	__( 'This is the featured section displayed below the intro.', 'bit51' ),
) );

// Remove after post meta
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

// Edit comment form
add_filter( 'genesis_comment_form_args', 'bit51_comment_form_args' );

// Remove secondary sidebar
unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar-alt' );


function bit51_comment_form_args( $args ) {

	$args['title_reply'] = 'Join The Conversation';

	return $args;

}

// Register and add after post widget area
genesis_register_sidebar( 
	array(
		'id'			=> 'after-post',
		'name'			=> __( 'After Post', 'bit51' ),
		'description'	=> __( 'This is the section after a post.', 'bit51' ),
	) 
);

add_action( 'genesis_before_comments', 'bit51_after_content' );

function bit51_after_content() {

	if ( is_single() ) {

		require( CHILD_DIR . '/after-post.php' );

	}

}

// Modify the Author Box
add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );
add_filter( 'get_the_author_genesis_author_box_archive', '__return_true' );
remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );
add_action( 'genesis_before_comments', 'bit51_author_box_single' );
remove_action( 'genesis_before_loop', 'genesis_do_author_box_archive' );
add_action( 'genesis_before_loop', 'bit51_author_box_archive' );

function bit51_author_box_single() {
	if ( is_single() ) {
		
		bit51_author_box();

		echo '<div id="post-comments">';

	}

}

function bit51_author_box_archive() {
	if ( is_author() ) {
		bit51_author_box();
	}
}

function bit51_author_box() {

	$links = array(
		'facebook' 		=> get_the_author_meta( 'facebook' ),
		'linkedin' 		=> get_the_author_meta( 'linkedin' ),
		'twitter' 		=> get_the_author_meta( 'twitter' ),
		'google' 	=> get_the_author_meta( 'googleplus' ),
	);

	$profiles = array();

	foreach ( $links as $link => $url ) {

		if ( strstr( $url, 'http' ) ) {
			$url = parse_url( $url, PHP_URL_PATH );
			$url = substr( $url, 1 );
		}

		$profiles[$link]['url'] = $url;
		$profiles[$link]['length'] = strlen( $url );

	}

	@ini_set( 'auto_detect_line_endings', true );

	$authinfo =  '<div class="author-box">' . PHP_EOL;
	$authinfo .= get_avatar( get_the_author_id() , 80 );

	if ( strlen( get_the_author_meta( 'url' ) ) > 1 ) {
		$authinfo .= '<strong>About <a href="' . get_the_author_meta( 'url' ) . '" target="_blank" title="' . get_the_author_meta( 'website_title' ) . '">' . get_the_author_meta( 'display_name' ) . '</a></strong>' . PHP_EOL;
	} else {
		$authinfo .= '<strong>About ' . get_the_author_meta( 'display_name' ) . '</strong>' . PHP_EOL;
	}

	$authinfo .= '<p>' . get_the_author_meta( 'description' ) . '</p>' . PHP_EOL;
   
	if ( $profiles['facebook']['length'] > 1 || $profiles['linkedin']['length'] > 1 || $profiles['twitter']['length'] > 1 || $profiles['google']['length'] > 1 ) {

		if ( ( $profiles['facebook']['length'] <= 1 && $profiles['google']['length'] <= 1 && $profiles['linkedin']['length'] <= 1 ) && $profiles['twitter']['length'] > 1 ) {

			$authinfo .= '<p id="authcontact">Follow ' . get_the_author_meta( 'first_name' ) . ' on <a href="http://twitter.com/' . $profiles['twitter']['url'] . '" target="_blank" title="' . get_the_author_meta( 'display_name' ) . ' on Twitter">Twitter</a></p>' . PHP_EOL;

		} else {

			$authinfo .= '<p id="authcontact">Find ' . get_the_author_meta( 'first_name' ) . ' on ';

			if ( $profiles['facebook']['length'] > 1 ) {
				$authinfo .= ' <a href="http://facebook.com/' . $profiles['facebook']['url'] . '" target="_blank" title="' . get_the_author_meta( 'display_name' ) . ' on Facebook">Facebook</a>';
			}

			if ( $profiles['google']['length'] > 1 ) {

				$comma = $profiles['facebook']['length'] > 1 ? ',' : '';
				$and = $profiles['facebook']['length'] > 1 && ( $profiles['linkedin']['length'] <= 1 || $profiles['twitter']['length'] <= 1 ) ? ' and' : '';
				
				$authinfo .= $comma . $and . ' <a href="http://plus.google.com/' . $profiles['google']['url'] . '?rel=author" rel="author" target="_blank" title="' . get_the_author_meta( 'display_name' ) . ' on Google+">Google+</a>';

			}

			if ( $profiles['linkedin']['length'] > 1 ) {

				$comma = $profiles['facebook']['length'] > 1 || $profiles['google']['length'] > 1 ? ',' : '';
				$and = ( $profiles['facebook']['length'] > 1 || $profiles['google']['length'] > 1 ) && $profiles['twitter']['length'] <= 1 ? ' and' : '';

				$authinfo .= $comma . $and . ' <a href="http://www.linkedin.com/in/' . $profiles['linkedin']['url'] . '" target="_blank" title="' . get_the_author_meta( 'display_name' ) . ' on LinkedIn">LinkedIn</a>';

			}

			if ( $profiles['twitter']['length'] > 1 ) {
				$authinfo .= ', and <a href="http://twitter.com/' . $profiles['twitter']['url'] . '" target="_blank" title="' . get_the_author_meta( 'display_name' ) . ' on Twitter">Twitter</a>';
			}

			$authinfo .= '.</p>' . PHP_EOL;

		}

	}

	$authinfo .= '</div>' . PHP_EOL;

	echo $authinfo;

}

//Add comment policies
add_action( 'genesis_after_comment_form', 'bit51_after_comment_box' );

function bit51_after_comment_box() {

	if ( is_single() ) {

		echo '<p class="comment-policy">Please review our <a href="http://bit51.com/policies-and-disclosures/#comments">Comment Policy</a> and our <a href="http://bit51.com/policies-and-disclosures/#privacy">Privacy Policy</a> before posting.</p>';
		echo '</div>';

	}

}

//Customize the footer credits
add_filter( 'genesis_footer_creds_text', 'custom_footer_creds_text' );

function custom_footer_creds_text( $creds ) {
	
	$creds = '&copy; 2011-' . date( 'Y', time() ) . ' Bit51 - <a href="http://creativecommons.org/licenses/by-nc/3.0/deed.en_US" target="_blank">Creative Commons Licensed</a>. Built on <a href="http://b51.co/SPGenesis" target="_blank">Genesis</a>.';
	
	return $creds;
	
}

//Customize return to top of page text
add_filter( 'genesis_footer_backtotop_text', 'bit51_footer_backtotop_text' );
function bit51_footer_backtotop_text( $backtotop ) {

	$backtotop = '[footer_backtotop text="Go To Top"]';

	return $backtotop;

}

//Customize search text
add_filter( 'genesis_search_text', 'bit51_custom_search_text' );

function bit51_custom_search_text( $text ) {
	return esc_attr( 'Search Bit51' );
}

//Remove widgets
add_action( 'widgets_init', 'bit51_remove_genesis_widgets', 20 );

function bit51_remove_genesis_widgets() {
	unregister_widget( 'Genesis_eNews_Updates' );
	unregister_widget( 'Genesis_Featured_Page' );
	unregister_widget( 'Genesis_User_Profile_Widget' );
	unregister_widget( 'Genesis_Menu_Pages_Widget' );
	unregister_widget( 'Genesis_Widget_Menu_Categories' );
	unregister_widget( 'Genesis_Featured_Post' );
	unregister_widget( 'Genesis_Latest_Tweets_Widget' );
}

// Remove extra layouts
 genesis_unregister_layout( 'sidebar-content' );
 genesis_unregister_layout( 'content-sidebar-sidebar' );
 genesis_unregister_layout( 'sidebar-sidebar-content' );
 genesis_unregister_layout( 'sidebar-content-sidebar' );

// Custom right nav
add_filter( 'genesis_nav_items', 'bit51_right_nav', 10, 2 );
add_filter( 'wp_nav_menu_items', 'bit51_right_nav', 10, 2 );

function bit51_right_nav( $menu, $args ) {

	$args = ( array ) $args;

	if ( 'primary' !== $args['theme_location'] ) {
		return $menu;
	}

	$follow = '<li class="social-share"><a href="http://plus.google.com/111800087192533843819" target="_blank" title="Find Bit51 on Google+"><i class="icon-google-plus icon-2x"></i></a></li>' . PHP_EOL
		. '<li class="social-share"><a href="https://github.com/Bit51" target="_blank" title="View Bit51s Code on Github"><i class="icon-github icon-2x"></i></a></li>' . PHP_EOL
		. '<li class="social-share"><a href="http://facebook.com/bit51" target="_blank" title="Like Bit51 on Facebook"><i class="icon-facebook icon-2x"></i></a></li>' . PHP_EOL
		. '<li class="social-share"><a href="http://twitter.com/Bit51" target="_blank" title="Follow Bit51 on Twitter"><i class="icon-twitter icon-2x"></i></a></li>' . PHP_EOL
		. '<li class="social-share"><a href="http://feeds.bit51.com/site/" target="_blank" title="Subscribe via RSS"><i class="icon-rss icon-2x"></i></a></li>' . PHP_EOL;

	return $menu . $follow;

}

// Modify the Comments
remove_action( 'genesis_list_comments', 'genesis_default_list_comments' );
add_action( 'genesis_list_comments', 'bit51_default_list_comments' );
add_filter( 'genesis_title_comments', 'bit51_title_comments' );

function bit51_title_comments() {
	$title = '<h3>Discussion</h3>';
	return $title;
}

function bit51_default_list_comments() {

	$args = array(
		'type'			=> 'comment',
		'avatar_size'	=> 48,
		'callback'		=> 'bit51_comment_callback',
	);

	$args = apply_filters( 'genesis_comment_list_args', $args );

	wp_list_comments( $args );

}

function bit51_comment_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

		<?php do_action( 'genesis_before_comment' ); ?>

		<div class="comment-avatar"><?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?></div>

		<div class="comment-body">
			<div class="comment-header">
				<span class="comment-author vcard">
					<?php printf( __( '<cite class="fn">%s</cite> ', 'genesis' ), get_comment_author_link() ); ?>
				</span><!-- end .comment-author -->

				<span class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php echo human_time_diff( get_comment_time('U'), current_time('timestamp')) . " " . __('ago'); ?></a>
					<?php edit_comment_link( __( '(Edit)', 'genesis' ), '' ); ?>
				</spam><!-- end .comment-meta -->
			</div>


			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'genesis' ) ); ?></p>
				<?php endif; ?>

				<?php comment_text(); ?>
			</div><!-- end .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>

		</div>

		<?php do_action( 'genesis_after_comment' );

}

//customize the header
remove_action(	'genesis_header', 'genesis_do_header' );
remove_action(	'genesis_header', 'genesis_header_markup_open', 5 );
remove_action(	'genesis_header', 'genesis_header_markup_close', 15 );
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'bit51_do_nav' );

function bit51_do_nav() {

	/** Do nothing if menu not supported */
	if ( ! genesis_nav_menu_supported( 'primary' ) )
		return;

	/** If menu is assigned to theme location, output */
	if ( has_nav_menu( 'primary' ) ) {

		$args = array(
			'theme_location' => 'primary',
			'container'      => '',
			'menu_class'     => genesis_get_option( 'nav_superfish' ) ? 'menu genesis-nav-menu menu-primary superfish' : 'menu genesis-nav-menu menu-primary',
			'echo'           => 0,
		);

		$nav = wp_nav_menu( $args );

		$pattern = genesis_markup( '<nav class="primary">%2$s%1$s%3$s</nav>', '<div id="nav">%2$s<div id="logo"><a href="http://bit51.com" title="Bit51"><img src="' . get_stylesheet_directory_uri() . '/images/logo.png" alt="Bit51" width="71" height="30"></a></div>%1$s%3$s</div>', 0 );

		$nav_output = sprintf( $pattern, $nav, genesis_structural_wrap( 'nav', 'open', 0 ), genesis_structural_wrap( 'nav', 'close', 0 ) );

		echo apply_filters( 'genesis_do_nav', $nav_output, $nav, $args );

	}

}

//clear the posts
add_action( 'genesis_after_post_content', 'bit51_after_post_content' );

function bit51_after_post_content() {
	echo '<div class="clear"></div>';
}

//Customize Breadcrumbs
add_filter('genesis_breadcrumb_args', 'bit51_breadcrumb_args');

function bit51_breadcrumb_args( $args ) {
	$args['home'] = 'Bit51 Home';
	$args['sep'] = ' | ';
	$args['list_sep'] = ', '; // Genesis 1.5 and later
	$args['prefix'] = '<div class="breadcrumb">';
	$args['suffix'] = '</div>';
	$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
	$args['heirarchial_categories'] = true; // Genesis 1.5 and later
	$args['display'] = true;
	$args['labels']['prefix'] = 'You are here: ';
	$args['labels']['author'] = 'Archives for ';
	$args['labels']['category'] = ''; // Genesis 1.6 and later
	$args['labels']['tag'] = 'Archives for ';
	$args['labels']['date'] = 'Archives for ';
	$args['labels']['search'] = 'Search for ';
	$args['labels']['tax'] = 'Archives for ';
	$args['labels']['post_type'] = '';
	$args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
	return $args;
}
