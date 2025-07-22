<?php
/**
 * Product image template - zoom
 *
 * @var int   $product_id
 * @var bool  $link_images
 * @var array $classes
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

global $iconic_woo_image_swap_class;

$images = $iconic_woo_image_swap_class->get_product_images( 1, $product_id );

if ( empty( $images ) ) {
	return;
}

$classes[] = 'iconic-wis-product-image--zoom'; ?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php echo wp_kses( $images[0], $allowed_html ); ?>
</div>
