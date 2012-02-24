<?php

// Footer Left and Right
add_filter('admin_footer_text', 'left_admin_footer_text_output'); //left side
function left_admin_footer_text_output($text) {
    $text = '<a href=\'http://www.presspixels.com/release/dynamic-registration-links/\' target=\'_blank\'>Dynamic Registration Links</a>. Auto Change your Site Links for Unregistered Users.';
    return $text;
}
 
add_filter('update_footer', 'right_admin_footer_text_output', 11); //right side
function right_admin_footer_text_output($text) {
    $text = 'A <a href=\'http://www.presspixels.com/\' target=\'_blank\'>Press Pixels</a> Plugin, built for WordPress by Lumo and Skashi.';
    return $text;
}

if (!class_exists('AuthenticateLinksAdmin')) {

  class AuthenticateLinksAdmin {

    var $_page_types = array(
        'al_redirect_url',
        'al_redirect_text',
        'al_redirect_css',
        'al_redirect_logged_in_users',
        'al_link_the_content',
        'al_link_the_excerpt',
        'al_link_widget_text',
        'al_link_replace_comments',
        'al_link'
    );

    // default constructor
    function AuthenticateLinksAdmin() {
      // Hook for adding Admin menus
      add_action('admin_menu', array(&$this, 'add_menu_options'));
    }

    // action function for above hook
    function add_menu_options() {
      $title = 'Authenticate Links';
      add_options_page($title, $title, 'manage_options', 'authenticatelinks', array(&$this, 'display'));
    }
   

    // display() displays the page content for the Remove Meta Generator settings submenu
    function display() {

      // See if the user has posted us some information
      // If they did, this hidden field will be set to 'Y'
      if (isset($_POST['al_submit_hidden']) and $_POST['al_submit_hidden'] == '1') {
        $page_types = $this->_page_types;
        for ($i = 0; $i < sizeof($page_types); $i++) {
          update_option($page_types[$i], $_REQUEST[$page_types[$i]]);
        }
        // Put an settings updated message on the screen
        echo '<div class="updated"><p><strong>' . __('settings saved.', 'menu-aladmin') . '</strong></p></div>';
      }
      // Now display the settings editing screen
      ?>
<div class="wrap" style="margin-top:20px">
	<div id="icon-link" class="icon32"><br /></div>
	<h2>Dynamic Registration Links</h2>
	<div class="postbox-container" style="width: 67%; margin-right: 2%; margin-top: 20px" >
		<div class="metabox-holder">
			<div class="inside">
				<form name="form1" method="post" action="">
          			<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Core Settings</span></h3>
						<div class="inside">
						<p><a href="http://www.presspixels.com">Press Pixels</a> <a href="http://www.presspixels.com/release/dynamic-registration-links/">Dynamic Registration Links</a> automatically changes your WordPress site content links for unregistered users. Add the class attribute <i>class="authenticatelink"</i> as shown below to force redirection to your specified URL.</p>
						<pre>&lt;a href="http://www.presspixels.com" <strong>class="authenticatelink"</strong>&gt;<span style="<?php echo get_option('al_redirect_css', 'color:red;text-decoration:underline')?>">Press Pixels!</span>&lt;/a&gt;</pre>
						
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row">
											<label for="twp_errmsg">Redirect URL</label>
										</th>
										<td>
											<input id="twp_errmsg" name="al_redirect_url" type="text" class="regular-text" value="<?php echo get_option('al_redirect_url', 'wp-login.php')?>" size="30">
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="twp_errmsg">Redirect CSS (Blank for None)</label>
										</th>
										<td>
											<input id="twp_errmsg" name="al_redirect_css" type="text" class="regular-text" value="<?php echo get_option('al_redirect_css', 'color:red;text-decoration:underline')?>" size="30">
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">Other Various Settings:</th>
										<td>
											<input id="al_redirect_logged_in_users" class="checkbox" type="checkbox" value="1" name="al_redirect_logged_in_users" <?php if( get_option('al_redirect_logged_in_users', 1) == 1 ) echo 'checked="true"';?>>
											<label for="al_redirect_logged_in_users">Replace URLs only when a user is not logged in</label>
											<br>
                      <input id="al_link_the_content" class="checkbox" type="checkbox" value="1" name="al_link_the_content" <?php if( get_option('al_link_the_content', 1) == 1 ) echo 'checked="true"';?>>
											<label for="al_link_the_content">Replace URLs in article posts</label>
											<br>
											<input id="al_link_the_excerpt" class="checkbox" type="checkbox" value="1" name="al_link_the_excerpt" <?php if( get_option('al_link_the_excerpt', 1) == 1 ) echo 'checked="true"';?>>
											<label for="al_link_the_excerpt">Replace URLs in the article post excerpt</label>
											<br>
											<input id="al_link_widget_text" class="checkbox" type="checkbox" value="1" name="al_link_widget_text" <?php if( get_option('al_link_widget_text', 1) == 1 ) echo 'checked="true"';?>>
											<label for="al_link_widget_text">Replace URLs in widget text</label>
											<br>
											<input id="al_link_replace_comments" class="checkbox" type="checkbox" value="1" name="al_link_replace_comments" <?php if( get_option('al_link_replace_comments', 1) == 1 ) echo 'checked="true"';?>>
											<label for="al_link_replace_comments">Replace URLs in comments</label>
											<br>
											<input id="al_link" class="checkbox" type="checkbox" value="1" name="al_link" <?php if( get_option('al_link', 1) == 1 ) echo 'checked="true"';?>>
											<label for="al_link">Show Press Pixels Link (Does not Effect Your Site)</label>

										</td>
									</tr>
								</tbody>
							</table>
							<input type="hidden" name="al_submit_hidden" value="1">
							<p class="submit"><input type="submit" name="Submit" class="button-save" value="<?php esc_attr_e('Save Changes') ?>" /></p>
						</div>
					</div>			 
				</form>	 
			</div>
		</div>
	</div>
          <div class="postbox-container" style="width: 30%; margin-top: 20px" >
				<div class="metabox-holder">
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Looking for Support?</span></h3>
						<div class="inside">
						 	<p>If you are having problems with this plugin and need support, please <a target="_blank" href="http://www.presspixels.com/wordpress-contact-press-pixels-support/" target="_blank">contact online</a> or alternatively <a href="mailto:hello@presspixels.com">send a mail</a> and we will help sort you out ASAP!</p>
						</div>
					</div>
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Press Pixels Info</span></h3>
						<div class="inside">
							<p>Press Pixels is all over the Web, you can keep focused with all the latest news at any of the Press Pixels pages listed below:</p>
							<p><a target="_blank" href="http://twitter.com/sitesolution" target="_blank">Twitter</a>&nbsp;&nbsp;<a target="_blank" href="http://www.facebook.com/presspixels" target="_blank">Facebook</a>&nbsp;&nbsp;<a target="_blank" href="http://feeds.feedburner.com/presspixels" target="_blank">RSS</a>&nbsp;&nbsp;<a target="_blank" href=“http://www.presspixels.com/release/background-slider/“>Slider</a>&nbsp;&nbsp;<a target="_blank" href="http://www.presspixels.com" target="_blank">Site</a></dt></p>
						</div>
					</div>
					<div class="postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Latest News from Press Pixels</span></h3>
						<div class="inside">
							<p>Checkout the latest news and updated content from the Press Pixels Team:</p>
							<?php // import rss feed
							if(function_exists('fetch_feed')) {
								$rss = fetch_feed('http://feeds.feedburner.com/presspixels');
								if(!is_wp_error($rss)) : // error check
									$maxitems = $rss->get_item_quantity(5); // number of items
									$rss_items = $rss->get_items(0, $maxitems);
								endif;
							?>
						<ul>
						<?php if($maxitems == 0) echo '<dt>Updating... more news soon!</dt>';
						else foreach ($rss_items as $item) : ?>
						<li>
							<a target="_blank" href="<?php echo $item->get_permalink(); ?>" 
							title="<?php echo $item->get_date('j F Y @ g:i a'); ?>">
							<?php echo $item->get_title(); ?>
							</a>
						</li>
						<?php endforeach; ?>
						</ul>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
      </div><!-- end wrap div -->
      <?php
    }

  }

  $authenticateLinksAdmin = new AuthenticateLinksAdmin();
}
