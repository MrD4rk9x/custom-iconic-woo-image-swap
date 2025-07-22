<?php
/**
 * Helpers.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Iconic_WIS_Helpers class
 */
class Iconic_WIS_Helpers {
	/**
	 * Get and modify the allowed tags/attributes used by wp_kses_* functions.
	 *
	 * This prevents the stripping of attributes from images in the loop.
	 *
	 * @return array
	 */
	public static function get_modified_kses_allowed_html() {
		$html = wp_kses_allowed_html( 'post' );

		$html['img']['decoding'] = true;
		$html['img']['srcset']   = true;
		$html['img']['sizes']    = true;

		return $html;
	}
}
