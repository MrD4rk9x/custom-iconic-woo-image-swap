<?php
/**
 * Handles all methods related to the Last Send option in the database.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 *
 * @license GPL-2.0-or-later
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Iconic_WIS_NS\StellarWP\Telemetry\Last_Send;

use Iconic_WIS_NS\StellarWP\Telemetry\Contracts\Abstract_Subscriber;

/**
 * The subscriber for the Last_Send option.
 *
 * @since 1.0.0
 *
 * @package StellarWP\Telemetry
 */
class Last_Send_Subscriber extends Abstract_Subscriber {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', [ $this, 'initialize_last_send_option' ] );
	}

	/**
	 * Initializes the option in the options table to track the last time data was sent to the telemetry server.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function initialize_last_send_option() {
		$this->container->get( Last_Send::class )->initialize_option();
	}
}
