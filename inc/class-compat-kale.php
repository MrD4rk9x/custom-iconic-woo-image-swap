<?php
/**
 * Compatibility: Kale Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_Kale' ) ) {
	return;
}

/**
 * Kale theme compatibility class.
 */
class Iconic_WIS_Compat_Kale {

	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'kale' !== $theme->template ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'init_hook' ), 100 );
	}

	/**
	 * Init Hook
	 */
	public static function init_hook() {
		remove_action( 'woocommerce_before_shop_loop_item', 'kale_template_loop_product_thumb', 10 );
		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'before_shop_loop' ), 0 );
		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'after_shop_loop' ), 10 );
	}

	/**
	 * Before shop loop.
	 */
	public static function before_shop_loop() {
		echo '<div class="product-thumb">';
		echo '<a href="' . esc_url( get_the_permalink() ) . ' class="woocommerce-LoopProduct-link">';

	}

	/**
	 * After shop loop.
	 */
	public static function after_shop_loop() {
		echo '</a>';
		echo '<span class="product-thumb-bag-icon"><i class="fa fa-shopping-bag"></i></span>';
	}
}
