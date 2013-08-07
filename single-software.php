<?php
/**
 * Details of a specific software package
 */

if ( ! class_exists( 'bit51_software_page' ) ) {

	class bit51_software_page {

		private $spage, 
			$spages,
			$sfaq;

		function __construct() {

			global $software_pages, $post;

			$this->spages = $software_pages;
			$this->spage = get_query_var( 'spage' );
			$this->sfaq = get_field( 'faq_question' );

			//process subpages if necessary
			if ( ! empty( $this->spage ) ) {

				switch( $this->spage ) {

					case 'changelog':

						if ( strlen( get_post_meta( $post->ID, '_bit51_changelog', true ) ) <= 1 ) {
							
							if ( strlen( get_post_meta( $post->ID, '_bit51_changelog_url', true ) ) > 1 ) {
								wp_redirect( get_post_meta( $post->ID, '_bit51_changelog_url', true ), 301 );
							} else {
								$this->bit51_notfound();
							}

						}

						break;

					case 'donate':

						if ( strlen( get_post_meta( $post->ID, '_bit51_donate', true ) ) <= 1 ) {
							
							if ( strlen( get_post_meta( $post->ID, '_bit51_donate_url', true ) ) > 1 ) {
								wp_redirect( get_post_meta( $post->ID, '_bit51_donate_url', true ), 301 );
							} else {
								$this->bit51_notfound();
							}

						}

						break;

					case 'faq':

						if ( ! is_array( $this->sfaq ) || sizeof( $this->sfaq ) < 1 ) {
							$this->bit51_notfound();
						}

						break;

					case 'support':

						if ( strlen( get_post_meta( $post->ID, '_bit51_support', true ) ) <= 1 ) {
							
							if ( strlen( get_post_meta( $post->ID, '_bit51_support_url', true ) ) > 1 ) {
								wp_redirect( get_post_meta( $post->ID, '_bit51_support_url', true ), 301 );
							} else {
								$this->bit51_notfound();
							}

						}

						break;

				}

				//Include Advanced Custom Fields Repeater
				add_action( 'acf/register_fields', array( &$this, 'bit51_register_fields' ) );


				/** Customize the post title **/
				remove_action( 'genesis_post_title', 'genesis_do_post_title' );
				add_action( 'genesis_post_title', array( &$this, 'bit51_do_post_title' ) );

				/** Customize the breadcrumb **/
				add_filter( 'genesis_single_crumb', array( &$this, 'bit51_add_blog_crumb' ), 10, 2 );
				add_filter( 'genesis_archive_crumb', array( &$this, 'bit51_add_blog_crumb' ), 10, 2 );

			}

			/** Remove the post info function */
			remove_action( 'genesis_before_post_content', 'genesis_post_info' );

			/** Remove the author box on single posts */
			remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );

			/** Remove the post meta function */
			remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

			/** Remove the comments template */
			remove_action( 'genesis_after_post', 'genesis_get_comments_template' );

			/** Customize the content */
			remove_action( 'genesis_post_content', 'genesis_do_post_content' );
			add_action( 'genesis_post_content', array( &$this, 'bit51_content' ) );

			/** Execute Genesis **/
			genesis();

		}

		/**
		 * Registers Advanced Custom Fields Repeater Integration
		 * @return void
		 */
		function bit51_register_fields() {
			include_once( 'acf-repeater/repeater.php' );
		}

		/**
		 * Replace the page title for subpages
		 * @return null|void	Null on failure void on success
		 */
		function bit51_do_post_title() {

			$title = apply_filters( 'genesis_post_title_text', get_the_title() );

			if ( 0 == strlen( $title ) )
				return;

			$title = sprintf( '<h1 class="entry-title">%s - %s</h1>', $title, $this->spages[$this->spage] );
			
			echo apply_filters( 'genesis_post_title_output', "$title \n" );

		}

		/**
		 * Add breadcrumb level for subpages
		 * @param  string $crumb	existing breadvrumbs
		 * @param  array $args 		breadcrumb arguments
		 * @return string			sting with new breadcrumb
		 */
		function bit51_add_blog_crumb( $crumb, $args ) {

			return '<a href="' . get_bloginfo( 'url' ) . '/software/" title="View all Software">Software</a> / <a href="' . get_permalink( $post->ID ) . '" title="">' . get_the_title( $post->ID ) . '</a> / ' . $this->spages[$this->spage];

		}

		/**
		 * Display page and subpage content
		 * @return void
		 */
		function bit51_content() {
			global $post, $bit51_utilities;

			$paypal = get_post_meta( $post->ID, '_bit51_paypal', true );

			if ( ! empty( $this->spage ) ) {

				if ( strlen( $paypal ) > 1 ) {

					echo '<div class="software-paypal">';

						echo 'Have you found this software useful? Please help support it\'s continued development with a donation of $20, $50, or even $100.';
						echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="' . $paypal . '"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>';
						echo '<p class="recurring">You can even <a href="' . ( ( strlen( get_post_meta( $post->ID, '_bit51_donate_url', true ) ) > 1 ) ? get_post_meta( $post->ID, '_bit51_donate_url', true ) : ( get_bloginfo( 'url' ) . '/donate' ) ) . '">make a recurring donation.</a></p>';
						echo '<p>Short on funds?</p>';
						echo '<ul>';
						echo '<li><a href="' . get_post_meta( $post->ID, '_bit51_meta_url', true ) . '" target="_blank">Rate ' . get_the_title( $post->ID ) . ' 5★\'s</a></li>';
						echo '<li>Talk about it on your site and link back to <a href="' . get_permalink( $post->ID ) . '" target="_blank">this page.</a></li>';
						echo '<li><a href="http://twitter.com/home?status=' . urlencode( 'I use ' . get_the_title( $post->ID ) . ' by @Bit51 and you should too - ' . get_permalink( $post->ID ) ) . '" target="_blank">Tweet about it.</a></li>';
						echo '</ul>';

					echo '</div>';

				}
			
				switch ( $this->spage ) {

					case 'changelog':

						echo '<div class="software-description">';
						echo apply_filters( 'the_content', get_post_meta( $post->ID, '_bit51_changelog', true ) );
						echo '<div class="clear"></div>';
						echo '</div>';
						break;

					case 'donate':

						echo '<div class="software-description">';
						echo apply_filters( 'the_content', get_post_meta( $post->ID, '_bit51_donate', true ) );
						echo '<div class="clear"></div>';
						echo '</div>';
						break;	

					case 'faq':

						echo '<div class="software-description software-faq">';
						echo '<ul>';

						foreach ( $this->sfaq as  $faq ) {

							echo '<li class="faq">';
							echo '<h2>' . $faq['question'] . '</h2>';
							echo $faq['answer'];
							echo '</li>';

						}

						echo '</ul>';
						echo '<div class="clear"></div>';
						echo '</div>';
						break;	

					case 'support':

						echo '<div class="software-description">';
						echo apply_filters( 'the_content', get_post_meta( $post->ID, '_bit51_support', true ) );
						echo '<div class="clear"></div>';
						echo '</div>';
						break;

					default:
						echo '<div class="software-description">';
						echo '<p>' . __( 'That page could not be found', 'bit51' ) . '</p>';
						echo '<div class="clear"></div>';
						echo '</div>';
						break;

				}

			} else {

				//Get the plugin meta information
				$meta = $bit51_utilities->get_plugin_data( $post->ID );
				
				// display plugin meta information
				echo '<div id="software-meta">';
					if( isset( $meta['Rating'] ) && isset( $meta['Votes'] ) ) {
						echo '<div class="software-meta-table">';
							echo '<div class="leftcol">Version:</div>';
							echo '<div class="software-version rightcol">';
								echo $meta['Version'];
							echo '</div>';
							echo '<div class="leftcol">Downloads:</div>';
							echo '<div class="software-downloads rightcol">';
								echo $meta['Downloads'];
							echo '</div>';
							echo '<div class="leftcol">Rating:</div>';
							echo '<div class="software-rating rightcol" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
								echo '<div class="rating-stars">';
									echo '<div class="rating" style="width: ' . ( $meta['Rating'] / 5 ) * 100 . '%;">';
										echo '<meta itemprop="ratingValue" content="' . $meta['Rating'] . '">';
										echo '<meta itemprop="reviewCount" content="' . $meta['Votes'] . '">';
									echo '</div>';
								echo '</div>';
								echo  '(' . $meta['Rating'] . '/5)';
							echo '</div>';
							if ( strlen( $meta['Votes'] ) > 1 ) {
								echo '<div class="leftcol"># of Ratings:</div>';
								echo '<div class="software-votes rightcol">';
									echo $meta['Votes'];
								echo '</div>';
							}
						echo '</div>';
					}
					echo '<div class="software-links">';

						echo '<a class="software-download btn" href="' . get_post_meta( $post->ID, '_bit51_download_url', true ) . '" title="Download ' . get_the_title() . '" target="_blank" >Download</a>';

						if ( is_array( $this->sfaq ) && sizeof( $this->sfaq ) >= 1 ) {
							echo '<a class="software-faq btn" href="' .  get_permalink( $post->ID ) . 'faq" title="FAQ ' . get_the_title() . '" >FAQ</a>';
						}

						if ( strlen( get_post_meta( $post->ID, '_bit51_support', true ) ) > 1 ) {
							echo '<a class="software-support btn" href="' .  get_permalink( $post->ID ) . 'support" title="' . get_the_title() . ' - Support" >Support</a>';
						} elseif( strlen( get_post_meta( $post->ID, '_bit51_support_url', true ) ) > 1 ) {
							echo '<a class="software-support btn" href="' . get_post_meta( $post->ID, '_bit51_support_url', true ) . '" title="' . get_the_title() . ' - Support" >Support</a>';
						}
						
						if ( strlen( get_post_meta( $post->ID, '_bit51_changelog', true ) ) > 1 ) {
							echo '<a class="software-changelog btn" href="' .  get_permalink( $post->ID ) . 'changelog" title="' . get_the_title() . ' - Changelog" >Changelog</a>';
						} elseif( strlen( get_post_meta( $post->ID, '_bit51_changelog_url', true ) ) > 1 ) {
							echo '<a class="software-changelog btn" href="' . get_post_meta( $post->ID, '_bit51_changelog_url', true ) . '" title="' . get_the_title() . ' - Changelog" >Changelog</a>';
						}

						if ( strlen( get_post_meta( $post->ID, '_bit51_donate', true ) ) > 1 ) {
							echo '<a class="software-donate btn" href="' .  get_permalink( $post->ID ) . 'donate" title="' . get_the_title() . ' - Donate" >Donate</a>';
						} elseif( strlen( get_post_meta( $post->ID, '_bit51_donate_url', true ) ) > 1 ) {
							echo '<a class="software-donate btn" href="' . get_post_meta( $post->ID, '_bit51_donate_url', true ) . '" title="' . get_the_title() . ' - Donate" >Donate</a>';
						}

					echo '</div>';
				echo '</div>';

				if ( strlen( $paypal ) > 1 ) {

					echo '<div class="software-paypal">';

						echo 'Have you found this software useful? Please help support it\'s continued development with a donation of $20, $50, or even $100.';
						echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="' . $paypal . '"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>';
						echo '<p class="recurring">You can even <a href="' . ( ( strlen( get_post_meta( $post->ID, '_bit51_donate_url', true ) ) > 1 ) ? get_post_meta( $post->ID, '_bit51_donate_url', true ) : ( get_bloginfo( 'url' ) . '/donate' ) ) . '">make a recurring donation.</a></p>';
						echo '<p>Short on funds?</p>';
						echo '<ul>';
						echo '<li><a href="' . get_post_meta( $post->ID, '_bit51_meta_url', true ) . '" target="_blank">Rate ' . get_the_title( $post->ID ) . ' 5★\'s</a></li>';
						echo '<li>Talk about it on your site and link back to <a href="' . get_permalink( $post->ID ) . '" target="_blank">this page.</a></li>';
						echo '<li><a href="http://twitter.com/home?status=' . urlencode( 'I use ' . get_the_title( $post->ID ) . ' by @Bit51 and you should too - ' . get_permalink( $post->ID ) ) . '" target="_blank">Tweet about it.</a></li>';
						echo '</ul>';

					echo '</div>';

				}
				
				// Display the content, if the content editor has content
				if( $post->post_content !== '' ) {
					echo '<div class="software-description">';
						the_content();
					echo '<div class="clear"></div>';
					echo '</div>';
				}

			}
		}

		/**
		 * Sets proper 404 error when subpage not found
		 * @return void
		 */
		function bit51_notfound() {

			global $wp_query;

			$wp_query->set_404();
			status_header( 404 );
			get_template_part( 404 );

			exit();
		
		}

	}

}

new bit51_software_page();
