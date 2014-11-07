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

  public function output() {
    if( ! $this->data->is_enabled ) return $this->html;

    $output = '<div class="fwe_embed fwe_embed-' . $this->data->type . ' fwe_embed-' . $this->data->site . '" id="' . $this->data->id . '">';

    $output .= $this->data->output();

    if( $this->data->type == "video" ) $output .= '<div class="fwe-play"></div>';
    $output .= '</div>';

    return $output;
  }

  public function fwe_inline_script_footer() {
    wp_enqueue_script( 'fwe-embedscript', plugins_url( '/js/plugin.js', dirname( __FILE__ ) ), array(), FALSE, TRUE );
  }

}
