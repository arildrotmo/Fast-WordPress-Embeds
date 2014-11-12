<?php

class Youtube_embed {

	public $site = "youtube";
	public $id;
	public $type = "video";

	private $thumbs = array();
	public $is_enabled = TRUE;

	function __construct( $html ) {
		$this->parse_embed( $html );
		$this->is_enabled = $this->check_enabled();
	}

	/*
	 *	Find the video ID in embed code
	 */
	private function parse_embed( $html ) {
		$this->id = explode( '?', explode( 'embed/', $html )[1] )[0];
	}

	/*
	 *	Is YouTube-support enabled?
	 */
	private function check_enabled() {
		$options = get_option( 'fwe_settings' );
		return isset( $options['fwe_enable_youtube'] );
	}

	/*
	 *	Is default width set?
	 */
	public function default_width() {
		$options = get_option( 'fwe_settings' );
		return $options['fwe_youtube_width'];
	}

	/*
	 *	Output embed replacement
	 */
	public function output() {
		$this->get_thumbnails();

		$img = '<img sizes="(max-width: 640px) 100vw, 33vw" srcset="';
		$img .= $this->thumbs['huge'] . ' 640w, ';
		$img .= $this->thumbs['large'] . ' 480w, ';
		$img .= $this->thumbs['small'] . ' 320w" ';
		$img .= 'src="' . $this->thumbs['small'] . '" alt="YouTube thumbnail"';

		$html = '<noscript><a href="https://www.youtube.com/watch?v=' . $this->id . '" target="_blank">' . $img . '></a></noscript>';

		$html .= $img . ' style="display: none">';

		return $html;
	}

	/*
	 *	Get thumbnail-urls
	 */
	private function get_thumbnails() {
		$this->thumbs['huge'] = 'http://i.ytimg.com/vi/' . $this->id . '/sddefault.jpg';
		$this->thumbs['large'] = 'http://i.ytimg.com/vi/' . $this->id . '/hqdefault.jpg';
		$this->thumbs['small'] = 'http://i.ytimg.com/vi/' . $this->id . '/mqdefault.jpg';
	}

}






