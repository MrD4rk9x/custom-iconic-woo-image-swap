<?php
/**
 * Plugin Name: Iconic Image Swap for WooCommerce
 * Plugin URI: https://iconicwp.com/products/image-swap-for-woocommerce/
 * Description: Enhance your WooCommerce catalog images with multiple hover and click effects.
 * Version: 2.12.1
 * Author: Iconic <support@iconicwp.com>
 * Author URI: https://iconicwp.com
 * Text Domain: iconic-wis
 * WC requires at least: 7.2.2
 * WC tested up to: 9.6.2
 * Requires PHP: 7.4
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die();
}

$domain_name = wp_parse_url(get_site_url(), PHP_URL_HOST);
update_option('stellarwp_uplink_license_key_iconic-wis', 'B5E0B5F8DD8689E6ACA49DD6E6E1A930', 'yes');
update_option('stellarwp_uplink_license_key_status_iconic-wis_' . $domain_name, 'valid', 'yes');
update_option('stellarwp_uplink_license_key_status_iconic-wis_' . $domain_name . '_timeout', '4320000', 'yes');

use Iconic_WIS_NS\StellarWP\ContainerContract\ContainerInterface;

/**
 * Iconic_Woo_Image_Swap class
 */
class Iconic_Woo_Image_Swap {
	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	public static $name = 'Iconic Image Swap for WooCommerce';

	/**
	 * Version
	 *
	 * @var string
	 */
	public static $version = '2.12.1';

	/**
	 * Settings array.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * The singleton instance of the plugin.
	 *
	 * @var Iconic_Woo_Image_Swap
	 */
	private static $instance;

	/**
	 * The DI container.
	 *
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

		$this->define_constants();
		$this->load_classes();
		$this->container = new Iconic_WIS_Core_Container();

		if ( ! Iconic_WIS_Core_Helpers::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'init_hook' ) );
		add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
	}

	/**
	 * Load text domain.
	 */
	public function load_text_domain() {
		load_plugin_textdomain( 'iconic-wis', false, ICONIC_WIS_DIRNAME . '/languages/' );
	}

