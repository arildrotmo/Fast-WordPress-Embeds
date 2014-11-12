<?php
/*
	Plugin Name: Fast WordPress Embeds
	Plugin URI: http://www.github.com/arildrotmo/fast-wordpress-embeds
	Description: This plugin will discover your embedded videos from YouTube and Vimeo, and replace them with a clickable thumbnail and a play button. Does NOT depend on jQuery. Also works without Javascript, the thumbnail is then an external link to the video. Uses <img sizes/srcset> for responsive images.
	Author: Arild Rotmo
	Author URI: http://www.arildrotmo.com
  License: GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Version: 0.1

  Fast WordPress Embeds is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
   
  Fast WordPress Embeds is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details : https://www.gnu.org/licenses/gpl-2.0.html .
*/

require( 'inc/fwe_embed.php' );

// Add embed-support to text widgets
add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );

function fwe_replaceembeds( $embedhtml ) {
  $fwe_embed = new Fwe_embed( $embedhtml );
  $op = get_option( 'fwe_settings' );

  if ( empty( $embedhtml ) || ! is_string( $embedhtml ) || ! $fwe_embed->data ) {
    return $embedhtml;
  }

  // Load inline styles and scripts only once.
  static $styles_scripts = FALSE;

  if( ! $styles_scripts ) {
    add_action( 'wp_footer', array( $fwe_embed, 'fwe_inline_script_footer' ) );
    $output = '<style>';

    // User-defined width :
    $output .= '.fwe_embed{width:' . cleanWidth($op['fwe_default_width']) . '}';
    
    $output .= file_get_contents( plugin_dir_url ( __FILE__ ) . 'css/fwe_embed.css' ) . '</style>';
    $styles_scripts = TRUE;
  }
  else $output = "";

  return $output . $fwe_embed->output();
}



/*
 *  Validate the preferred width against a regex for CSS length.
 *  Attempts to return a guess if its not valid.
 */

function cleanWidth($w) {

  $pattern = "/^(auto|0)$|^[+-]?[0-9]+\.?([0-9]+)?( )?(px|rem|em|vh|vw|vmin|vmax|ex|ch|%|in|cm|mm|pt|pc|mozmm)$/";

  if( preg_match( $pattern, $w, $m ) == 1 ) {
    return $m[0];
  }
  else {
    $length = preg_replace( "/[^0-9]/", "", $w );
    if( $length !== "" ) {
      return $length . "px";
    }
    else return "300px";
  }
  
}


function fwe_inline_script_head() {
  echo '<script>' . file_get_contents( plugin_dir_url( __FILE__ ) . '/js/picturefill.min.js' ) . '</script>';
}

add_action( 'wp_head', 'fwe_inline_script_head' );
add_filter( 'embed_oembed_html',  'fwe_replaceembeds' );

require ( 'admin/settings.php' );


/*
 *  Deactivation cleanup
 */
function fwe_deactivate() {
  unregister_setting( 'pluginPage', 'fwe_settings' );
  delete_option( 'fwe_settings' );

  // Multisite
  delete_site_option( 'fwe_settings' );
}

register_deactivation_hook( __FILE__, 'fwe_deactivate' );
