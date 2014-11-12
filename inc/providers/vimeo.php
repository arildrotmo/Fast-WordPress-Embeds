<?php

class Vimeo_embed {

	public $site = "vimeo";
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
		$this->id = explode( '"', explode( 'video/', $html )[1] )[0];
	}

	/*
	 *	Is Vimeo-support enabled?
	 */
	private function check_enabled() {
		$options = get_option( 'fwe_settings' );
		return isset( $options['fwe_enable_vimeo'] );
	}

	/*
	 *	Is default width set?
	 */
	public function default_width() {
		$options = get_option( 'fwe_settings' );
		return $options['fwe_vimeo_width'];
	}

	/*
	 *	Output embed replacement
	 */
	public function output() {
		$this->get_thumbnails();

		$img = '<img sizes="(max-width: 640px) 100vw, 33vw" srcset="';
		$img .= $this->thumbs['huge'] . ' 1280w, ';
		$img .= $this->thumbs['large'] . ' 640w, ';
		$img .= $this->thumbs['small'] . ' 320w" ';
		$img .= 'src="'. $this->thumbs['small'] .'" alt="Vimeo thumbnail"';

		$html = '<noscript><a href="https://vimeo.com/' . $this->id . '" target="_blank">' . $img . '></a></noscript>';
		
		$html .= $img . ' style="display: none">';

		return $html;
	}

	/*
	 *	Get thumbnail-urls
	 */
	private function get_thumbnails() {
		$url = 'http://vimeo.com/api/v2/video/'.$this->id.'.php';
		$contents = @file_get_contents( $url );
		$array = @unserialize( trim( $contents ) );
		$this->thumbs['large'] = $array[0]['thumbnail_large'];
		$this->thumbs['huge'] = substr( $this->thumbs['large'], 0, strpos( $this->thumbs['large'], "_" ) ) . "_1280.jpg";
		$this->thumbs['small'] = substr( $this->thumbs['large'], 0, strpos( $this->thumbs['large'], "_" ) ) . "_320.jpg";
	}

}