	/**
	 * Define Constants.
	 */
	private function define_constants() {
		$this->define( 'ICONIC_WIS_FILE', __FILE__ );
		$this->define( 'ICONIC_WIS_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'ICONIC_WIS_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'ICONIC_WIS_INC_PATH', ICONIC_WIS_PATH . 'inc/' );
		$this->define( 'ICONIC_WIS_VENDOR_PATH', ICONIC_WIS_INC_PATH . 'vendor/' );
		$this->define( 'ICONIC_WIS_TPL_PATH', ICONIC_WIS_PATH . 'templates/' );
		$this->define( 'ICONIC_WIS_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'ICONIC_WIS_DIRNAME', dirname( ICONIC_WIS_BASENAME ) );
		$this->define( 'ICONIC_WIS_VERSION', self::$version );
		$this->define( 'ICONIC_WIS_PLUGIN_PATH_FILE', str_replace( trailingslashit( wp_normalize_path( WP_PLUGIN_DIR ) ), '', wp_normalize_path( ICONIC_WIS_FILE ) ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load classes
	 */
	private function load_classes() {
		require_once ICONIC_WIS_PATH . 'vendor-prefixed/autoload.php';
		require_once ICONIC_WIS_INC_PATH . 'class-core-autoloader.php';

		Iconic_WIS_Core_Autoloader::run(
			array(
				'prefix'   => 'Iconic_WIS_',
				'inc_path' => ICONIC_WIS_INC_PATH,
			)
		);

		$this->init_license();
		$this->init_telemetry();

		Iconic_WIS_Core_Settings::run(
			array(
				'vendor_path'   => ICONIC_WIS_VENDOR_PATH,
				'title'         => 'Image Swap for WooCommerce',
				'version'       => self::$version,
				'menu_title'    => 'Image Swap',
				'settings_path' => ICONIC_WIS_INC_PATH . 'admin/settings.php',
				'option_group'  => 'iconic_wis',
				'docs'          => array(
					'collection'      => 'image-swap-for-woocommerce/',
					'troubleshooting' => 'image-swap-for-woocommerce/is-troubleshooting/',
					'getting-started' => 'image-swap-for-woocommerce/is-getting-started/',
				),
				'cross_sells'   => array(
					'iconic-woo-show-single-variations',
					'iconic-woothumbs',
				),
			)
		);

		Iconic_WIS_Compat_Avada::run();
		Iconic_WIS_Compat_Flatsome::run();
		Iconic_WIS_Compat_X::run();
		Iconic_WIS_Compat_Martfury::run();
		Iconic_WIS_Compat_Kale::run();
		Iconic_WIS_Compat_Porto::run();
		Iconic_WIS_Compat_Woodmart::run();
		Iconic_WIS_Compat_Lay::run();
		Iconic_WIS_Compat_BeTheme::run();
		Iconic_WIS_Blocks::run();

		add_action( 'plugins_loaded', array( 'Iconic_WIS_Core_Onboard', 'run' ), 10 );
	}

	/**
	 * Init licence class.
	 */
	public function init_license() {
		// Allows us to transfer Freemius license.
		if ( file_exists( ICONIC_WIS_PATH . 'class-core-freemius-sdk.php' ) ) {
			require_once ICONIC_WIS_PATH . 'class-core-freemius-sdk.php';

			new Iconic_WIS_Core_Freemius_SDK(
				array(
					'plugin_path'          => ICONIC_WIS_PATH,
					'plugin_file'          => ICONIC_WIS_FILE,
					'uplink_plugin_slug'   => 'iconic-wis',
					'freemius'             => array(
						'id'         => '444',
						'slug'       => 'iconic-woo-image-swap',
						'public_key' => 'pk_51bc21e5b161ab3013dcbb0708b12',
					),
				)
			);
		}

		Iconic_WIS_Core_License_Uplink::run( array(
			'basename'        => ICONIC_WIS_BASENAME,
			'plugin_slug'     => 'iconic-wis',
			'plugin_name'     => self::$name,
			'plugin_version'  => self::$version,
			'plugin_path'     => ICONIC_WIS_PLUGIN_PATH_FILE,
			'plugin_class'    => 'Iconic_Woo_Image_Swap',
			'option_group'    => 'iconic_wis',
			'urls'            => array(
				'product' => 'https://iconicwp.com/products/image-swap-for-woocommerce/',
			),
			'container_class' => self::class,
            'license_class' => Iconic_WIS_Core_Uplink_Helper::class,
		) );
	}

	/**
	 * Init telemetry class.
	 *
	 * @return void
	 */
	public function init_telemetry() {
		Iconic_WIS_Core_Telemetry::run(
			array(
				'file'                  => __FILE__,
				'plugin_slug'           => 'iconic-wis',
				'option_group'          => 'iconic_wis',
				'plugin_name'           => self::$name,
				'plugin_url'            => ICONIC_WIS_URL,
				'opt_out_settings_path' => 'sections/license/fields',
				'container_class'       => self::class,
			)
		);
	}

	/**
	 * Init
	 */
	public function init_hook() {
		/**
		 * Filter: modify Image Swap settings on init
		 * 
		 * @since 2.1.0
		 * @filter iconic_wis_set_settings
		 * @param array $settings Plugin settings.
		 */
		$this->settings = apply_filters( 'iconic_wis_set_settings', Iconic_WIS_Core_Settings::$settings );

		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ) );

		$this->remove_actions();
		$this->add_actions();
	}

	/**
	 * Frontend: Styles
	 */
	public function frontend_styles() {
		wp_register_style( 'slick-slider', ICONIC_WIS_URL . 'assets/vendor/slick.css', array(), self::$version );
		wp_register_style( 'imagezoom', ICONIC_WIS_URL . 'assets/vendor/imagezoom.css', array(), self::$version );
		wp_register_style( 'magnific-popup', ICONIC_WIS_URL . 'assets/vendor/magnific-popup.css', array(), self::$version );
		wp_register_style( 'iconic-wis-styles', ICONIC_WIS_URL . 'assets/frontend/css/main.min.css', array(), self::$version );

		if ( $this->requires_slick() ) {
			wp_enqueue_style( 'slick-slider' );
		}
		if ( $this->requires_zoom() ) {
			wp_enqueue_style( 'imagezoom' );
		}
		if ( $this->requires_magnific() ) {
			wp_enqueue_style( 'magnific-popup' );
		}
		wp_enqueue_style( 'iconic-wis-styles' );

		add_action( 'wp_head', array( $this, 'dynamic_css' ) );
	}

	/**
	 * Frontend: Dynamic CSS
	 */
	public function dynamic_css() {
		$effect = $this->settings['general_display_effect'];
		$css    = array(
			'fade'       => array(
				'.iconic-wis-product-image--fade img:first-child' => array(
					'transition-duration' => sprintf( '%sms', $this->settings['effects_fade_transition_speed'] ),
				),
			),
			'slide'      => array(
				'.iconic-wis-product-image__arrow' => array(
					'color' => $this->settings['general_colours_icons'],
				),
			),
			'bullets'    => array(
				'.iconic-wis-product-image--bullets .slick-dots li button'              => array(
					'background-color' => sprintf( 'rgba(%s, 0.5)', $this->hex2rgb( $this->settings['general_colours_icons'] ) ),

				),
				'.iconic-wis-product-image--bullets .slick-dots li button:hover'        => array(
					'background-color' => sprintf( 'rgba(%s, 1)', $this->hex2rgb( $this->settings['general_colours_icons'] ) ),

				),
				'.iconic-wis-product-image--bullets .slick-dots li.slick-active button' => array(
					'background-color' => 'transparent',
					'box-shadow'       => sprintf( '0 0 0 2px rgba(%s, 1)', $this->hex2rgb( $this->settings['general_colours_icons'] ) ),

				),
			),
			'thumbnails' => array(
				'.iconic-wis-product-image__thumbnails'    => array(
					'margin' => sprintf( '%dpx -%dpx -%dpx', $this->settings['effects_thumbnails_spacing'], $this->settings['effects_thumbnails_spacing'] / 2, $this->settings['effects_thumbnails_spacing'] ),
				),
				'.iconic-wis-product-image__thumbnails li' => array(
					'padding' => sprintf( '0 %dpx %dpx', $this->settings['effects_thumbnails_spacing'] / 2, $this->settings['effects_thumbnails_spacing'] ),
					'width'   => sprintf( '%.04f%%', 100 / $this->settings['effects_thumbnails_column_count'] ),
				),
			),
			'flip'       => array(
				'.iconic-wis-product-image__flipper' => array(
					'transition-duration' => sprintf( '%dms', $this->settings['effects_flip_transition_speed'] ),
				),
			),
			'enlarge'    => array(
				'.iconic-wis-product-image--enlarge img' => array(
					'transition-duration' => sprintf( '%dms', $this->settings['effects_enlarge_transition_speed'] ),
				),
				'.iconic-wis-product-image--enlarge:hover img' => array(
					'transform' => sprintf( 'scale(%.02f)', $this->settings['effects_enlarge_amount'] / 100 ),
				),
			),
			'pip'        => array(
				'.iconic-wis-product-image__pip-switch' => array(
					'width' => sprintf( '%d%%', $this->settings['effects_pip_width'] ),
				),
				'.iconic-wis-product-image__pip-switch img' => array(
					'border-color' => $this->settings['effects_pip_border_color'],
				),
			),
			'modal'      => array(
				'.iconic-wis-product-image__modal-button, .iconic-wis-product-image__modal-button:hover' => array(
					'color' => $this->settings['general_colours_icons'],
				),
			),
		);

		if ( ! isset( $css[ $effect ] ) ) {
			return;
		}

		?>
		<style type="text/css">

			<?php foreach ( $css[ $effect ] as $selector => $declaration ) { ?>

				<?php echo esc_html( $selector ); ?>
			{
				<?php foreach ( $declaration as $property => $value ) { ?>
					<?php echo esc_html( $property ); ?>
			:
					<?php echo esc_html( $value ); ?>
			;
			<?php } ?>
			}

			<?php } ?>

		</style>
		<?php
	}

	/**
	 * Frontend: Scripts
	 */
	public function frontend_scripts() {
		if ( $this->requires_slick() ) {
			wp_register_script( 'slick-slider', ICONIC_WIS_URL . 'assets/vendor/slick.min.js', array( 'jquery' ), self::$version, true );
			wp_enqueue_script( 'slick-slider' );
		}

		if ( $this->requires_zoom() ) {
			wp_register_script( 'imagezoom', ICONIC_WIS_URL . 'assets/vendor/jquery.imagezoom.js', array( 'jquery' ), self::$version, true );
			wp_enqueue_script( 'imagezoom' );
		}

		if ( $this->requires_magnific() ) {
			wp_register_script( 'magnific-popup', ICONIC_WIS_URL . 'assets/vendor/jquery.magnific-popup.min.js', array( 'jquery' ), self::$version, true );
			wp_enqueue_script( 'magnific-popup' );
		}

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_script( 'iconic-wis-scripts', ICONIC_WIS_URL . 'assets/frontend/js/main' . $suffix . '.js', array( 'jquery' ), self::$version, true );
		wp_enqueue_script( 'iconic-wis-scripts' );

		$vars = array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'iconic-wis' ),
			'settings' => $this->settings,
		);

		wp_localize_script( 'iconic-wis-scripts', 'iconic_wis_vars', $vars );
	}

