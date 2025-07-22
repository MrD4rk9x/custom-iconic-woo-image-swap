<?php
/**
 * Product image template - fade
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

$image_count = count( $images );
$classes[]   = $image_count > 1 ? 'iconic-wis-product-image--fade' : ''; ?>

<?php if ( $link_images ) { ?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php foreach ( $images as $image ) { ?>
			<?php echo wp_kses( $image, $allowed_html ); ?>
		<?php } ?>
	</a>
<?php } else { ?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php foreach ( $images as $image ) { ?>
			<?php echo wp_kses( $image, $allowed_html ); ?>
		<?php } ?>
	</div>
<?php } ?>
