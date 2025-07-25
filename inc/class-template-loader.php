<?php
/**
 * Template Loader.
 *
 * @package iconic-image-swap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Iconic_Template_Loader' ) ) {

	if ( ! class_exists( 'Gamajo_Template_Loader' ) ) {
		require plugin_dir_path( __FILE__ ) . 'vendor/class-gamajo-template-loader.php';
	}

	/**
	 * Template loader for Iconic plugins
	 *
	 * Only need to specify class properties here.
	 */
	class Iconic_Template_Loader extends Gamajo_Template_Loader {

		/**
		 * Prefix for filter names.
		 *
		 * @since 1.0.0
		 * @var string $filter_prefix
		 */
		protected $filter_prefix;

		/**
		 * Directory name where custom templates for this plugin should be found in the theme.
		 *
		 * @since 1.0.0
		 * @var string $theme_template_directory
		 */
		protected $theme_template_directory;

		/**
		 * Reference to the root directory path of this plugin.
		 *
		 * @since 1.0.0
		 * @var string $plugin_directory
		 */
		protected $plugin_directory;

		/**
		 * Construct
		 *
		 * @param string $filter_prefix            Filter prefix.
		 * @param string $theme_template_directory Theme template directory path.
		 * @param string $plugin_directory         Plugin directory path.
		 */
		public function __construct( $filter_prefix, $theme_template_directory, $plugin_directory ) {

			$this->filter_prefix            = $filter_prefix;
			$this->theme_template_directory = $theme_template_directory;
			$this->plugin_directory         = $plugin_directory;

		}

	}

}
