<?php
	/**
	 * Admin boot
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright Webcraftic 25.05.2017
	 * @version 1.0
	 */

	require(WBCR_CMP_PLUGIN_DIR . '/admin/pages/comments.php');

	if( !defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
		require(WBCR_CMP_PLUGIN_DIR . '/admin/pages/more-features.php');
	}

	/**
	 * Add settings link in plugins list
	 *
	 * @param $links
	 * @return mixed
	 */
	function wbcr_cmp_add_settings_link($links)
	{
		global $wbcr_comments_plus_plugin;

		$settings_link = '<a href="' . admin_url('options-general.php?page=comments-' . $wbcr_comments_plus_plugin->pluginName) . '&' . $wbcr_comments_plus_plugin->pluginName . '_screen=comments">' . __('Settings') . '</a>';
		array_unshift($links, $settings_link);

		return $links;
	}

	// plugin settings link
	add_filter("plugin_action_links_" . WBCR_CMP_PLUGIN_BASE, 'wbcr_cmp_add_settings_link');

	function wbcr_cmp_group_options($options)
	{
		$options[] = array(
			'name' => 'disable_comments',
			'title' => __('Disable comments on the entire site', 'comments-plus'),
			'tags' => array('disable_all_comments'),
			'values' => array('disable_all_comments' => 'disable_comments')
		);
		$options[] = array(
			'name' => 'comment_text_convert_links_pseudo',
			'title' => __('Replace external links in comments on the JavaScript code', 'comments-plus'),
			'tags' => array('recommended', 'seo_optimize')
		);
		$options[] = array(
			'name' => 'pseudo_comment_author_link',
			'title' => __('Replace external links from comment authors on the JavaScript code', 'comments-plus'),
			'tags' => array('recommended', 'seo_optimize')
		);
		$options[] = array(
			'name' => 'remove_x_pingback',
			'title' => __('Disable X-Pingback', 'comments-plus'),
			'tags' => array('recommended', 'defence', 'disable_all_comments')
		);
		$options[] = array(
			'name' => 'remove_url_from_comment_form',
			'title' => __('Remove field "site" in comment form', 'comments-plus'),
			'tags' => array()
		);

		return $options;
	}

	add_filter("wbcr_clearfy_group_options", 'wbcr_cmp_group_options');

	function wbcr_cmp_allow_quick_mods($mods)
	{
		$mods['disable_all_comments'] = __('Disable all comments', 'comments-plus');
		
		return $mods;
	}

	add_filter("wbcr_clearfy_allow_quick_mods", 'wbcr_cmp_allow_quick_mods');