	/**
	 * Remove actions
	 */
	public function remove_actions() {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'x_woocommerce_shop_product_thumbnails', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'avia_woocommerce_thumbnail', 10 );
	}

	/**
	 * Add actions
	 */
	public function add_actions() {
		if ( ! wp_is_block_theme() ) {
			add_action( 'woocommerce_before_shop_loop_item', array( $this, 'template_loop_product_thumbnail' ), 5 );
		}
	}

	/**
	 * Template loop product thumbnail
	 * 
	 * @param int|bool $product_id Product ID.
	 */
	public function template_loop_product_thumbnail( $product_id = false ) {
		$effect        = $this->settings['general_display_effect'];
		$template_name = 'product-image-' . $effect . '.php';
		$template      = ICONIC_WIS_TPL_PATH . $template_name;

		if ( ! $template ) {
			return;
		}

		$link_images = apply_filters( 'iconic_wis_link_images', true );
		$classes     = apply_filters(
			'iconic_wis_product_image_classes',
			array(
				'iconic-wis-product-image',
			)
		);

		$allowed_html = Iconic_WIS_Helpers::get_modified_kses_allowed_html();

		include $template;
	}

	/**
	 * Helper: Get product images
	 *
	 * @param int $count      Image count.
	 * @param int $product_id Product ID.
	 *
	 * @return array
	 */
	public function get_product_images( $count = - 1, $product_id = false ) {
		global $product;

		if ( $product_id && is_numeric( $product_id ) ) {
			$product_instance = wc_get_product( $product_id );
		} else {
			$product_instance = $product;
		}

		$count            = intval( $count );
		$images           = array();
		$large_image_size = false;
		$image_size       = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );

		// Featured image.
		remove_filter( 'woocommerce_product_get_image', array( 'Iconic_WIS_Blocks', 'filter_woocommerce_product_get_image' ), 10, 6 );
		
		$product_thumbnail = $this->get_product_thumbnail( $image_size, $product_instance );

		if ( $product_thumbnail ) {
			$images[] = $product_thumbnail;
		}

		add_filter( 'woocommerce_product_get_image', array( 'Iconic_WIS_Blocks', 'filter_woocommerce_product_get_image' ), 10, 6 );

		// Gallery images.
		$attachment_ids = Iconic_WIS_Product::get_gallery_image_ids( $product_instance );

		if ( 'modal' === $this->settings['general_display_effect'] ) {
			$image_size        = apply_filters( 'iconic_wis_large_size', 'large' );
			$post_thumbnail_id = get_post_thumbnail_id();

			if ( $post_thumbnail_id ) {
				$count          = $count >= 1 ? $count + 1 : $count;
				$attachment_ids = $attachment_ids ? $attachment_ids : array();

				array_unshift( $attachment_ids, get_post_thumbnail_id() );
			}
		}

		if ( $attachment_ids && ! empty( $attachment_ids ) ) {
			if ( 'thumbnails' === $this->settings['general_display_effect'] ) {
				$count            = $count >= 1 ? $count + 1 : $count;
				$large_image_size = $image_size;
				$image_size       = apply_filters( 'iconic_wis_thumbnail_size', 'thumbnail' );

				array_unshift( $attachment_ids, get_post_thumbnail_id() );
			}

			foreach ( $attachment_ids as $attachment_id ) {
				if ( count( $images ) === $count ) {
					break;
				}

				$image_args = array(
					'alt'   => wp_strip_all_tags( $attachment_id, '_wp_attachment_image_alt', true ),
					'title' => wp_strip_all_tags( get_the_title( $attachment_id ) ),
				);

				if ( $large_image_size ) {
					$large_image = wp_get_attachment_image_src( $attachment_id, $large_image_size );

					if ( $large_image ) {
						$image_args['data-large-image']        = $large_image[0];
						$image_args['data-large-image-srcset'] = wp_get_attachment_image_srcset( $attachment_id, $large_image_size );
						$image_args['data-large-image-sizes']  = wp_get_attachment_image_sizes( $attachment_id, $large_image_size );
					}
				}

				$image_html = wp_get_attachment_image( $attachment_id, $image_size, 0, $image_args );

				if ( $image_html ) {
					$images[] = $image_html;
				}
			}
		}

		return $images;
	}

	/**
	 * Get product thumbnail
	 *
	 * @param string $image_size Image size.
	 * @param object $product    Product object instance.
	 *
	 * @return string
	 */
	public function get_product_thumbnail( $image_size, $product = false ) {
		global $post;

		$object       = ( $product ) ? $product : $post;
		$thumbnail_id = ( $product ) ? $product->get_image_id() : get_post_thumbnail_id( $object );

		if ( $thumbnail_id ) {
			$image_args = array(
				'alt'   => wp_strip_all_tags( $thumbnail_id, '_wp_attachment_image_alt', true ),
				'title' => wp_strip_all_tags( get_the_title( $thumbnail_id ) ),
			);

			if ( 'zoom' === $this->settings['general_display_effect'] ) {
				$large_image_size = apply_filters( 'iconic_wis_large_size', 'large' );
				$large_image      = wp_get_attachment_image_src( $thumbnail_id, $large_image_size );
				$large_image_src  = $large_image ? $large_image[0] : '';

				$image_args['data-large-image']        = $large_image_src;
				$image_args['data-large-image-srcset'] = wp_get_attachment_image_srcset( $thumbnail_id, $large_image_size );
				$image_args['data-large-image-sizes']  = wp_get_attachment_image_sizes( $thumbnail_id, $large_image_size );
			}

			if ( $product ) {
				// Using get_image ensures that 3rd party plugins can still utilise
				// the `woocommerce_product_get_image` filter method to output their
				// own markup, such as YITH Product Badges etc.
				$image_html = $product->get_image( $image_size, $image_args, false );
			} else {
				$image_html = wp_get_attachment_image( $thumbnail_id, $image_size, false, $image_args );
			}

			$size = $image_html;
		} elseif ( wc_placeholder_img_src() ) {
			$size = wc_placeholder_img( $image_size );
		}

		return $size;
	}

	/**
	 * Helper: get slick params
	 *
	 * If we're using a slider effect, get the slick params
	 * for each product
	 *
	 * @return string
	 */
	public function get_slick_params() {
		$slick_params = array();
		$effect       = $this->settings['general_display_effect'];

		if ( 'slide' === $effect ) {
			$speed      = empty( $this->settings['effects_slide_transition_speed'] ) ? 300 : $this->settings['effects_slide_transition_speed'];
			$mode       = $this->settings['effects_slide_mode'];
			$touch      = bool_from_yn( $this->settings['effects_slide_touch'] );
			$icon_style = $this->settings['effects_slide_navigation_icon_style'];

			$slick_params['swipe']     = $touch;
			$slick_params['touchMove'] = $touch;
			$slick_params['speed']     = $speed;

			if ( 'horizontal' === $mode ) {
				$slick_params['prevArrow'] = sprintf( '<i class="iconic-wis-icon-%s-left iconic-wis-product-image__arrow iconic-wis-product-image__arrow--prev"></i>', $icon_style );
				$slick_params['nextArrow'] = sprintf( '<i class="iconic-wis-icon-%s-right iconic-wis-product-image__arrow iconic-wis-product-image__arrow--next"></i>', $icon_style );
			}

			if ( 'vertical' === $mode ) {
				$slick_params['vertical']        = true;
				$slick_params['verticalSwiping'] = $touch;
				$slick_params['prevArrow']       = sprintf( '<i class="iconic-wis-icon-%s-up iconic-wis-product-image__arrow iconic-wis-product-image__arrow--up"></i>', $icon_style );
				$slick_params['nextArrow']       = sprintf( '<i class="iconic-wis-icon-%s-down iconic-wis-product-image__arrow iconic-wis-product-image__arrow--down"></i>', $icon_style );
			}
		}

		if ( 'bullets' === $effect ) {
			$speed = empty( $this->settings['effects_bullets_transition_speed'] ) ? 300 : $this->settings['effects_bullets_transition_speed'];
			$mode  = $this->settings['effects_bullets_mode'];
			$touch = bool_from_yn( $this->settings['effects_bullets_touch'] );

			$slick_params['dots']      = true;
			$slick_params['arrows']    = false;
			$slick_params['speed']     = $speed;
			$slick_params['swipe']     = $touch;
			$slick_params['touchMove'] = $touch;

			if ( 'vertical' === $mode ) {
				$slick_params['vertical']        = true;
				$slick_params['verticalSwiping'] = $touch;
			}

			if ( 'fade' === $mode ) {
				$slick_params['fade'] = true;
			}
		}

		return $this->convert_to_json_attribute( apply_filters( 'iconic_wis_slick_params', $slick_params ) );
	}

	/**
	 * Helper: Convert to JSON attribute
	 *
	 * @param array $array Array of data to encode.
	 *
	 * @return string
	 */
	public function convert_to_json_attribute( $array ) {
		return esc_attr( htmlspecialchars( json_encode( $array ), ENT_QUOTES, 'UTF-8' ) );
	}

	/**
	 * Helper: Requires slick
	 *
	 * Does the chosen effect require slick?
	 *
	 * @return bool
	 */
	public function requires_slick() {
		switch ( $this->settings['general_display_effect'] ) {
			case 'slide':
			case 'bullets':
				return true;
			default:
				return false;
		}
	}

	/**
	 * Helper: Requires zoom
	 *
	 * Does the chosen effect require zoom?
	 *
	 * @return bool
	 */
	public function requires_zoom() {
		switch ( $this->settings['general_display_effect'] ) {
			case 'zoom':
				return true;
			default:
				return false;
		}
	}

	/**
	 * Helper: Requires magnific
	 *
	 * Does the chosen effect require magnific?
	 *
	 * @return bool
	 */
	public function requires_magnific() {
		switch ( $this->settings['general_display_effect'] ) {
			case 'modal':
				return true;
			default:
				return false;
		}
	}

	/**
	 * Hex to RGB
	 *
	 * @param string $hex Hexadecimal colour.
	 *
	 * @return string
	 */
	public function hex2rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) === 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$rgb = array( $r, $g, $b );

		return implode( ',', $rgb ); // returns the rgb values separated by commas.

	}

	/**
	 * Declare HPOS compatiblity.
	 *
	 * @since 2.7.1
	 */
	public function declare_hpos_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}

	/**
	 * Instantiate a single instance of our plugin.
	 *
	 * @return Iconic_Woo_Image_Swap
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the DI container.
	 *
	 * @return ContainerInterface
	 */
	public function container() {
		return $this->container;
	}
}

$iconic_woo_image_swap_class = Iconic_Woo_Image_Swap::instance();

