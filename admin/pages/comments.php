<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	class WbcrCmp_CommentsPage extends FactoryPages000_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "comments";
		public $page_menu_dashicon = 'dashicons-testimonial';

		public $addLinkToPluginActions = true;

		public function __construct(Factory000_Plugin $plugin)
		{
			$this->menuTitle = __('Disable comments', 'comments-plus');

			if( !defined('LOADING_COMMENTS_PLUS_AS_ADDON') ) {
				$this->internal = false;
				$this->menuTarget = 'options-general.php';
			}

			add_filter('wbcr_factory_imppage_actions_notice', array($this, 'actionsNotice'));

			parent::__construct($plugin);
		}

		public function getMenuTitle()
		{
			return defined('LOADING_COMMENTS_PLUS_AS_ADDON')
				? __('Comments', 'comments-plus')
				: __('General', 'comments-plus');
		}

		/**
		 * We register notifications for some actions
		 * @param $notices
		 * @return array
		 */
		public function actionsNotice($notices)
		{
			$notices[] = array(
				'conditions' => array(
					'wbcr_cmp_clear_comments' => 1
				),
				'type' => 'success',
				'message' => __('All comments have been deleted.', 'factory_pages_000')
			);

			$notices[] = array(
				'conditions' => array(
					'wbcr_cmp_clear_comments_error' => 1,
					'wbcr_cmp_code' => 'interal_error'
				),
				'type' => 'danger',
				'message' => __('An error occurred while trying to delete comments. Internal error occured. Please try again later.', 'factory_pages_000')
			);

			return $notices;
		}

		public function clearCommentsAction()
		{
			global $wpdb;
			$comments_type = isset($_GET['wbcr_cmp_comments_type'])
				? sanitize_text_field($_GET['wbcr_cmp_comments_type'])
				: null;

			if( empty($comments_type) ) {
				wp_die(__('You are not allowed to view this page.', 'comments-plus'));
			}

			$get_all_selected_types = $this->getOption('disable_comments_for_post_types');
			$post_types = explode(',', $get_all_selected_types);

			$sql = "SELECT count(comment_id) from $wpdb->comments";

			if( $comments_type == 'certain_post_types' ) {
				if( empty($post_types) ) {
					wp_die(__('You do not have the selected post types!', 'comments-plus'));
				}

				$post_types_str = '';
				foreach($post_types as $type_name) {
					$post_types_str .= "'" . $type_name . "',";
				}
				$post_types_str = rtrim($post_types_str, ',');

				$sql = "SELECT SUM(comment_count) from $wpdb->posts WHERE post_type in ($post_types_str)";
			}

			$comments_count = $wpdb->get_var($sql);

			if( $comments_count <= 0 ) {
				wp_die(__('No comments available for deletion.', 'comments-plus'));
			}

			if( $comments_type == 'all' ) {
				$this->confirmPageTemplate(array(
					'title' => __('Are you sure that you desire to delete all comments from the database?', 'comments-plus'),
					'description' => __('Deleting comments will remove existing comment entries in the database and cannot be reverted without a database backup.', 'comments-plus'),
					'hint' => sprintf(__('You have %s comments', 'comments-plus'), $comments_count),
					'actions' => array(
						'onp_confirm' => array(
							'class' => 'btn btn-danger',
							'title' => __("Yes, I'm sure", 'comments-plus'),
							'url' => $this->getActionUrl('clearComments', array(
								'wbcr_cmp_comments_type' => 'all',
								'wbcr_cmp_confirmed' => true
							))
						),
						'onp_cancel' => array(
							'class' => 'btn btn-default',
							'title' => __("No, return back", 'comments-plus'),
							'url' => $this->getActionUrl('index')
						),
					)
				));

				if( isset($_GET['wbcr_cmp_confirmed']) ) {
					if( $wpdb->query("TRUNCATE $wpdb->commentmeta") != false ) {
						if( $wpdb->query("TRUNCATE $wpdb->comments") != false ) {
							$wpdb->query("UPDATE $wpdb->posts SET comment_count = 0 WHERE post_author != 0");
							$wpdb->query("OPTIMIZE TABLE $wpdb->commentmeta");
							$wpdb->query("OPTIMIZE TABLE $wpdb->comments");

							$this->redirectToAction('index', array(
								'wbcr_cmp_clear_comments' => '1'
							));
						} else {
							$this->redirectToAction('index', array(
								'wbcr_cmp_clear_comments_error' => '1',
								'wbcr_cmp_code' => 'interal_error',
							));
						}
					} else {
						$this->redirectToAction('index', array(
							'wbcr_cmp_clear_comments_error' => '1',
							'wbcr_cmp_code' => 'interal_error',
						));
					}
				}

				return;
			}

			$this->confirmPageTemplate(array(
				'title' => sprintf(__('Are you sure that you desire to delete all comments from the database for the selected post types (%s)?', 'comments-plus'), implode(',', $post_types)),
				'description' => __('Deleting comments will remove existing comment entries in the database and cannot be reverted without a database backup.', 'comments-plus'),
				'hint' => sprintf(__('You have %s comments', 'comments-plus'), $comments_count),
				'actions' => array(
					'onp_confirm' => array(
						'class' => 'btn btn-danger',
						'title' => __("Yes, I'm sure", 'comments-plus'),
						'url' => $this->getActionUrl('clearComments', array(
							'wbcr_cmp_comments_type' => 'certain_post_types',
							'wbcr_cmp_confirmed' => true
						))
					),
					'onp_cancel' => array(
						'class' => 'btn btn-default',
						'title' => __("No, return back", 'comments-plus'),
						'url' => $this->getActionUrl('index')
					),
				)
			));

			if( isset($_GET['wbcr_cmp_confirmed']) ) {
				// Loop through post_types and remove comments/meta and set posts comment_count to 0
				foreach($post_types as $delete_post_type) {
					$wpdb->query("DELETE cmeta FROM $wpdb->commentmeta cmeta INNER JOIN $wpdb->comments comments ON cmeta.comment_id=comments.comment_ID INNER JOIN $wpdb->posts posts ON comments.comment_post_ID=posts.ID WHERE posts.post_type = '$delete_post_type'");
					$wpdb->query("DELETE comments FROM $wpdb->comments comments INNER JOIN $wpdb->posts posts ON comments.comment_post_ID=posts.ID WHERE posts.post_type = '$delete_post_type'");
					$wpdb->query("UPDATE $wpdb->posts SET comment_count = 0 WHERE post_author != 0 AND post_type = '$delete_post_type'");
				}

				$wpdb->query("OPTIMIZE TABLE $wpdb->commentmeta");
				$wpdb->query("OPTIMIZE TABLE $wpdb->comments");

				$this->redirectToAction('index', array(
					'wbcr_cmp_clear_comments' => '1'
				));
			}
		}


		/**
		 * Permalinks options.
		 *
		 * @since 1.0.0
		 * @return mixed[]
		 */
		public function getOptions()
		{
			$options = array();
			$types = get_post_types(array('public' => true), 'objects');
			$post_types = array();
			foreach($types as $type_name => $type) {
				$post_types[] = array($type_name, $type->label);
			}

			$options[] = array(
				'type' => 'dropdown',
				'name' => 'disable_comments',
				'way' => 'buttons',
				'title' => __('Disable comments', 'comments-plus'),
				'data' => array(
					array('enable_comments', __('Not disable', 'comments-plus')),
					array(
						'disable_comments',
						__('Everywhere', 'comments-plus'),
						sprintf(__('You can delete all comments in the database by clicking on this link (<a href="%s">cleaning comments in database</a>).', 'comments-plus'), $this->getActionUrl('clearComments', array('wbcr_cmp_comments_type' => 'all')))
					),
					array(
						'disable_certain_post_types_comments',
						__('On certain post types', 'comments-plus'),
						sprintf(__('You can delete all comments for the selected post types. Select the post types below and save the settings. After that, click the link (<a href="%s">delete all comments for the selected post types in database</a>).', 'comments-plus'), $this->getActionUrl('clearComments', array('wbcr_cmp_comments_type' => 'certain_post_types')))
					)
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('Everywhere - Warning: This option is global and will affect your entire site. Use it only if you want to disable comments everywhere. A complete description of what this option does is available here', 'comments-plus') . '<br><br>' . __('On certain post types - Disabling comments will also disable trackbacks and pingbacks. All comment-related fields will also be hidden from the edit/quick-edit screens of the affected posts. These settings cannot be overridden for individual posts.', 'comments-plus'),
				'default' => 'enable_comments',
				'events' => array(
					'disable_certain_post_types_comments' => array(
						'show' => '.factory-control-disable_comments_for_post_types, .factory-control-remove_x_pingback, .factory-control-comment_text_convert_links_pseudo, .factory-control-pseudo_comment_author_link, .factory-control-remove_url_from_comment_form'
					),
					'enable_comments' => array(
						'show' => '.factory-control-remove_x_pingback, .factory-control-comment_text_convert_links_pseudo, .factory-control-pseudo_comment_author_link, .factory-control-remove_url_from_comment_form',
						'hide' => '.factory-control-disable_comments_for_post_types'
					),
					'disable_comments' => array(
						'hide' => '.factory-control-disable_comments_for_post_types, .factory-control-remove_x_pingback, .factory-control-comment_text_convert_links_pseudo, .factory-control-pseudo_comment_author_link, .factory-control-remove_url_from_comment_form'
					)
				)
			);

			$options[] = array(
				'type' => 'list',
				'way' => 'checklist',
				'name' => 'disable_comments_for_post_types',
				'title' => __('Select post types', 'comments-plus'),
				'data' => $post_types,
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('Select the post types for which comments will be disabled', 'comments-plus'),
				'default' => array('post', 'page', 'attachments')
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'remove_url_from_comment_form',
				'title' => __('Remove field "site" in comment form', 'comments-plus'),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('Tired of spam in the comments? Do visitors leave "blank" comments for the sake of a link to their site?', 'comments-plus') . '<br><b>Clearfy: </b>' . __('Removes the "Site" field from the comment form.', 'comments-plus') . '<br>--<br><span class="hint-warnign-color"> *' . __('Works with the standard comment form, if the form is manually written in your theme-it probably will not work!', 'comments-plus') . '</span>',
				'default' => false
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'comment_text_convert_links_pseudo',
				'title' => __('Replace external links in comments on the JavaScript code', 'comments-plus') . ' <span class="wbcr-clearfy-recomended-text">(' . __('Recommended', 'comments-plus') . ')</span>',
				'layout' => array('hint-type' => 'icon'),
				'hint' => __('Superfluous external links from comments, which can be typed from a dozen and more for one article, do not bring anything good for promotion.', 'comments-plus') . '<br><br><b>Clearfy: </b>' . sprintf(__('Replaces the links of this kind of %s, on links of this kind %s', 'comments-plus'), '<code>a href="http://yourdomain.com" rel="nofollow"</code>', '<code>span data-uri="http://yourdomain.com"</code>'),
				'default' => false
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'pseudo_comment_author_link',
				'title' => __('Replace external links from comment authors on the JavaScript code', 'comments-plus') . ' <span class="wbcr-clearfy-recomended-text">(' . __('Recommended', 'comments-plus') . ')</span>',
				'layout' => array('hint-type' => 'icon'),
				'hint' => __('Up to 90 percent of comments in the blog can be left for the sake of an external link. Even nofollow from page weight loss here does not help.', 'comments-plus') . '<br><br><b>Clearfy: </b>' . __('Replaces the links of the authors of comments on the JavaScript code, it is impossible to distinguish it from usual links.', 'comments-plus') . '<br>--<br><i>' . __('In some Wordpress topics this may not work.', 'comments-plus') . '</i>',
				'default' => false
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'remove_x_pingback',
				'title' => __('Disable XML-RPC', 'clearfy') . ' <span class="wbcr-clearfy-recomended-text">(' . __('Recommended', 'comments-plus') . ')</span>',
				'layout' => array('hint-type' => 'icon'),
				'hint' => __('A pingback is basically an automated comment that gets created when another blog links to you. A self-pingback is created when you link to an article within your own blog. Pingbacks are essentially nothing more than spam and simply waste resources.', 'comments-plus') . '<br><b>Clearfy: </b>' . __('Removes the server responses a reference to the xmlrpc file.', 'clearfy'),
				'default' => false
			);

			$formOptions = array();

			$formOptions[] = array(
				'type' => 'form-group',
				'items' => $options,
				//'cssClass' => 'postbox'
			);

			return apply_filters('wbcr_cmp_comments_form_options', $formOptions);
		}
	}

	FactoryPages000::register($wbcr_comments_plus_plugin, 'WbcrCmp_CommentsPage');
