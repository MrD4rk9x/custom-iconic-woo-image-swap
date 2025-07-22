<?php
/**
 * Compatibility: Porto Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_Porto' ) ) {
	return;
}

/**
 * Porto theme compatibility class.
 */
class Iconic_WIS_Compat_Porto {
	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'porto' !== $theme->template ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'init' ), 20 );
	}

	/**
	 * Init hook.
	 */
	public static function init() {
		global $iconic_woo_image_swap_class;

		remove_action( 'woocommerce_before_shop_loop_item_title', 'porto_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_before_shop_loop_item', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 10 );
		add_filter( 'iconic_wis_product_image_classes', array( __CLASS__, 'add_product_image_classes' ), 10, 1 );
		add_filter( 'iconic_wis_link_images', '__return_false' );
	}

	/**
	 * Add theme based classes to product images.
	 *
	 * @param array $classes An array of CSS classes.
	 *
	 * @return array
	 */
	public static function add_product_image_classes( $classes ) {
		$classes[] = 'iconic-wis-product-image--porto';

		return $classes;
	}
}
