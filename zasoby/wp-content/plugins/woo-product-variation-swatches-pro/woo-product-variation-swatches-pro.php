<?php
/**
 * Plugin Name:             Variation Swatches for WooCommerce Pro
 * Plugin URI:              https://radiustheme.com
 * Description:             This is the Pro version of Variation Swatches for WooCommerce.
 * Version:                 2.2.1
 * Author:                  RadiusTheme
 * Author URI:              https://radiustheme.com
 * Requires at least:       4.8
 * Tested up to:            6.1
 * WC requires at least:    3.2
 * WC tested up to:         5.1
 * Domain Path:             /languages
 * Text Domain:             woo-product-variation-swatches-pro
 */
if (!defined('ABSPATH')) {
	exit;
}

// Define PLUGIN_FILE.
if (!defined('RTWPVSP_PLUGIN_FILE')) {
	define('RTWPVSP_PLUGIN_FILE', __FILE__);
}

// Define VERSION.
if (!defined('RTWPVSP_VERSION')) {
	define('RTWPVSP_VERSION', '2.2.1');
}

add_action('admin_notices', function () {
	if (!function_exists('rtwpvs')) {
		$class = 'notice notice-error';
		$text  = esc_html__('Variation Swatches for WooCommerce', 'woo-product-variation-swatches-pro');
		$link  = esc_url(
			add_query_arg(
				[
					'tab'       => 'plugin-information',
					'plugin'    => 'woo-product-variation-swatches',
					'TB_iframe' => 'true',
					'width'     => '640',
					'height'    => '500',
				], admin_url('plugin-install.php')
			)
		);
		$link_pro = 'https://www.radiustheme.com/downloads/woocommerce-variation-swatches';

		printf('<div class="%1$s"><p><a target="_blank" href="%3$s"><strong>Variation Swatches for WooCommerce Pro</strong></a> is not working, You need to install and activate <a class="thickbox open-plugin-details-modal" href="%2$s"><strong>%4$s</strong></a> free version to get the pro features.</p></div>', $class, $link, $link_pro, $text);
	}
});

add_action('rtwpvs_loaded', function () {
	if (!class_exists('Rtwpvsp')) {
		require_once 'app/Rtwpvsp.php';
	}
});
