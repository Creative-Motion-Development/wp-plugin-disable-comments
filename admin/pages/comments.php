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

		public $dashicon = 'dashicons-testimonial';

		public function __construct(Factory000_Plugin $plugin)
		{
			$this->menuTitle = $plugin->pluginTitle;
			$this->internal = defined('LOADING_COMMENTS_PLUS_AS_ADDON');

			parent::__construct($plugin);
		}

		public function getMenuTitle()
		{
			return defined('LOADING_COMMENTS_PLUS_AS_ADDON')
				? __('Comments', 'comments-plus')
				: __('General', 'comments-plus');
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
					array('enable_comments', __('Enable comments', 'comments-plus')),
					array('disable_comments', __('Everywhere', 'comments-plus')),
					array('disable_certain_post_types_comments', __('On certain post types', 'comments-plus'))
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('Everywhere - Warning: This option is global and will affect your entire site. Use it only if you want to disable comments everywhere. A complete description of what this option does is available here', 'comments-plus') . '<br><br>' . __('On certain post types - Disabling comments will also disable trackbacks and pingbacks. All comment-related fields will also be hidden from the edit/quick-edit screens of the affected posts. These settings cannot be overridden for individual posts.', 'comments-plus'),
				'default' => 'enable_comments',
				'events' => array(
					'disable_certain_post_types_comments' => array(
						'show' => '.factory-control-disable_comments_for_post_types'
					),
					'enable_comments' => array(
						'hide' => '.factory-control-disable_comments_for_post_types'
					),
					'disable_comments' => array(
						'hide' => '.factory-control-disable_comments_for_post_types'
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
				'hint' => __('fsdfsdf', 'comments-plus'),
				//'default' => array('post', 'page', 'attachments')
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

			$formOptions = array();

			$formOptions[] = array(
				'type' => 'form-group',
				'items' => $options,
				//'cssClass' => 'postbox'
			);

			return apply_filters('wbcr_cmp_comments_form_options', $formOptions);
		}
	}

	FactoryPages000::register($wbcr_clearfy_plugin, 'WbcrCmp_CommentsPage');
