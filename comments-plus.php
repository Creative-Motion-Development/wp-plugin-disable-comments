<?php
	/**
	 * Plugin Name: Webcraftic Disable comments
	 * Plugin URI: https://wordpress.org/plugins/comments-plus/
	 * Description: Disable comments description.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.0
	 * Text Domain: comments-plus
	 * Domain Path: /languages/
	 */

	if( defined('WBCR_CMP_PLUGIN_ACTIVE') || (defined('WBCR_CLEARFY_PLUGIN_ACTIVE') && !defined('LOADING_COMMENTS_PLUS_AS_ADDON')) ) {
		function wbcr_cmp_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - disable unused features" plugin installed, this plugin already has disable comments functions, so you can deactivate plugin "Disable comments"!'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_cmp_admin_notice_error');

		return;
	} else {

		define('WBCR_CMP_PLUGIN_ACTIVE', true);

		define('WBCR_CMP_PLUGIN_DIR', dirname(__FILE__));
		define('WBCR_CMP_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WBCR_CMP_PLUGIN_URL', plugins_url(null, __FILE__));

		#comp remove
		// the following constants are used to debug features of diffrent builds
		// on developer machines before compiling the plugin

		// build: free, premium, ultimate
		if( !defined('BUILD_TYPE') ) {
			define('BUILD_TYPE', 'free');
		}
		// language: en_US, ru_RU
		if( !defined('LANG_TYPE') ) {
			define('LANG_TYPE', 'en_EN');
		}
		// license: free, paid
		if( !defined('LICENSE_TYPE') ) {
			define('LICENSE_TYPE', 'free');
		}

		// wordpress language
		if( !defined('WPLANG') ) {
			define('WPLANG', LANG_TYPE);
		}
		// the compiler library provides a set of functions like onp_build and onp_license
		// to check how the plugin work for diffrent builds on developer machines

		if( !defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
			require('libs/onepress/compiler/boot.php');
			// creating a plugin via the factory
		}
		#endcomp

		if( !defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
			require_once(WBCR_CMP_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		function wbcr_cmp_plugin_init()
		{
			global $wbcr_comments_plus_plugin;

			// Localization plugin
			load_plugin_textdomain('comments-plus', false, dirname(WBCR_CMP_PLUGIN_BASE) . '/languages/');

			if( defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
				//return;
				global $wbcr_clearfy_plugin;
				$wbcr_comments_plus_plugin = $wbcr_clearfy_plugin;
			} else {

				$wbcr_comments_plus_plugin = new Factory000_Plugin(__FILE__, array(
					'name' => 'wbcr_comments_plus',
					'title' => __('Webcraftic Disable comments', 'comments-plus'),
					'version' => '1.0.0',
					'host' => 'wordpress.org',
					'url' => 'https://wordpress.org/plugins/comments-plus/',
					'assembly' => BUILD_TYPE,
					'updates' => WBCR_CMP_PLUGIN_DIR . '/updates/'
				));

				// requires factory modules
				$wbcr_comments_plus_plugin->load(array(
					array('libs/factory/bootstrap', 'factory_bootstrap_000', 'admin'),
					array('libs/factory/forms', 'factory_forms_000', 'admin'),
					array('libs/factory/pages', 'factory_pages_000', 'admin'),
					array('libs/factory/font-awesome', 'factory_pages_000', 'admin'),
					array('libs/factory/clearfy', 'factory_clearfy_000', 'all')
				));
			}

			// loading other files
			if( is_admin() ) {
				require(WBCR_CMP_PLUGIN_DIR . '/admin/boot.php');
			}

			require(WBCR_CMP_PLUGIN_DIR . '/includes/classes/class.configurate-comments.php');

			new WbcrCmp_ConfigComments($wbcr_comments_plus_plugin);
		}

		if( defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
			wbcr_cmp_plugin_init();
		} else {
			add_action('plugins_loaded', 'wbcr_cmp_plugin_init');
		}
	}