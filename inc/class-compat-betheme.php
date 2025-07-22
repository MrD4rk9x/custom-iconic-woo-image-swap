<?php
/**
 * Compatibility: BeTheme Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_BeTheme' ) ) {
	return;
}

/**
 * BeTheme theme compatibility class.
 */
class Iconic_WIS_Compat_BeTheme {
	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'betheme' !== $theme->template ) {
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
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 11 );

		add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 100 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'add_template_loop' ), 10 );
		add_filter( 'iconic_wis_link_images', '__return_false' );
		add_filter( 'iconic_wis_product_image_classes', array( __CLASS__, 'add_product_image_classes' ), 10, 1 );
	}

	/**
	 * Add the template loop once per-product for this theme.
	 *
	 * The reason we are using the static variable is because
	 * the theme's content-product.php template adds the
	 * woocommerce_before_shop_loop_item_title hook twice in
	 * two separate places.
	 *
	 * @return void
	 */
	public static function add_template_loop() {
		global $iconic_woo_image_swap_class, $product;

		static $added = array();

		if ( in_array( $product->get_id(), $added, true ) ) {
			// Prevent interefernce with a additional instances of this product loop.
			unset( $added[ array_search( $product->get_id(), $added, true ) ] );
			return;
		}

		$added[] = $product->get_id();

		$iconic_woo_image_swap_class->template_loop_product_thumbnail();
	}

	/**
	 * Add theme based classes to product images.
	 *
	 * @param array $classes Product image classes.
	 *
	 * @return array
	 */
	public static function add_product_image_classes( $classes ) {
		$classes[] = 'iconic-wis-product-image--betheme';

		return $classes;
	}
}
