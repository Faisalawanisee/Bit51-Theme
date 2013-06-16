<?php
/**
 * Shows a list of all available software
 */
 
 // Add a custom post loop
remove_action ( 'genesis_loop', 'genesis_do_loop' ); 
add_action( 'genesis_loop', 'bit51_software_loop' ); 
 
function bit51_software_loop() {
    	
	// Intro Text (from page content)
	echo '<div class="page hentry entry">';
	echo '<h1 class="entry-title">Software</h1>';
	echo '<div class="entry-content">';

	echo '<h2 class="software-category">Google Chrome Extensions</h2>';
 
	$args = array(
		'post_type'			=> 'software',
		'orderby'			=> 'title',
		'order'				=> 'ASC',
		'posts_per_page'	=> '12',
		'tax_query'			=> array(
			array(
				'taxonomy'			=> 'software_type',
				'field'				=> 'slug',
				'terms'				=> 'google-chrome-extension'
			)
		)
	);

	$loop = new WP_Query( $args );

	if( $loop->have_posts() ) {
				
		while( $loop->have_posts() ) {

			$loop->the_post(); 

			global $post, $bit51_utilities;

			$meta = $bit51_utilities->get_plugin_data( $post->ID );
 			
			echo '<div class="software-short">';
				echo '<h3 class="software-title"><a href="' . get_permalink( $post->ID ) . '">' . get_the_title() . '</a></h3>';
				echo '<div class="software-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><span class="rated">Rated:</span> ';
					echo '<div class="rating-stars">';
						echo '<div class="rating" style="width: ' . ( $meta['Rating'] / 5 ) * 100 . '%;">';
							echo '<meta itemprop="ratingValue" content="' . $meta['Rating'] . '">';
							echo '<meta itemprop="reviewCount" content="' . $meta['Votes'] . '">';
						echo '</div>';
					echo '</div>';
					echo  $meta['Rating'] . '/5 with ' . $meta['Votes'] . ' votes and ' . $meta['Downloads'] . ' downloads.';
				echo '</div>';
				echo '<div class="software-excerpt">' . get_the_excerpt() . "</div>";
				echo '<div class="software-links">';
					echo '<a class="software-info" href="' . get_permalink( $post->ID ) . '" title="' . get_the_title() . ' - More Information" ><i class="icon-info-sign"></i> More Info</a>';
					echo '<a class="software-download" href="' . get_post_meta( $post->ID, '_bit51_download_url', true ) . '" title="Download ' . get_the_title() . '" target="_blank" ><i class="icon-cloud-download"></i> Download</a>';
				echo '</div>';
			echo '</div>';
		
		}
		
	}

	echo '<h2 class="software-category">WordPress Plugins</h2>';
 
	$args = array(
		'post_type'			=> 'software',
		'orderby'			=> 'title',
		'order'				=> 'ASC',
		'posts_per_page'	=> '12',
		'tax_query'			=> array(
			array(
				'taxonomy'			=> 'software_type',
				'field'				=> 'slug',
				'terms'				=> 'wordpress-plugin'
			)
		)
	);

	$loop = new WP_Query( $args );

	if( $loop->have_posts() ) {
				
		while( $loop->have_posts() ) {

			$loop->the_post(); 

			global $post, $bit51_utilities;

			$meta = $bit51_utilities->get_plugin_data( $post->ID );
 			
			echo '<div class="software-short">';
				echo '<h3 class="software-title"><a href="' . get_permalink( $post->ID ) . '">' . get_the_title() . '</a></h3>';
				echo '<div class="software-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><span class="rated">Rated:</span> ';
					echo '<div class="rating-stars">';
						echo '<div class="rating" style="width: ' . ( $meta['Rating'] / 5 ) * 100 . '%;">';
							echo '<meta itemprop="ratingValue" content="' . $meta['Rating'] . '">';
							echo '<meta itemprop="reviewCount" content="' . $meta['Votes'] . '">';
						echo '</div>';
					echo '</div>';
					echo  $meta['Rating'] . '/5 with ' . $meta['Votes'] . ' votes and ' . $meta['Downloads'] . ' downloads.';
				echo '</div>';
				echo '<div class="software-excerpt">' . get_the_excerpt() . "</div>";
				echo '<div class="software-links">';
					echo '<a class="software-info" href="' . get_permalink( $post->ID ) . '" title="' . get_the_title() . ' - More Information" ><i class="icon-info-sign"></i> More Info</a>';
					echo '<a class="software-download" href="' . get_post_meta( $post->ID, '_bit51_download_url', true ) . '" title="Download ' . get_the_title() . '" target="_blank" ><i class="icon-cloud-download"></i> Download</a>';
				echo '</div>';
			echo '</div>';
		
		}
		
	}
	
	//Closing text
	echo '</div><!-- end .entry-content -->';
	echo '</div><!-- end .page .hentry .entry -->';
}
	
// Remove standard post info
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
 
genesis();