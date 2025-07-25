<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );

namespace Iconic_WIS_NS\StellarWP\Uplink\Auth\Admin;

use Iconic_WIS_NS\StellarWP\Uplink\Auth\Nonce;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Token\Connector;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Token\Exceptions\InvalidTokenException;
use Iconic_WIS_NS\StellarWP\Uplink\Notice\Notice_Handler;
use Iconic_WIS_NS\StellarWP\Uplink\Notice\Notice;
use Iconic_WIS_NS\StellarWP\Uplink\Resources\Collection;

/**
 * Handles storing token data after successfully redirecting
 * back from an Origin site that has authorized their license.
 */
final class Connect_Controller {

	public const TOKEN   = 'uplink_token';
	public const LICENSE = 'uplink_license';
	public const SLUG    = 'uplink_slug';
	public const NONCE   = '_uplink_nonce';

	/**
	 * @var Connector
	 */
	private $connector;

	/**
	 * @var Notice_Handler
	 */
	private $notice;

	/**
	 * @var Collection
	 */
	private $collection;


	public function __construct( Connector $connector, Notice_Handler $notice, Collection $collection ) {
		$this->connector  = $connector;
		$this->notice     = $notice;
		$this->collection = $collection;
	}

	/**
	 * Store the token data passed back from the Origin site.
	 *
	 * @action admin_init
	 */
	public function maybe_store_token_data(): void {
		if ( ! is_admin() || wp_doing_ajax() ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$args = array_intersect_key( $_GET, [
			self::TOKEN   => true,
			self::NONCE   => true,
			self::LICENSE => true,
			self::SLUG    => true,
		] );

		if ( ! $args ) {
			return;
		}

		if ( ! Nonce::verify( $args[ self::NONCE ] ?? '' ) ) {
			$this->notice->add( new Notice( Notice::ERROR,
				__( 'Unable to save token data: nonce verification failed.', 'iconic-wis' ),
				true
			) );

			return;
		}

		try {
			if ( ! $this->connector->connect( $args[ self::TOKEN ] ?? '' ) ) {
				$this->notice->add( new Notice( Notice::ERROR,
					__( 'Error storing token.', 'iconic-wis' ),
					true
				) );

				return;
			}
		} catch ( InvalidTokenException $e ) {
			$this->notice->add( new Notice( Notice::ERROR,
				sprintf( '%s.', $e->getMessage() ),
				true
			) );

			return;
		}

		$license = $args[ self::LICENSE ] ?? '';
		$slug    = $args[ self::SLUG ] ?? '';

		// Store or override an existing license.
		if ( $license && $slug ) {
			if ( ! $this->collection->offsetExists( $slug ) ) {
				$this->notice->add( new Notice( Notice::ERROR,
					__( 'Plugin or Service slug not found.', 'iconic-wis' ),
					true
				) );

				return;
			}

			$plugin = $this->collection->offsetGet( $slug );

			if ( ! $plugin->set_license_key( $license, 'network' ) ) {
				$this->notice->add( new Notice( Notice::ERROR,
					__( 'Error storing license key.', 'iconic-wis' ),
				true
				) );

				return;
			}
		}

		$this->notice->add(
			new Notice( Notice::SUCCESS,
				__( 'Connected successfully.', 'iconic-wis' ),
				true
			)
		);
	}
}
