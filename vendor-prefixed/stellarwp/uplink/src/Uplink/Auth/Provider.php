<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );

namespace Iconic_WIS_NS\StellarWP\Uplink\Auth;

use Iconic_WIS_NS\StellarWP\Uplink\Auth\Admin\Disconnect_Controller;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Admin\Connect_Controller;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Auth_Pipes\Multisite_Subfolder_Check;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Auth_Pipes\Network_Token_Check;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Auth_Pipes\User_Check;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Token\Contracts\Token_Manager;
use Iconic_WIS_NS\StellarWP\Uplink\Config;
use Iconic_WIS_NS\StellarWP\Uplink\Contracts\Abstract_Provider;
use Iconic_WIS_NS\StellarWP\Uplink\Pipeline\Pipeline;

final class Provider extends Abstract_Provider {

	/**
	 * @inheritDoc
	 */
	public function register() {
		if ( ! $this->container->has( Config::TOKEN_OPTION_NAME ) ) {
			return;
		}

		$this->container->bind(
			Token_Manager::class,
			static function ( $c ) {
				return new Token\Token_Manager( $c->get( Config::TOKEN_OPTION_NAME ) );
			}
		);

		$this->register_nonce();
		$this->register_authorizer();
		$this->register_auth_disconnect();
		$this->register_auth_connect();
	}

	/**
	 * Register nonce container definitions.
	 *
	 * @return void
	 */
	private function register_nonce(): void {
		/**
		 * Filter how long the callback nonce is valid for.
		 *
		 * @note There is also an expiration time in the Uplink Origin plugin.
		 *
		 * Default: 35 minutes, to allow time for them to properly log in.
		 *
		 * @param int $expiration Nonce expiration time in seconds.
		 */
		$expiration = apply_filters( 'stellarwp/uplink/' . Config::get_hook_prefix() . '/auth/nonce_expiration', 2100 );
		$expiration = absint( $expiration );

		$this->container->singleton( Nonce::class, new Nonce( $expiration ) );
	}

	/**
	 * Registers the Authorizer and the steps in order for the pipeline
	 * processing.
	 */
	private function register_authorizer(): void {
		$this->container->singleton(
			Network_Token_Check::class,
			static function ( $c ) {
				return new Network_Token_Check( $c->get( Token_Manager::class ) );
			}
		);

		$pipeline = ( new Pipeline( $this->container ) )->through( [
			User_Check::class,
			Multisite_Subfolder_Check::class,
			Network_Token_Check::class,
		] );

		$this->container->singleton(
			Authorizer::class,
			static function () use ( $pipeline ) {
				return new Authorizer( $pipeline );
			}
		);
	}

	/**
	 * Register auth disconnection definitions and hooks.
	 *
	 * @return void
	 */
	private function register_auth_disconnect(): void {
		$this->container->singleton( Disconnect_Controller::class, Disconnect_Controller::class );

		add_action( 'admin_init', [ $this->container->get( Disconnect_Controller::class ), 'maybe_disconnect' ], 9, 0 );
	}

	/**
	 * Register auth connection definitions and hooks.
	 *
	 * @return void
	 */
	private function register_auth_connect(): void {
		$this->container->singleton( Connect_Controller::class, Connect_Controller::class );

		add_action( 'admin_init', [ $this->container->get( Connect_Controller::class ), 'maybe_store_token_data'], 9, 0 );
	}

}
