<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );

namespace Iconic_WIS_NS\StellarWP\Uplink\API\V3\Auth;

use Iconic_WIS_NS\StellarWP\Uplink\API\V3\Contracts\Client_V3;
use Iconic_WIS_NS\StellarWP\Uplink\Traits\With_Debugging;
use WP_Error;
use WP_Http;

use function StellarWP\Uplink\is_authorized;

/**
 * Manages authorization.
 */
class Token_Authorizer implements Contracts\Token_Authorizer {

	use With_Debugging;

	/**
	 * @var Client_V3
	 */
	private $client;

	public function __construct( Client_V3 $client ) {
		$this->client = $client;
	}

	/**
	 * Manually check if a license is authorized.
	 *
	 * @see is_authorized()
	 *
	 * @param  string  $license  The license key.
	 * @param  string  $token  The stored token.
	 * @param  string  $domain  The user's domain.
	 *
	 * @return bool
	 */
	public function is_authorized( string $license, string $token, string $domain ): bool {
		$response = $this->client->get( 'tokens/auth', [
			'license' => $license,
			'token'   => $token,
			'domain'  => $domain,
		] );

		if ( $response instanceof WP_Error ) {
			if ( $this->is_wp_debug() ) {
				error_log( sprintf(
					__( 'Authorization error occurred: License: "%s", Token: "%s", Domain: "%s". Errors: %s', 'iconic-wis' ),
					$license,
					$token,
					$domain,
					implode( ', ', $response->get_error_messages() )
				) );
			}

			return false;
		}

		return $response['response']['code'] === WP_Http::OK;
	}

}
