<?php
/**
 * Blocks.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Iconic_WIS_Blocks class
 */
class Iconic_WIS_Blocks {
	/**
	 * Init.
	 */
	public static function run() {
		add_filter( 'render_block', array( __CLASS__, 'override_block_render' ), 20, 3 );
		add_filter( 'woocommerce_product_get_image', array( __CLASS__, 'filter_woocommerce_product_get_image' ), 10, 6 );
	}

	/**
	 * Override block render.
	 * 
	 * Relates to the Query Loop, Products (Beta) and Collections (Beta) blocks.
	 *
	 * @param string   $block_content The block content.
	 * @param array    $block         The full block, including name and attributes.
	 * @param WP_Block $instance      The block instance.
	 * 
	 * @return string
	 */
	public static function override_block_render( $block_content, $block, $block_instance ) {
		global $iconic_woo_image_swap_class;

		if ( is_admin() ) {
			return $block_content;
		}

		$blocks_to_override = array(
			'woocommerce/product-image',
			'core/post-featured-image'
		);

		if ( empty( $block['blockName'] ) || ! in_array( $block['blockName'], $blocks_to_override, true ) ) {
			return $block_content;
		}

		if ( empty( $block_instance->context['postId'] ) ) {
			return $block_content;
		}

		if ( ! empty( $block_instance->context['postType'] ) && 'product' !== $block_instance->context['postType'] ) {
			return $block_content;
		}

		ob_start();
		$iconic_woo_image_swap_class->template_loop_product_thumbnail();
		$image_swap_html = ob_get_clean();

		return $image_swap_html;
	}

	/**
	 * Replace product images with Image Swap markup in context.
	 * 
	 * Relates to the other WC product blocks, excluding Products (Beta), 
	 * Collections (Beta) blocks as well as the Query Loop block.
	 *
	 * @param  string $image       Image.
	 * @param  object $object      Product instance.
	 * @param  string $size        (default: 'woocommerce_thumbnail').
	 * @param  array  $attr        Image attributes.
	 * @param  bool   $placeholder True to return $placeholder if no image is found, or false to return an empty string.
	 * @param  string $image_clone Image.
	 * 
	 * @return string
	 */
	public static function filter_woocommerce_product_get_image( $image, $object, $size, $attr, $placeholder, $image_clone ) {
		global $post, $iconic_woo_image_swap_class;
		
		if ( is_admin() || is_product() ||! $post ) {
			return $image;
		}
		
		$blocks_to_override = array(
			'woocommerce/product-category',
			'woocommerce/product-tag',
			'woocommerce/products-by-attribute',
			'woocommerce/handpicked-products',
			'woocommerce/product-best-sellers',
			'woocommerce/product-new',
			'woocommerce/product-top-rated',
			'woocommerce/product-on-sale',
		);

		$found_block = false;

		foreach( $blocks_to_override as $block ) {
			if ( has_block( $block, $post ) ) {
				$found_block = true;
			}
		}
		
		if ( ! $found_block ) {
			return $image;
		}
		
		add_filter( 'iconic_wis_link_images', '__return_false' );
		ob_start();
		$iconic_woo_image_swap_class->template_loop_product_thumbnail( $object->get_id(), true );
		$image_swap_html = ob_get_clean();
		
		return $image_swap_html;
	}
}
