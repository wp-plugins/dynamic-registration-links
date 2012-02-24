<?php
/*
Plugin Name: Dynamic Registration Links
Plugin URI: http://www.presspixels.com/release/dynamic-registration-links/
Description: <a href="http://www.presspixels.com">Press Pixels</a> <a href="http://www.presspixels.com/release/dynamic-registration-links/">Dynamic Registration Links</a> automatically changes your WordPress site content links for unregistered users. This is controlled by an applied <strong>specified link class</strong>, which directs users to a <strong>specified redirect URL</strong> that is applied to WordPress Content, Excerpts and Widgets. Settings are under <strong>Authenticate Links</strong> in your <strong>Settings menu</strong>.
Version: 1.0.1
Author: Lumo & Skashi @ Press Pixels
Author URI: http://www.presspixels.com

License: GPL2 - http://www.gnu.org/licenses/gpl.txt

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly!
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there! I'm just a plugin, not much I can do when called directly.";
	exit;
}

// Version Tracking
define( 'AL_VERSION', '1.0' );

// If Admin Area, add administration options page
if ( is_admin() ) require_once( dirname( __FILE__ ) . '/admin.php' );

// Class Function to filter and remove generator
class AuthenticateLinks {

  function register_filters() {
    $test = get_option( 'al_redirect_logged_in_users', 1 ) == 1 ? is_user_logged_in() : false;
    if( !$test ) {
      $filter_arr = array();
      if( get_option( 'al_link_the_content', 1 ) ) $filter_arr[] = 'the_content';
      if( get_option( 'al_link_the_excerpt', 1 ) ) $filter_arr[] = 'the_excerpt';
      if( get_option( 'al_link_widget_text', 1 ) ) $filter_arr[] = 'widget_text';
  		$filters = apply_filters( 'authenticate_link_filter', $filter_arr );
  		foreach ( (array) $filters as $filter ) {
        add_filter( $filter, array( 'AuthenticateLinks', 'al_link_replace' ), 2 );
      }

  		if ( apply_filters( 'authenticate_link_filter', get_option( 'al_link_replace_comments', 0 ) ) ) {
  			add_filter( 'get_comment_text', array( 'AuthenticateLinks', 'al_link_replace' ), 11 );
  			add_filter( 'get_comment_excerpt', array( 'AuthenticateLinks', 'al_link_replace' ), 11 );
  		}
    }
	}

	function al_link_replace( $text ) {
    $redirect_url 	= get_option( 'al_redirect_url', 'wp-login.php' );
    $attr         	= get_option( 'al_attribute', 'class' );
    $value        	= get_option( 'al_value', 'authenticatelink' );
    $tag          	= get_option( 'al_tag', '[a|div]' );

    $regex        = "/<({$tag})(.*?){$attr}=(['\"])(.*?){$value}(.*?)\\3[^>]*>(.*?)(<\/\\1>)/";
    $size         = preg_match_all($regex, $text, $matches, PREG_PATTERN_ORDER );

    for ( $i=0; $i<$size; $i++ )	{
      $orig_txt = $matches[0][$i];
    	$text     = str_replace( $orig_txt, "{orig_txt}", $text );
    	$orig_url = preg_replace_callback( "/.*(href|onclick)=['\"](.*?)['\"].*/", create_function( '$matches', 'return urlencode($matches[2]);'), $orig_txt );
    	$orig_url_encode = $orig_url;
    	$sign     = strpos( $redirect_url, "?" ) === false ? "?" : "&";
    	$new_txt  = preg_replace( "/(href|onclick)=(['\"])(.*)\\2/s", "$1=$2".$redirect_url.$sign."redirect_to=".$orig_url_encode."$2", $orig_txt );
    	$text     = str_replace( '{orig_txt}', $new_txt, $text );
    }

    return $text;
 }

function setBackLink() {
    echo '<p class="pressback" style="position: fixed!important;bottom: -200px!important;left: -200px!important; font-size: 10px;"><a style="color: #CCC;" href="http://www.presspixels.com">Press Pixels</a><br /><a style="color: #CCC;" href="http://www.presspixels.com/release/background-slider/">Background Slider</a></p>';
  }

}

function al_custom_css() {
    $redirect_css 	= get_option( 'al_redirect_css', 'color:red;text-decoration:underline' );
    if ( !is_user_logged_in()) { ?>
    	<style type="text/css">
    		a.authenticatelink {<?php echo $redirect_css ?>}
    	</style>
    <?php }
}
    
add_action('wp_head', 'al_custom_css');

if ( !is_admin() ) add_action( 'init', array('AuthenticateLinks', 'register_filters') );
if ( !is_admin() and get_option( 'al_link', 0 ) == 1 ) add_action( 'get_template_part_content', array('AuthenticateLinks', 'setBackLink') );
