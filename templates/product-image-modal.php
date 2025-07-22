<?php
/**
 * Product image template - modal
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

$images = $iconic_woo_image_swap_class->get_product_images( - 1, $product_id );

if ( empty( $images ) ) {
	return;
}

$first_image = array_shift( $images );
$image_count = count( $images );
$classes[]   = 'iconic-wis-product-image--modal'; ?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $link_images ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo wp_kses( $first_image, $allowed_html ); ?></a>
	<?php } else { ?>
		<?php echo wp_kses( $first_image, $allowed_html ); ?>
	<?php } ?>
	<?php if ( $image_count >= 1 ) { ?>
		<button aria-label="<?php echo esc_attr__( 'Open in Fullscreen', 'iconic-wis' ); ?>" class="iconic-wis-product-image__modal-button" data-images="<?php echo esc_attr( $iconic_woo_image_swap_class->convert_to_json_attribute( $images ) ); ?>">
			<i class="iconic-wis-icon-<?php echo esc_attr( $iconic_woo_image_swap_class->settings['effects_modal_icon'] ); ?>"></i>
		</button>
	<?php } ?>
</div>
