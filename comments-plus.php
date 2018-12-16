<?php
/**
 * Plugin Name: Webcraftic Disable Comments
 * Plugin URI: https://wordpress.org/plugins/comments-plus/
 * Description: Allows administrators to globally disable comments on their site. Comments can be disabled for individual record types.
 * Author: Webcraftic <wordpress.webraftic@gmail.com>
 * Version: 1.0.9
 * Text Domain: comments-plus
 * Domain Path: /languages/
 * Author URI: https://clearfy.pro
 * Framework Version: FACTORY_000_VERSION
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'WCM_PLUGIN_VERSION' ) ) {
	define( 'WCM_PLUGIN_VERSION', '1.0.9' );
}
if ( ! defined( 'WCM_PLUGIN_DIR' ) ) {
	define( 'WCM_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'WCM_PLUGIN_BASE' ) ) {
	define( 'WCM_PLUGIN_BASE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'WCM_PLUGIN_URL' ) ) {
	define( 'WCM_PLUGIN_URL', plugins_url( null, __FILE__ ) );
}

#comp remove
// the following constants are used to debug features of diffrent builds
// on developer machines before compiling the plugin

// build: free, premium, ultimate
if ( ! defined( 'BUILD_TYPE' ) ) {
	define( 'BUILD_TYPE', 'free' );
}
// language: en_US, ru_RU
if ( ! defined( 'LANG_TYPE' ) ) {
	define( 'LANG_TYPE', 'en_EN' );
}
// license: free, paid
if ( ! defined( 'LICENSE_TYPE' ) ) {
	define( 'LICENSE_TYPE', 'free' );
}

// wordpress language
if ( ! defined( 'WPLANG' ) ) {
	define( 'WPLANG', LANG_TYPE );
}
// the compiler library provides a set of functions like onp_build and onp_license
// to check how the plugin work for diffrent builds on developer machines

if ( ! defined( 'LOADING_COMMENTS_PLUS_AS_ADDON' ) ) {
	require( 'libs/onepress/compiler/boot.php' );
	// creating a plugin via the factory
}
// #fix compiller bug new Factory000_Plugin
#endcomp

if ( ! defined( 'LOADING_COMMENTS_PLUS_AS_ADDON' ) ) {
	require_once( WCM_PLUGIN_DIR . '/libs/factory/core/includes/check-compatibility.php' );
	require_once( WCM_PLUGIN_DIR . '/libs/factory/clearfy/includes/check-clearfy-compatibility.php' );
}

$plugin_info = array(
	'prefix'         => 'wbcr_comments_plus_', // wbcr_cmp
	'plugin_name'    => 'wbcr_comments_plus',
	'plugin_title'   => __( 'Webcraftic Disable comments', 'comments-plus' ),
	'plugin_version' => WCM_PLUGIN_VERSION,
	'plugin_build'   => BUILD_TYPE,
	//'updates' => WCM_PLUGIN_DIR . '/updates/'
);

/**
 * Проверяет совместимость с Wordpress, php и другими плагинами.
 */
$compatibility = new Wbcr_FactoryClearfy_Compatibility( array_merge( $plugin_info, array(
	'factory_version'                  => 'FACTORY_000_VERSION',
	'plugin_already_activate'          => defined( 'WCM_PLUGIN_ACTIVE' ),
	'plugin_as_component'              => defined( 'LOADING_COMMENTS_PLUS_AS_ADDON' ),
	'plugin_dir'                       => WCM_PLUGIN_DIR,
	'plugin_base'                      => WCM_PLUGIN_BASE,
	'plugin_url'                       => WCM_PLUGIN_URL,
	'required_php_version'             => '5.3',
	'required_wp_version'              => '4.2.0',
	'required_clearfy_check_component' => true
) ) );

/**
 * Если плагин совместим, то он продолжит свою работу, иначе будет остановлен,
 * а пользователь получит предупреждение.
 */
if ( ! $compatibility->check() ) {
	return;
}

define( 'WCM_PLUGIN_ACTIVE', true );

if ( ! defined( 'LOADING_COMMENTS_PLUS_AS_ADDON' ) ) {
	require_once( WCM_PLUGIN_DIR . '/libs/factory/core/boot.php' );
}

require_once( WCM_PLUGIN_DIR . '/includes/class.plugin.php' );

if ( ! defined( 'LOADING_COMMENTS_PLUS_AS_ADDON' ) ) {
	new WCM_Plugin( __FILE__, $plugin_info );
}
