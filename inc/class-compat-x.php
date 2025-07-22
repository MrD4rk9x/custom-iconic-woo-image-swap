<?php
/**
 * Compatibility: X Theme.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Iconic_WIS_Compat_X' ) ) {
	return;
}

/**
 * X theme compatibility class.
 */
class Iconic_WIS_Compat_X {
	/**
	 * Init.
	 */
	public static function run() {
		add_action( 'init', array( __CLASS__, 'hooks' ) );
	}

	/**
	 * Hooks.
	 */
	public static function hooks() {
		if ( ! function_exists( 'x_bootstrap' ) ) {
			return;
		}

		add_action( 'woocommerce_before_shop_loop_item', array( __CLASS__, 'add_rating' ), 4 );
		add_action( 'wp_footer', array( __CLASS__, 'custom_css' ) );
	}

	/**
	 * Add ratings.
	 */
	public static function add_rating() {
		global $product;

		$kses_args = array(
			'div'    => array(
				'class'      => array(),
				'role'       => array(),
				'aria-label' => array(),
			),
			'span'   => array(
				'style' => array(),
			),
			'strong' => array(
				'class' => array(),
			),
		);

		$rating = ( function_exists( 'wc_get_rating_html' ) ) ? wc_get_rating_html( $product->get_average_rating() ) : $product->get_rating_html();

		if ( ! empty( $rating ) ) {
			echo '<div class="star-rating-container aggregate">' . wp_kses( $rating, $kses_args ) . '</div>';
		}
	}

	/**
	 * CSS fix.
	 */
	public static function custom_css() {
		?>
		<style>
			.archive.woocommerce .entry-wrap a.button {
				z-index: 1000;
			}

			.archive.woocommerce .star-rating-container {
				z-index: 1000;
			}
		</style>
		<?php
	}
}
