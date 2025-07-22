<?php
/**
 * Product image template - horizontal slide
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

$classes[] = 'iconic-wis-product-image--enlarge';
$classes[] = bool_from_yn( $this->settings['effects_enlarge_crop'] ) ? $this->settings['effects_enlarge_crop'] : 'iconic-wis-product-image--enlarge-croppe';
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $link_images ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo wp_kses( $images[0], $allowed_html ); ?></a>
	<?php } else { ?>
		<?php echo wp_kses( $images[0], $allowed_html ); ?>
	<?php } ?>
</div>
