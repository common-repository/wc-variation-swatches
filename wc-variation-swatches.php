<?php
/**
 * Plugin Name:          Product Variation Swatches for WooCommerce
 * Plugin URI:           https://pluginever.com/plugins/wc-variation-swatches-pro/
 * Description:          Provides a much nicer way to display variations of variable products.
 * Version:              1.1.1
 * Author:               PluginEver
 * Author URI:           https://pluginever.com
 * License:              GPL v2 or later
 * License URI:          https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:          wc-variation-swatches
 * Domain Path:          languages/
 * Requires Plugins:     woocommerce
 * Requires at least:    5.2
 * Tested up to:         6.6
 * Requires PHP:         7.4
 * WC requires at least: 3.0.0
 * WC tested up to:      9.3
 *
 * @package WooCommerceVariationSwatches
 */

/**
 * Copyright (c) 2024 PluginEver (email : support@pluginever.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

use WooCommerceVariationSwatches\Plugin;

// don't call the file directly.
defined( 'ABSPATH' ) || exit();

// Require the autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Instantiate the plugin.
WooCommerceVariationSwatches\Plugin::create(
	array(
		'file'         => __FILE__,
		'settings_url' => admin_url( 'edit.php?post_type=product&page=wc-variation-swatches' ),
		'docs_url'     => 'https://pluginever.com/docs/wc-variation-swatches/',
		'support_url'  => 'https://pluginever.com/support/',
		'review_url'   => 'https://wordpress.org/support/plugin/wc-variation-swatches/reviews/#new-post',
	)
);
