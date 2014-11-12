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
global $wp_embed;
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

  $output = $fwe_embed->output( $styles_scripts );

  $styles_scripts = TRUE;

  return $output;
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
