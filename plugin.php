<?php
/**
 * Plugin Name:     Dekode Label Environment
 * Plugin URI:      https://github.com/DekodeInteraktiv/dekode-label-environment
 * Description:     Adds a banner to the frontend and an item to the admin bar informing about the server environment
 * Author:          Dekode
 * Author URI:      https://dekode.no
 * Version:         1.0.0
 *
 * @package         ProjectBank
 */

declare( strict_types = 1 );

namespace Dekode\Label_Environment;

// Shows on all environment except from on production. Can be turned off by filter.
if ( \apply_filters( 'dekode_label_environment_enabled', true ) ) {

	\add_action( 'admin_bar_menu', __NAMESPACE__ . '\\add_environment_to_admin_bar', 999 );
	\add_action( 'admin_head', __NAMESPACE__ . '\\add_admin_bar_styles', 999 );

	/**
	 * Add information about the environment in the admin bar
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance, passed by reference.
	 *
	 * @return void
	 */
	function add_environment_to_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
		$args = [
			'id'    => 'dekode-label-environment',
			'title' => \sprintf( 'Environment: %s', \esc_html( \wp_get_environment_type() ) ),
		];
		$wp_admin_bar->add_node( $args );
	}

	// Display the banner on all environments except production.
	if ( \apply_filters( 'dekode_label_environment_banner_enabled', ( 'production' !== \wp_get_environment_type() ) ) ) {
		\add_action( 'wp_head', __NAMESPACE__ . '\\add_banner_styles' );
	}

	/**
	 * Add inline style to head for displaying admin bar environment information in admin
	 * Remove pointer events since the text is not linked
	 *
	 * @return void
	 */
	function add_admin_bar_styles() {
		echo '<style>#wp-admin-bar-dekode-label-environment{pointer-events:none;}</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Add inline style to head for displaying the banner on frontend
	 *
	 * @return void
	 */
	function add_banner_styles() {
		$css = 'body:after{'
		. 'align-items:center;'
		. 'background-color:#000;'
		. 'color:#fff;'
		. 'content:"' . \wp_get_environment_type() . '";'
		. 'display:flex;'
		. 'font-size:12px;'
		. 'left:0;'
		. 'line-height:24px;'
		. 'justify-content:center;'
		. 'position:fixed;'
		. 'top:56px;'
		. 'transform:rotate(-45deg);'
		. 'transform-origin:bottom left;'
		. 'width:112px;'
		. 'z-index:50;'
		. '}'
		. 'body.admin-bar:after{' // Admin bar has 32px height - bump label down.
		. 'top:88px;'
		. '}'
		. '@media screen and (max-width: 782px) {'
		. 'body.admin-bar:after{' // Admin bar has 46px height on screen resolution 782px and less - bump label down.
		. 'top:102px;'
		. '}'
		. '}';
		echo '<style>' . $css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
