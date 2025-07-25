<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );
/**
 * The authorize button view, allowing the user to authorize their install with
 * the license server via the origin site.
 *
 * @see \Iconic_WIS_NS\StellarWP\Uplink\Components\Admin\Authorize_Button_Controller
 *
 * @var string $link_text The link text, changes based on whether the user is authorized to authorize :)
 * @var string $url The location the link goes to, either the custom origin URL, or a link to the admin.
 * @var string $target The link target.
 * @var string $tag The HTML tag to use for the wrapper.
 * @var string $classes The CSS classes for the hyperlink.
 */

defined( 'ABSPATH' ) || exit;
?>

<<?php echo esc_html( $tag ) ?> class="uplink-authorize-container">
	<a href="<?php echo esc_url( $url ) ?>"
	   target="<?php echo $target ? esc_attr( $target ) : '' ?>"
	   <?php echo $classes ? sprintf( 'class="%s"', esc_attr( $classes ) ) : '' ?>
	>
		<?php echo esc_html( $link_text ) ?>
	</a>
</<?php echo esc_html( $tag ) ?>>

