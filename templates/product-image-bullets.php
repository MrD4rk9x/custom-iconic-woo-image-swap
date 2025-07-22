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

$images = $iconic_woo_image_swap_class->get_product_images( $iconic_woo_image_swap_class->settings['effects_bullets_image_count'], $product_id );

if ( empty( $images ) ) {
	return;
}

$image_count  = count( $images );
$slick_params = $iconic_woo_image_swap_class->get_slick_params();
$classes[]    = 'iconic-wis-product-image--bullets';
$classes[]    = 'iconic-wis-product-image--bullets-' . $iconic_woo_image_swap_class->settings['effects_bullets_position'];
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" 
	<?php
	if ( $image_count > 1 ) {
		printf( 'data-slick="%s"', esc_attr( $slick_params ) );
	}
	?>
>
	<?php
	$i = 1;
	foreach ( $images as $image ) {
		?>
		<?php $class = $i > 1 ? 'iconic-wis-hidden' : ''; ?>

		<?php if ( $link_images ) { ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo wp_kses( $image, $allowed_html ); ?></a>
		<?php } else { ?>
			<div class="<?php echo esc_attr( $class ); ?>"><?php echo wp_kses( $image, $allowed_html ); ?></div>
		<?php } ?>

		<?php
		$i ++;
	}
	?>
</div>
