<?php
/*
	Plugin Name: Fast WordPress Embeds
	Plugin URI: http://www.github.com/arildrotmo/fast-wordpress-embeds
	Description: This plugin will discover your embedded videos from YouTube and Vimeo, and replace them with a clickable thumbnail and a play button. Also works without Javascript, the thumbnail is then an external link to the video.
	Author: Arild Rotmo
	Author URI: http://www.arildrotmo.com
  License: GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Version: 0.1

  Fast WordPress Embeds is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
   
  Fast WordPress Embeds is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details : https://www.gnu.org/licenses/gpl-2.0.html .
*/

require( 'inc/fwe_embed.php' );

function fwe_replaceembeds( $embedhtml ) {
  $fwe_embed = new Fwe_embed( $embedhtml );

  if ( empty( $embedhtml ) || ! is_string( $embedhtml ) || ! $fwe_embed->data ) {
    return $embedhtml;
  }

  static $styles_scripts = FALSE;

  if( ! $styles_scripts ) {
    add_action( 'wp_footer', array( $fwe_embed, 'fwe_inline_script_footer' ) );
    $output = '<style>' . file_get_contents( plugin_dir_url ( __FILE__ ) . 'css/fwe_embed.css' ) . '</style>';
    $styles_scripts = TRUE;
  }
  else $output = "";

  return $output . $fwe_embed->output();
}


function fwe_inline_script_head() {
  echo '<script>' . file_get_contents( plugin_dir_url( __FILE__ ) . '/js/picturefill.min.js' ) . '</script>';
}

add_action( 'wp_head', 'fwe_inline_script_head' );
add_filter( 'embed_oembed_html',  'fwe_replaceembeds' );

require ( 'admin/settings.php' );
