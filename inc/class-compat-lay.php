<?php
/**
 * Compatibility: Lay Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_Lay' ) ) {
	return;
}

/**
 * Lay theme compatibility class.
 */
class Iconic_WIS_Compat_Lay {
	/**
	 * Init.
	 */
	public static function run() {
		$theme = wp_get_theme();

		if ( 'lay' !== $theme->template ) {
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
		add_action( 'woocommerce_before_shop_loop_item_title', array( $iconic_woo_image_swap_class, 'template_loop_product_thumbnail' ), 10 );
		add_filter( 'iconic_wis_link_images', '__return_false' );
		add_filter( 'iconic_wis_product_image_classes', array( __CLASS__, 'add_product_image_classes' ), 10, 1 );
		add_action( 'wp_footer', array( __CLASS__, 'custom_css' ) );
	}

	/**
	 * Add theme based classes to product images.
	 *
	 * @param array $classes Product image classes.
	 *
	 * @return array
	 */
	public static function add_product_image_classes( $classes ) {
		$classes[] = 'iconic-wis-product-image--lay ph lay-woocommerce-image';

		return $classes;
	}

	/**
	 * CSS fix.
	 */
	public static function custom_css() {
		?>
		<style>
			.lay-products .lay-product {
				position: relative;
			}
			.iconic-wis-product-image {
				margin-bottom: 0 !important;
			}
			/* Fade */
			.lay_woocommerce_product_thumbnail_title_wrap,
			.iconic-wis-product-image--fade + .lay_woocommerce_product_thumbnail_title_wrap {
				position: absolute;
				width: 100%;
				bottom: 0;
				left: 0;
				z-index: 3;
			}
		</style>
		<?php
	}
}
