<?php
namespace GPLSCore\GPLS_PLUGIN_IBZOH\includes;

/**
 * Gutenberg Image Block Class.
 */
class ImageBlock {

	/**
	 * Singular Instance.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Plugin Info Array.
	 *
	 * @var array
	 */
	private static $plugin_info;

	/**
	 * Constructor.
	 *
	 * @param array $plugin_info
	 */
	private function __construct( $plugin_info ) {
		self::$plugin_info = $plugin_info;

		$this->hooks();
	}

	/**
	 * Initialize Func.
	 *
	 * @param array $plugin_info
	 * @return void
	 */
	public static function init( $plugin_info ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $plugin_info );
		}
	}

	/**
	 * Image Block Hooks.
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'enqueue_block_assets', array( $this, 'front_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'blocks_assets' ) );
		add_filter( 'render_block', array( $this, 'render_core_img' ), 100, 2 );
	}

	public function render_core_img( $block_content, $block ) {
		if ( 'core/image' === $block['blockName'] && ! empty( $block['attrs'] ) && ! empty( $block['attrs']['zoomOnHover'] ) && $block['attrs']['zoomOnHover'] && ! empty( $block['attrs']['id'] ) ) {
			$attachment_url = wp_get_attachment_url( $block['attrs']['id'] );
			$block_content  = str_replace( '<img', '<img data-zoomed="' . $attachment_url . '"', $block_content );
		}
		return $block_content;
	}

	/**
	 * Front Assets.
	 *
	 * @return void
	 */
	public function front_assets() {
		wp_enqueue_style( self::$plugin_info['name'] . '-even-zoom-css', self::$plugin_info['url'] . 'assets/dist/css/front/zoom-on-hover.min.css', array(), self::$plugin_info['version'], 'all' );
		wp_enqueue_script( self::$plugin_info['name'] . '-front-assets-actions', self::$plugin_info['url'] . 'assets/dist/js/front/actions.min.js', array( 'wp-blocks' ), self::$plugin_info['version'], true );
		wp_localize_script(
			self::$plugin_info['name'] . '-front-assets-actions',
			str_replace( '-', '_', self::$plugin_info['classes_prefix'] . '-localized-data' ),
			array(
				'zoomOnHoverClass'     => self::$plugin_info['classes_prefix'] . '-zoom-on-hover',
				'zoomOnHoverLensClass' => self::$plugin_info['classes_prefix'] . '-zoom-on-hover-lens',
			)
		);
	}

	/**
	 * Blocks Assets.
	 *
	 * @return void
	 */
	public function blocks_assets() {
		wp_enqueue_script( self::$plugin_info['name'] . '-admin-assets-actions', self::$plugin_info['url'] . 'assets/dist/js/admin/admin-actions.min.js', array( 'jquery' ), self::$plugin_info['version'], true );
		wp_localize_script(
			self::$plugin_info['name'] . '-admin-assets-actions',
			str_replace( '-', '_', self::$plugin_info['classes_prefix'] . '-localized-data' ),
			array(
				'prefix'           => self::$plugin_info['classes_prefix'],
				'zoomOnHoverClass' => self::$plugin_info['classes_prefix'] . '-zoom-on-hover',
				'labels'           => array(
					'zoomOnHoverLabel' => esc_html__( 'Zoom on hover', 'gpls-ibzoh-image-block-zoom-on-hover' ),
					'enable'           => esc_html__( 'Enable', 'gpls-ibzoh-image-block-zoom-on-hover' ),
					'help'             => esc_html__( 'Enable zoom on hover', 'gpls-ibzoh-image-block-zoom-on-hover' ),
				),
			)
		);
	}
}
