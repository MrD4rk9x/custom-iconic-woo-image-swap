<?php
/**
 * Compatibility: Flatsome Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_Flatsome' ) ) {
	return;
}

/**
 * Flatsome theme compatibility class.
 */
class Iconic_WIS_Compat_Flatsome {
	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'flatsome' !== $theme->template ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'init' ), 20 );
	}

	/**
	 * Init hook.
	 */
	public static function init() {
		global $iconic_woo_image_swap_class;

		remove_action( 'woocommerce_before_shop_loop_item', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 5 );
		remove_action( 'flatsome_woocommerce_shop_loop_images', 'flatsome_woocommerce_get_alt_product_thumbnail', 11 );
		remove_action( 'flatsome_woocommerce_shop_loop_images', 'woocommerce_template_loop_product_thumbnail', 10 );

		add_action( 'flatsome_woocommerce_shop_loop_images', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 10 );
		add_filter( 'iconic_wis_link_images', '__return_false' );
		add_filter( 'iconic_wis_product_image_classes', array( __CLASS__, 'add_product_image_classes' ), 10, 1 );
	}

	/**
	 * Add theme based classes to product images.
	 *
	 * @param array $classes Product image classes.
	 *
	 * @return array
	 */
	public static function add_product_image_classes( $classes ) {
		$classes[] = 'iconic-wis-product-image--flatsome';

		return $classes;
	}
}
