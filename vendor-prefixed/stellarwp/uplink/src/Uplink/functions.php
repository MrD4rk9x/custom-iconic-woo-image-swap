<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );

namespace Iconic_WIS_NS\StellarWP\Uplink;

use Iconic_WIS_NS\StellarWP\Uplink\API\V3\Auth\Contracts\Token_Authorizer;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Auth_Url_Builder;
use Iconic_WIS_NS\StellarWP\Uplink\Auth\Token\Contracts\Token_Manager;
use Iconic_WIS_NS\StellarWP\Uplink\Components\Admin\Authorize_Button_Controller;
use Throwable;

/**
 * Displays the token authorization button, which allows admins to
 * authorize their product through your origin server and clear the
 * token locally by disconnecting.
 *
 * @param string $slug The Product slug to render the button for.
 * @param string $domain An optional domain associated with a license key to pass along.
 */
function render_authorize_button( string $slug, string $domain = '' ): void {
	try {
		Config::get_container()->get( Authorize_Button_Controller::class )
			->render( [
				'slug'   => $slug,
				'domain' => $domain,
			] );
	} catch ( Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "Unable to render authorize button: {$e->getMessage()} {$e->getFile()}:{$e->getLine()} {$e->getTraceAsString()}" );
		}
	}
}

/**
 * Get the stored authorization token.
 *
 * @return string|null
 */
function get_authorization_token(): ?string {
	try {
		return Config::get_container()->get( Token_Manager::class )->get();
	} catch ( Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "Error occurred when fetching token: {$e->getMessage()} {$e->getFile()}:{$e->getLine()} {$e->getTraceAsString()}" );
		}

		return null;
	}
}

/**
 * Check if a license is authorized.
 *
 * @note This response may be cached.
 *
 * @param  string  $license  The license key.
 * @param  string  $token  The stored token.
 * @param  string  $domain  The user's domain.
 *
 * @return bool
 */
function is_authorized( string $license, string $token, string $domain ): bool {
	try {
		return Config::get_container()
		             ->get( Token_Authorizer::class )
		             ->is_authorized( $license, $token, $domain );
	} catch ( Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "An Authorization error occurred: {$e->getMessage()} {$e->getFile()}:{$e->getLine()} {$e->getTraceAsString()}" );
		}

		return false;
	}
}

/**
 * Build a brand's authorization URL, with the uplink_callback base64 query variable.
 *
 * @param  string  $slug  The Product slug to render the button for.
 * @param  string  $domain  An optional domain associated with a license key to pass along.
 *
 * @return string
 */
function build_auth_url( string $slug, string $domain = '' ): string {
	try {
		return Config::get_container()->get( Auth_Url_Builder::class )
		                              ->build( $slug, $domain );
	} catch ( Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( "Error building auth URL: {$e->getMessage()} {$e->getFile()}:{$e->getLine()} {$e->getTraceAsString()}" );
		}

		return '';
	}
}
