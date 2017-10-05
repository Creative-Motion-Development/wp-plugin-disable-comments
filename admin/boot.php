<?php
	/**
	 * Admin boot
	 * @author Webcraftic <alex.kovalevv@gmail.com>
	 * @copyright Webcraftic 25.05.2017
	 * @version 1.0
	 */

	require_once(WBCR_UPM_PLUGIN_DIR . '/admin/pages/updates.php');

	if( !defined('LOADING_UPDATE_SERVICES_AS_ADDON') ) {
		require_once(WBCR_UPM_PLUGIN_DIR . '/admin/pages/more-features.php');
	}

	/**
	 * Add settings link in plugins list
	 *
	 * @param $links
	 * @return mixed
	 */
	function wbcr_upm_add_settings_link($links)
	{
		global $wbcr_update_services_plugin;

		$settings_link = '<a href="' . admin_url('options-general.php?page=updates-' . $wbcr_update_services_plugin->pluginName) . '&' . $wbcr_update_services_plugin->pluginName . '_screen=updates">' . __('Settings') . '</a>';
		array_unshift($links, $settings_link);

		return $links;
	}

	// plugin settings link
	add_filter("plugin_action_links_" . WBCR_UPM_PLUGIN_BASE, 'wbcr_upm_add_settings_link');

	function wbcr_upm_group_options($options)
	{
		$options[] = array(
			'name' => 'plugin_updates',
			'title' => __('Disable plugin updates', 'update-services'),
			'tags' => array('disable_all_updates'),
			'values' => array('disable_all_updates' => 'disable_plugin_updates')
		);
		$options[] = array(
			'name' => 'theme_updates',
			'title' => __('Disable theme updates', 'update-services'),
			'tags' => array('disable_all_updates'),
			'values' => array('disable_all_updates' => 'disable_theme_updates')
		);
		$options[] = array(
			'name' => 'auto_tran_update',
			'title' => __('Disable Automatic Translation Updates', 'update-services'),
			'tags' => array('disable_all_updates')
		);
		$options[] = array(
			'name' => 'wp_update_core',
			'title' => __('Disable wordPress core updates', 'update-services'),
			'tags' => array('disable_all_updates'),
			'values' => array('disable_all_updates' => 'disable_core_updates')
		);
		$options[] = array(
			'name' => 'enable_update_vcs',
			'title' => __('Enable updates for VCS Installations', 'update-services'),
			'tags' => array()
		);

		return $options;
	}

	add_filter("wbcr_clearfy_group_options", 'wbcr_upm_group_options');

	function wbcr_upm_allow_quick_mods($mods)
	{
		$mods['disable_all_updates'] = __('Disable all updates', 'update-services');

		return $mods;
	}

	add_filter("wbcr_clearfy_allow_quick_mods", 'wbcr_upm_allow_quick_mods');





