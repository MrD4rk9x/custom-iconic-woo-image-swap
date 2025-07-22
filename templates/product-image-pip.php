<?php
/**
 * Product image template - picture in picture
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

$images = $iconic_woo_image_swap_class->get_product_images( 2, $product_id );

if ( empty( $images ) ) {
	return;
}

$first_image = array_shift( $images );
$image_count = count( $images );
$classes[]   = 'iconic-wis-product-image--pip';
$classes[]   = 'iconic-wis-product-image--pip-' . $iconic_woo_image_swap_class->settings['effects_pip_position']; ?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $link_images ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="iconic-wis-product-image__large_image"><?php echo wp_kses( $first_image, $allowed_html ); ?></a>
	<?php } else { ?>
		<div class="iconic-wis-product-image__large_image"><?php echo wp_kses( $first_image, $allowed_html ); ?></div>
	<?php } ?>

	<?php if ( ! empty( $images ) ) { ?>
		<?php if ( $link_images ) { ?>
			<a href="javascript: void(0);" class="iconic-wis-product-image__pip-switch"><?php echo wp_kses( $images[0], $allowed_html ); ?></a>
		<?php } else { ?>
			<div class="iconic-wis-product-image__pip-switch"><?php echo wp_kses( $images[0], $allowed_html ); ?></div>
		<?php } ?>
	<?php } ?>
</div>
