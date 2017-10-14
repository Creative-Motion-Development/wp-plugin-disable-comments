<?php

	// if uninstall.php is not called by WordPress, die
	if( !defined('WP_UNINSTALL_PLUGIN') ) {
		die;
	}

	// remove plugin options
	global $wpdb, $wbcr_comments_plus_plugin;

	$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '" . $wbcr_comments_plus_plugin->pluginName . "_%';");
