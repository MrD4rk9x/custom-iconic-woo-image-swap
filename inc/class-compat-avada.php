<?php
/**
 * Compatibility: Avada Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_Avada' ) ) {
	return;
}

/**
 * Avada theme compatibility class.
 */
class Iconic_WIS_Compat_Avada {
	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'Avada' !== $theme->template ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'move_images' ), 20 );
	}

	/**
	 * Move images.
	 */
	public static function move_images() {
		global $iconic_woo_image_swap_class, $avada_woocommerce;

		remove_action( 'woocommerce_before_shop_loop_item', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 5 );
		remove_action( 'woocommerce_before_shop_loop_item_title', array( $avada_woocommerce, 'thumbnail' ), 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', array( $avada_woocommerce, 'before_shop_loop_item_title_open' ), 5 );
		remove_action( 'woocommerce_before_shop_loop_item_title', array( $avada_woocommerce, 'before_shop_loop_item_title_close' ), 20 );

		add_action( 'woocommerce_before_shop_loop_item_title', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 10 );
	}
}
