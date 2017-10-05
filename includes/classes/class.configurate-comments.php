<?php
	
	/**
	 * This class configures the parameters advanced
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017 Webraftic Ltd
	 * @version 1.0
	 */
	class WbcrClearfy_ConfigAdvanced extends WbcrClearfy_Configurate {

		private $modified_types = array();

		public function registerActionsAndFilters()
		{
			/*if( $this->getOption('remove_url_from_comment_form') ) {
				add_filter('comment_form_default_fields', array($this, 'removeUrlFromCommentForm'));
			}*/

			// These need to happen now
			if( $this->isDisabledAllPosts() ) {
				add_action('widgets_init', array($this, 'disable_rc_widget'));
				add_filter('wp_headers', array($this, 'filter_wp_headers'));
				add_action('template_redirect', array($this, 'filter_query'), 9);    // before redirect_canonical

				// Admin bar filtering has to happen here since WP 3.6
				add_action('template_redirect', array($this, 'filter_admin_bar'));
				add_action('admin_init', array($this, 'filter_admin_bar'));
			}

			// These can happen later
			//add_action('plugins_loaded', array($this, 'register_text_domain'));
			add_action('wp_loaded', array($this, 'initWploadedFilters'));
		}

		private function isDisabledAllPosts()
		{
			return $this->getOption('disable_comments', 'enable_comments') == 'disable_comments';
		}


		/*
		 * Get an array of disabled post type.
		 */
		private function getDisabledPostTypes()
		{
			return $this->getOption('disable_comments_for_post_types');
		}

		/*
	      * Check whether comments have been disabled on a given post type.
		 */
		private function isPostTypeDisabled($type)
		{
			return in_array($type, $this->getDisabledPostTypes());
		}

		public function initWploadedFilters()
		{
			$disabled_post_types = $this->getDisabledPostTypes();
			if( !empty($disabled_post_types) ) {
				foreach($disabled_post_types as $type) {
					// we need to know what native support was for later
					if( post_type_supports($type, 'comments') ) {
						$this->modified_types[] = $type;
						remove_post_type_support($type, 'comments');
						remove_post_type_support($type, 'trackbacks');
					}
				}
				add_filter('comments_array', array($this, 'filter_existing_comments'), 20, 2);
				add_filter('comments_open', array($this, 'filter_comment_status'), 20, 2);
				add_filter('pings_open', array($this, 'filter_comment_status'), 20, 2);
			} elseif( is_admin() ) {
				add_action('all_admin_notices', array($this, 'setup_notice'));
			}

			// Filters for the admin only
			if( is_admin() ) {
				add_action('admin_menu', array($this, 'settings_menu'));
				add_action('admin_menu', array($this, 'tools_menu'));
				add_filter('plugin_action_links', array($this, 'plugin_actions_links'), 10, 2);

				add_action('admin_print_footer_scripts', array($this, 'discussion_notice'));
				add_filter('plugin_row_meta', array($this, 'set_plugin_meta'), 10, 2);

				// if only certain types are disabled, remember the original post status
				if( !($this->persistentModeAllowed() && $this->options['permanent']) && !$this->isDisabledAllPosts() ) {
					add_action('edit_form_advanced', array($this, 'edit_form_inputs'));
					add_action('edit_page_form', array($this, 'edit_form_inputs'));
				}

				if( $this->isDisabledAllPosts() ) {
					add_action('admin_menu', array($this, 'filter_admin_menu'), 9999);    // do this as late as possible
					add_action('admin_print_footer_scripts-index.php', array($this, 'dashboard_js'));
					add_action('wp_dashboard_setup', array($this, 'filter_dashboard'));
					add_filter('pre_option_default_pingback_flag', '__return_zero');
				}
			} // Filters for front end only
			else {
				add_action('template_redirect', array($this, 'check_comment_template'));

				if( $this->isDisabledAllPosts() ) {
					add_filter('feed_links_show_comments_feed', '__return_false');
				}
			}
		}

		private function persistentModeAllowed()
		{
			if( defined('DISABLE_COMMENTS_ALLOW_PERSISTENT_MODE') && DISABLE_COMMENTS_ALLOW_PERSISTENT_MODE == false ) {
				return false;
			}
		}
	}