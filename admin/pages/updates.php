<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	class WbcrUpm_UpdatesPage extends FactoryPages000_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "updates";

		public $dashicon = 'dashicons-cloud';

		public function __construct(Factory000_Plugin $plugin)
		{
			$this->menuTitle = $plugin->pluginTitle;
			$this->internal = defined('LOADING_UPDATE_SERVICES_AS_ADDON');

			parent::__construct($plugin);
		}

		public function getMenuTitle()
		{
			return defined('LOADING_UPDATE_SERVICES_AS_ADDON')
				? __('Updates', 'update-services')
				: __('General', 'update-services');
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

			$options[] = array(
				'type' => 'dropdown',
				'name' => 'plugin_updates',
				'way' => 'buttons',
				'title' => __('Plugin Updates', 'update-services'),
				'data' => array(
					array('enable_plugin_monual_updates', __('Monual updates', 'update-services')),
					array('enable_plugin_auto_updates', __('Enable auto updates', 'update-services')),
					array('disable_plugin_updates', __('Disable updates', 'update-services'))
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('You can disable all plugin updates or choose manual or automatic update mode.', 'update-services'),
				'default' => 'enable_plugin_monual_updates'
			);

			$options[] = array(
				'type' => 'dropdown',
				'name' => 'theme_updates',
				'way' => 'buttons',
				'title' => __('Theme Updates', 'update-services'),
				'data' => array(
					array('enable_theme_monual_updates', __('Monual updates', 'update-services')),
					array('enable_theme_auto_updates', __('Enable auto updates', 'update-services')),
					array('disable_theme_updates', __('Disable updates', 'update-services'))
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('You can disable all themes updates or choose manual or automatic update mode.', 'update-services'),
				'default' => 'enable_theme_monual_updates'
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'auto_tran_update',
				'title' => __('Disable Automatic Translation Updates', 'update-services'),
				//'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				//'hint' => __('', 'update-services') . '<br><br><b>Clearfy: </b>' . __('', 'update-services'),
				'default' => false,
			);
			$options[] = array(
				'type' => 'dropdown',
				'name' => 'wp_update_core',
				'title' => __('WordPress Core Updates', 'update-services'),
				'data' => array(
					array('disable_core_updates', __('Disable updates', 'update-services')),
					array('disable_core_auto_updates', __('Disable auto updates', 'update-services')),
					array('allow_minor_core_auto_updates', __('Allow minor auto updates', 'update-services')),
					array('allow_major_core_auto_updates', __('Allow major auto updates', 'update-services')),
					array('allow_dev_core_auto_updates', __('Allow development auto updates', 'update-services'))
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('You can disable all core WordPress updates, or disable only automatic updates. Also you can select the update mode. By default (minor)', 'update-services') . '<br>-' . __('Major - automatically update to major releases (e.g., 4.1, 4.2, 4.3).', 'update-services') . '<br>-' . __('Minor - automatically update to minor releases (e.g., 4.1.1, 4.1.2, 4.1.3)..', 'update-services') . '<br>-' . __('Development - update automatically to Bleeding Edge releases.', 'update-services'),
				'default' => 'allow_minor_core_auto_updates'
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'enable_update_vcs',
				'title' => __('Enable updates for VCS Installations', 'update-services'),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('Enable Automatic Updates even if a VCS folder (.git, .hg, .svn) was found in the WordPress directory', 'update-services'),
				'default' => false,
			);

			/*$options[] = array(
				'type' => 'separator',
				'cssClass' => 'factory-separator-dashed'
			);

			$options[] = array(
				'type' => 'html',
				'html' => array($this, '_showFormButton')
			);*/

			$formOptions = array();

			$formOptions[] = array(
				'type' => 'form-group',
				'items' => $options,
				//'cssClass' => 'postbox'
			);

			return apply_filters('wbcr_clr_seo_form_options', $formOptions);
		}
	}

	FactoryPages000::register($wbcr_update_services_plugin, 'WbcrUpm_UpdatesPage');
