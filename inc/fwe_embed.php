<?php

class Fwe_embed {

  private $html;
  public $data;

  function __construct( $html ) {
    $this->html = $html;
    $this->data = $this->get_embeddata();
  }

  private function get_embeddata() {

    if ( strpos( $this->html, "vimeo.com" ) !== FALSE ) {
      require_once( '/providers/vimeo.php' );
      return new Vimeo_embed( $this->html );
    }

    else if ( strpos( $this->html, "youtube.com" ) !== FALSE ) {
      require_once( '/providers/youtube.php' );
      return new Youtube_embed( $this->html );
    }

    // Support for wordpress.tv not complete
    // else if ( strpos( $this->html, "v.wordpress.com" ) !== FALSE ) {
    //   require_once( '/providers/wordpresstv.php' );
    //   return new Wordpresstv_embed( $this->html );
    // }

    return FALSE;
  }

  public function output( $styles_scripts ) {
    if( ! $this->data->is_enabled ) return $this->html;
    
    $op = get_option( 'fwe_settings' );

    $output = "";

    if( ! $styles_scripts ) {
      add_action( 'wp_footer', array( $this, 'fwe_inline_script_footer' ) );

      $output .= '<style>';

      // User-defined width :
      $output .= '.fwe_embed{width:' . $this->cleanWidth( $op['fwe_default_width'] ) . '}';
      $output .= file_get_contents( plugins_url () . '/fast-wordpress-embeds/css/fwe_embed.css' ) . '</style>';
    }

    $output .= '<div class="fwe_embed fwe_embed-' . $this->data->type . ' fwe_embed-' . $this->data->site . '" id="' . $this->data->id . '">';
    $output .= $this->data->output();

    if( $this->data->type == "video" ) {
      $output .= '<div class="fwe-play"></div>';
    }

    $output .= '</div>';

    if( $this->data->default_width() !== $op['fwe_default_width'] ) {
      $output .= '<style>.fwe_embed-' . $this->data->site  . '{width:' . $this->cleanWidth( $this->data->default_width() ) . '}</style>';
    }
    
    return $output;
  }

  private function cleanWidth($w) {

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

  public function fwe_inline_script_footer() {
    wp_enqueue_script( 'fwe-embedscript', plugins_url( '/js/plugin.js', dirname( __FILE__ ) ), array(), FALSE, TRUE );
  }

}
