<?php

if ( ! class_exists( 'KcSeoInit' ) ):

	class KcSeoInit {
		private $version;

		function __construct() {
			$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : KCSEO_VERSION;
			add_action( 'init', array( $this, 'kcSeoScript' ) );
			add_action( 'admin_menu', array( $this, 'kcSeo_Wp_Schema_menu' ) );
			add_action( 'plugins_loaded', array( $this, 'kcSeo_pluginInit' ) );
			add_action( 'wp_ajax_kcSeoWpSchemaSettings', array( $this, 'kcSeoWpSchemaSettings' ) );
			add_action( 'wp_ajax_kcSeoMainSettings_action', array( $this, 'kcSeoMainSettings_action' ) );
			add_action( 'wp_ajax_newSocial', array( $this, 'newSocial' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// for MU Site
			add_action( 'activated_plugin', array( $this, 'update_queue' ), 10, 2 );
			add_action( 'deactivated_plugin', array( $this, 'update_queue' ), 10, 2 );

			register_activation_hook( KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'activePlugin' ) );
//	        register_deactivation_hook(KCSEO_WP_SCHEMA_PLUGIN_ACTIVE_FILE_NAME, array($this, 'uninstall'));
			// Uninstall hook

		}

		function update_queue( $plugin, $network_wide = null ) {
			if ( ! $network_wide ) {
				return;
			}
			list( $action ) = explode( '_', current_filter(), 2 );

			$action = str_replace( 'activated', 'activate', $action );
			$queue  = get_site_option( "network_{$action}_queue", array() );

			$queue[ $plugin ] = ( has_filter( $action . '_' . $plugin ) || has_filter( $action . '_plugin' ) );
			update_site_option( "network_{$action}_queue", $queue );
		}

		function admin_enqueue_scripts() {
			global $pagenow;
			// validate page
			$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : null;
			
			if ( ( $pagenow == 'admin.php' && ( $page == 'wp-seo-schema' || $page == 'wp-seo-schema-settings' ) ) || $pagenow == 'term.php' ) { 

				// scripts
				wp_enqueue_media();
				wp_enqueue_script( array(
					'jquery',
					'kcseo-select2-js',
					'kcseo-tooltip-js',
					'kcseo-admin-js',
				) );

				// styles
				wp_enqueue_style( array(
					'kcseo-select2-css',
					'kcseo-tooltip-css',
					'kcseo-admin-css',
				) );

				if ( $pagenow == 'term.php' ) { 
					$custom_css = ".kc-top-toolbar { display: none; }";
					wp_add_inline_style( 'kcseo-admin-css', $custom_css );
				}
			}
		}

		function kcSeoScript() {
			global $KcSeoWPSchema;
			// register team scripts and styles
			$scripts = array();
			$styles  = array();
			if ( is_admin() ) {


				$scripts[] = array(
					'handle' => 'kcseo-select2-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/select2.min.js',
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$scripts[] = array(
					'handle' => 'kcseo-tooltip-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/jquery.qtip.js',
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$scripts[] = array(
					'handle' => 'kcseo-admin-js',
					'src'    => $KcSeoWPSchema->assetsUrl . 'js/admin.js',
					'deps'   => array( 'jquery' ),
					'footer' => true
				);

				$styles['kcseo-select2-css'] = $KcSeoWPSchema->assetsUrl . 'css/select2.min.css';
				$styles['kcseo-tooltip-css'] = $KcSeoWPSchema->assetsUrl . 'css/jquery.qtip.css';
				$styles['kcseo-admin-css']   = $KcSeoWPSchema->assetsUrl . 'css/admin.css';
			}
			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->version, $script['footer'] );
			}
			foreach ( $styles as $k => $v ) {
				wp_register_style( $k, $v, false, $this->version );
			}
		}

		function newSocial() {
			$schemaModel = new KcSeoSchemaModel;
			$id          = ( $_REQUEST['id'] ? $_REQUEST['id'] + 1 : 0 );
			$html        = null;
			$html        = "<div class='sfield'>";
			$html        .= "<select name='social[$id][id]'>";
			foreach ( KcSeoOptions::getSocialList() as $skey => $social ) {
				$html .= "<option value='$skey'>$social</option>";
			}
			$html .= "</select>";
			$html .= "<input type='text' name='social[$id][link]'>";
			$html .= '<span class="dashicons dashicons-trash social-remove"></span>';
			$html .= "</div>";


			wp_send_json( array( 'data' => $html ) );
			die();
		}

		function kcSeoWpSchemaSettings() {
			global $KcSeoWPSchema;
			$error = true;
			$msg   = null;
			if ( $KcSeoWPSchema->verifyNonce() ) {
				unset( $_REQUEST['action'] );
				update_option( $KcSeoWPSchema->options['settings'], $_REQUEST );
				$error = false;
				$msg   = __( 'Settings successfully updated', "wp-seo-structured-data-schema-pro" );
			} else {
				$msg = __( 'Session is expired !!', "wp-seo-structured-data-schema-pro" );
			}
			$response = array(
				'error' => $error,
				'msg'   => $msg
			);
			wp_send_json( $response );
			die();
		}

		function kcSeoMainSettings_action() {
			global $KcSeoWPSchema;
			$error = true;
			$msg   = null;
			if ( $KcSeoWPSchema->verifyNonce() ) {
				unset( $_REQUEST['action'] );
				unset( $_REQUEST['_kcseo_nonce'] );
				unset( $_REQUEST['_wp_http_referer'] );
				update_option( $KcSeoWPSchema->options['main_settings'], $_REQUEST );
				$error = false;
				$msg   = __( 'Settings successfully updated', "wp-seo-structured-data-schema-pro" );
			} else {
				$msg = __( 'Session is expired !!', "wp-seo-structured-data-schema-pro" );
			}
			$response = array(
				'error' => $error,
				'msg'   => $msg
			);
			wp_send_json( $response );
			die();
		}

		function wp_schema_page() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->render( 'schema-options' );
		}

		function wp_schema_setting_page() {
			global $KcSeoWPSchema;
			$KcSeoWPSchema->render( 'settings' );
		}

		function kcSeo_Wp_Schema_menu() {
			global $KcSeoWPSchema;
			add_menu_page( __( 'WP SEO Structured Data Schema', "wp-seo-structured-data-schema-pro" ), __( 'WP SEO Schema', "wp-seo-structured-data-schema-pro" ), 'manage_options', 'wp-seo-schema',
				array( $this, 'wp_schema_page' ), $KcSeoWPSchema->assetsUrl . 'images/wp-seo-schema.png' );
			add_submenu_page( 'wp-seo-schema', __( 'WP SEO Schema settings', "wp-seo-structured-data-schema-pro" ), __( 'Settings', "wp-seo-structured-data-schema-pro" ), 'manage_options', 'wp-seo-schema-settings',
				array( $this, 'wp_schema_setting_page' ) );
		}


		function kcSeo_pluginInit() {
			load_plugin_textdomain( "wp-seo-structured-data-schema-pro", false, KCSEO_WP_SCHEMA_LANGUAGE_PATH );
			$this->updateVariableAndFixIssue();
		}

		function activePlugin() {
			$this->updateVariableAndFixIssue();
		}

		function updateVariableAndFixIssue() {
			global $KcSeoWPSchema;
			$this->fix1_2_9_to_1_2_10();
			update_option( $KcSeoWPSchema->options['installed_version'], $KcSeoWPSchema->options['version'] );
		}

		function fix1_2_9_to_1_2_10() {
			global $KcSeoWPSchema;
			$installed_version = get_option( $KcSeoWPSchema->options['installed_version'] );

			if ( version_compare( $installed_version, '1.2.9', '<=' ) ) {
				$settings = get_option( $KcSeoWPSchema->options['settings'] );
				if ( isset( $settings['contact']['contactType'] ) ) {
					$settings['contact']['contactType'] = strtolower( $settings['contact']['contactType'] );
					update_option( $KcSeoWPSchema->options['settings'], $settings );
				}
				if ( isset( $settings['area_served'] ) && ! empty( $settings['area_served'] ) ) {

					$cList = KcSeoOptions::getCountryList();
					$areas = array();
					foreach ( $settings['area_served'] as $area ) {
						$key = array_search( $area, $cList );
						if ( $key ) {
							$areas[] = $key;
						}
					}
					if ( ! empty( $areas ) ) {
						$settings['area_served'] = $areas;
						update_option( $KcSeoWPSchema->options['settings'], $settings );
					}
				}

			}
		}

	}
endif;