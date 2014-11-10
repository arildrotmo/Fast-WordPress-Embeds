<?php
  // http://wordpress.tv/2014/10/26/matt-mullenweg-the-state-of-the-word-2014/

  // <embed src="//v.wordpress.com/GmPDhkyi" type="application/x-shockwave-flash" width="500" height="281" allowscriptaccess="always" allowfullscreen="true" wmode="transparent"></embed>

// Get thumbnails?
// https://developer.wordpress.com/docs/oembed-provider-api/

class Wordpresstv_embed {

	public $site = "wordpresstv";
	public $id;
	public $type = "video";

	private $thumbs = array();
	public $is_enabled = TRUE;

	function __construct( $html ) {
		$this->parse_embed( $html );
		$this->is_enabled = $this->check_enabled();
	}

	private function parse_embed( $html ) {
		$this->id = explode( '"', explode( 'v.wordpress.com/', $html )[1] )[0];
	}

	private function check_enabled() {
		$options = get_option( 'fwe_settings' );
		return isset( $options['fwe_enable_wordpresstv'] );
	}

	public function output() {
		$this->get_thumbnails();

		$html = '<noscript><a href="//v.wordpress.com/' . $this->id . '" target="_blank"></noscript>';
		$html .= '<img ';
		$html .= 'sizes="(max-width: 640px) 100vw, 33vw" ';
		$html .= 'srcset="';
		$html .= $this->thumbs['huge'] . ' 420w" ';
		$html .= 'src="' . $this->thumbs['huge'] . '" alt="wordpress.tv thumbnail">';
		$html .= '<noscript></a></noscript>';

		return $html;
	}

	private function get_thumbnails() {
		$this->thumbs['huge'] = site_url() . '/wp-content/plugins/fast-wordpress-embeds/assets/wptv.png';
	}

}





