<?php
/**
 * Product image template - thumbnails
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

$images = $iconic_woo_image_swap_class->get_product_images( $iconic_woo_image_swap_class->settings['effects_thumbnails_image_count'], $product_id );

if ( empty( $images ) ) {
	return;
}

$first_image = array_shift( $images );
$image_count = count( $images );
$classes[]   = 'iconic-wis-product-image--thumbnails'; ?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<?php if ( $link_images ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="iconic-wis-product-image__large_image"><?php echo wp_kses( $first_image, $allowed_html ); ?></a>
	<?php } else { ?>
		<div class="iconic-wis-product-image__large_image"><?php echo wp_kses( $first_image, $allowed_html ); ?></div>
	<?php } ?>

	<?php if ( ! empty( $images ) ) { ?>
		<ul class="iconic-wis-product-image__thumbnails">
			<?php
			$i = 1;
			foreach ( $images as $image ) {
				?>
				<li class="iconic-wis-product-image__thumbnail 
				<?php
				if ( 1 === $i ) {
					echo 'iconic-wis-product-image__thumbnail--active';
				}
				?>
				"><?php echo wp_kses( $image, $allowed_html ); ?></li>
				<?php
				$i ++;
			}
			?>
		</ul>
	<?php } ?>
</div>
