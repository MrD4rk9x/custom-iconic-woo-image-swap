<?php
/**
 * Handles all hooks/filters related to the admin screens.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 *
 * @license GPL-2.0-or-later
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Iconic_WIS_NS\StellarWP\Telemetry\Admin;

use Iconic_WIS_NS\StellarWP\Telemetry\Config;
use Iconic_WIS_NS\StellarWP\Telemetry\Contracts\Abstract_Subscriber;
use Iconic_WIS_NS\StellarWP\Telemetry\Opt_In\Opt_In_Template;

/**
 * Handles all hooks/filters related to the admin screens.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Admin_Subscriber extends Abstract_Subscriber {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', [ $this, 'maybe_enqueue_admin_assets' ] );
	}

	/**
	 * Registers required hooks to set up the admin assets.
	 *
	 * @since 1.0.0
	 * @since 2.0.0 - Adjust to output assets if any stellar slug should render its modal.
	 *
	 * @return void
	 */
	public function maybe_enqueue_admin_assets() {
		global $pagenow;

		$should_render = false;

		foreach ( Config::get_all_stellar_slugs() as $stellar_slug => $wp_slug ) {
			$should_render = $this->container->get( Opt_In_Template::class )->should_render( $stellar_slug );

			if ( $should_render ) {
				break;
			}
		}

		if ( 'plugins.php' === $pagenow || $should_render ) {
			$this->container->get( Resources::class )->enqueue_admin_assets();
		}
	}
}
