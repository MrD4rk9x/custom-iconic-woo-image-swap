<?php
/**
 * Product.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

/**
 * Iconic_WIS_Product class
 */
class Iconic_WIS_Product {
	/**
	 * Get gallery image IDs
	 *
	 * @param WC_Product $product WC_Product object instance.
	 *
	 * @return array
	 */
	public static function get_gallery_image_ids( $product ) {
		if ( ! $product ) {
			return array();
		}

		// Ignore product gallery images for variations if WooThumbs is not active.
		if (
			/**
			 * Filter: use/do not use variation gallery images.
			 *
			 * @since 2.7.2
			 * @param bool $use True to use, false to not use.
			 */
			! apply_filters( 'iconic_wis_use_variation_gallery_images', false ) &&
			! class_exists( 'Iconic_WooThumbs' ) &&
			$product->is_type( 'variation' )
		) {
			return array();
		}

		/**
		 * Filter: modify the gallery image IDs for a given product.
		 *
		 * @since 4.7.0
		 * @param array      $attachment_ids Product gallery image IDs.
		 * @param WC_Product $product        WooCommere product object.
		 */
		return apply_filters( 'iconic_wis_gallery_image_ids', $product->get_gallery_image_ids(), $product );
	}
}
