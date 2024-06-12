<?php
/**
 * Plugin Name: Customer Who Viewed This Item Also Viewed Using Woocommerce
 * Plugin URL: http://wordpress.org/plugins/woocommerce-customer-also-viewed-this-item
 * Description:  This plugin will suggest your site visitors with products which were mostly explored by other customers.
 * Version: 3.1
 * Author: ZealousWeb
 * Author URI: https://zealousweb.com
 * Developer: The Zealousweb Team
 * Developer E-Mail: opensource@zealousweb.com
 * Text Domain: Customer-who-viewed-this-item-also-viewed-using-woocommerce
 * Domain Path: /languages
 *
 * Copyright: © 2009-2020 ZealousWeb Technologies.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
/**
 * @access      public
 * @since       1.0
 * @return      $content
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !defined( 'WCCWVZW_TEXT_DOMAIN' ) ) {
	define( 'WCCWVZW_TEXT_DOMAIN', 'customer-who-viewed-this-item-also-viewed-using-woocommerce' ); // Plugin's text domain
}

if ( !defined( 'WCCWVZW_FILE' ) ) {
	define( 'WCCWVZW_FILE', __FILE__ ); // Plugin File
}

if ( !defined( 'WCCWVZW_PLUGIN_BASENAME' ) ) {
	define( 'WCCWVZW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}

if ( !defined( 'WCCWVZW_DIR' ) ) {
	define( 'WCCWVZW_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if ( !defined( 'WCCWVZW_PLUGIN_PATH' ) ) {
	define( 'WCCWVZW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // Plugin Path
}

if ( !defined( 'WCCWVZW_PREFIX' ) ) {
	define( 'WCCWVZW_PREFIX', 'wccwvzw' ); // Plugin prefix
}

/**
 * include admin and front file
 *
 */
require_once( WCCWVZW_DIR . '/inc/front/' . WCCWVZW_PREFIX . '.front.php' );
require_once( WCCWVZW_DIR . '/inc/admin/' . WCCWVZW_PREFIX . '.admin.php' );