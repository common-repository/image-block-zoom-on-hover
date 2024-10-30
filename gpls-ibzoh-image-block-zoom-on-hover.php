<?php

namespace GPLSCore\GPLS_PLUGIN_IBZOH;

/**
 * Plugin Name:  Image Magnify Glass
 * Description:  A simple lightweight plugin that offers zooming when hovering over images from Gutenberg Image Block.
 * Author:       GrandPlugins
 * Author URI:   https://profiles.wordpress.org/grandplugins/
 * Text Domain:  gpls-ibzoh-image-block-zoom-on-hover
 * Domain Path:  /languages
 * Requires PHP: 5.6
 * Std Name:     gpls-ibzoh-image-block-zoom-on-hover
 * Version:      1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use GPLSCore\GPLS_PLUGIN_IBZOH\includes\ImageBlock;

if ( ! class_exists( __NAMESPACE__ . '\GPLS_IBZOH_Image_Block_Zoom_On_Hover' ) ) :

	/**
	 * Exporter Main Class.
	 */
	class GPLS_IBZOH_Image_Block_Zoom_On_Hover {

		/**
		 * Single Instance
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Plugin Info
		 *
		 * @var array
		 */
		private static $plugin_info;

		/**
		 * Singular init Function.
		 *
		 * @return self
		 */
		public static function init() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			self::setup_plugin_info();
			$this->load_languages();
			$this->includes();

			ImageBlock::init( self::$plugin_info );
		}

		/**
		 * Disable Duplicate Free/Pro.
		 *
		 * @return void
		 */
		private static function disable_duplicate() {
			if ( ! empty( self::$plugin_info['duplicate_base'] ) && self::is_plugin_active( self::$plugin_info['duplicate_base'] ) ) {
				deactivate_plugins( self::$plugin_info['duplicate_base'] );
			}
		}

		/**
		 * Plugin Activated Hook.
		 *
		 * @return void
		 */
		public static function plugin_activated() {
			self::setup_plugin_info();
			self::includes();
			self::disable_duplicate();
		}

		/**
		 * Includes Files
		 *
		 * @return void
		 */
		private static function includes() {
			require_once trailingslashit( plugin_dir_path( __FILE__ ) ) . 'vendor/autoload.php';
		}

		/**
		 * Load languages Folder.
		 *
		 * @return void
		 */
		public function load_languages() {
			load_plugin_textdomain( self::$plugin_info['text_domain'], false, self::$plugin_info['path'] . 'languages/' );
		}

		/**
		 * Set Plugin Info
		 *
		 * @return void
		 */
		public static function setup_plugin_info() {
			$plugin_data = get_file_data(
				__FILE__,
				array(
					'Version'     => 'Version',
					'Name'        => 'Plugin Name',
					'URI'         => 'Plugin URI',
					'SName'       => 'Std Name',
					'text_domain' => 'Text Domain',
				),
				false
			);

			self::$plugin_info = array(
				'id'             => 1453,
				'basename'       => plugin_basename( __FILE__ ),
				'version'        => $plugin_data['Version'],
				'name'           => $plugin_data['SName'],
				'text_domain'    => $plugin_data['text_domain'],
				'file'           => __FILE__,
				'plugin_url'     => $plugin_data['URI'],
				'public_name'    => $plugin_data['Name'],
				'path'           => trailingslashit( plugin_dir_path( __FILE__ ) ),
				'url'            => trailingslashit( plugin_dir_url( __FILE__ ) ),
				'options_page'   => $plugin_data['SName'],
				'localize_var'   => str_replace( '-', '_', $plugin_data['SName'] ) . '_localize_data',
				'type'           => 'free',
				'classes_prefix' => 'gpls-ibzoh',
				'prefix'         => 'gpls-ibzoh',
				'prefix_under'   => 'gpls_ibzoh',
				'duplicate_base' => 'gpls-ibzoh-image-block-zoom-on-hover/gpls-ibzoh-image-block-zoom-on-hover.php',
			);
		}

		/**
		 * Is Plugin Active.
		 *
		 * @param string $plugin_basename
		 * @return boolean
		 */
		private static function is_plugin_active( $plugin_basename ) {
			require_once \ABSPATH . 'wp-admin/includes/plugin.php';
			return is_plugin_active( $plugin_basename );
		}

	}

	add_action( 'plugins_loaded', array( __NAMESPACE__ . '\GPLS_IBZOH_Image_Block_Zoom_On_Hover', 'init' ), 10 );
	register_activation_hook( __FILE__, array( __NAMESPACE__ . '\GPLS_IBZOH_Image_Block_Zoom_On_Hover', 'plugin_activated' ) );
endif;
