<?php
/**
 * Compatibility: Martfury Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_Martfury' ) ) {
	return;
}

/**
 * Martfury theme compatibility class.
 */
class Iconic_WIS_Compat_Martfury {

	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'martfury' !== $theme->template ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'init_hook' ), 100 );
	}

	/**
	 * Init Hook
	 */
	public static function init_hook() {
		global $martfury_woocommerce, $iconic_woo_image_swap_class;

		remove_action( 'woocommerce_before_shop_loop_item', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 5 );
		add_action( 'martfury_after_product_loop_thumbnail', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 5 );

		remove_action( 'woocommerce_before_shop_loop_item_title', array( $martfury_woocommerce, 'product_content_thumbnail' ) );
		remove_action( 'martfury_woo_before_shop_loop_item_title', array( $martfury_woocommerce, 'product_content_thumbnail' ) );
		add_action( 'woocommerce_before_shop_loop_item_title', array( __class__, 'product_content_thumbnail' ) );
		add_action( 'martfury_woo_before_shop_loop_item_title', array( __class__, 'product_content_thumbnail' ) );
	}

	/**
	 * Overwrite of Martfury product_content_thumbnails function.
	 *
	 * Removes surrounding <a/> tag which breaks Woo Thumbs.
	 *
	 * @return void
	 */
	public static function product_content_thumbnail() {
		printf( '<div class="mf-product-thumbnail">' );

		do_action( 'martfury_after_product_loop_thumbnail' );

		do_action( 'martfury_after_product_loop_thumbnail_link' );

		echo '</div>';
	}
}
