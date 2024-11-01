<?php

namespace WooCommerceVariationSwatches;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin.
 *
 * @since 1.0.0
 *
 * @package WooCommerceVariationSwatches
 */
final class Plugin extends ByteKit\Plugin {

	/**
	 * Plugin constructor.
	 *
	 * @param array $data The plugin data.
	 *
	 * @since 1.0.0
	 */
	protected function __construct( $data ) {
		parent::__construct( $data );
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}
	/**
	 * Define constants.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function define_constants() {
		define( 'WC_VARIATION_SWATCHES_VERSION', $this->get_version() );
		define( 'WC_VARIATION_SWATCHES_FILE', $this->get_file() );
		define( 'WC_VARIATION_SWATCHES_PATH', $this->get_dir_path() );
		define( 'WC_VARIATION_SWATCHES_URL', plugins_url( '', WC_VARIATION_SWATCHES_FILE ) );
		define( 'WC_VARIATION_SWATCHES_ASSETS_URL', $this->get_assets_url() );
		define( 'WC_VARIATION_SWATCHES_INCLUDES', WC_VARIATION_SWATCHES_PATH . '/includes' );
		define( 'WC_VARIATION_SWATCHES_TEMPLATES_DIR', WC_VARIATION_SWATCHES_PATH . '/templates' );
		define( 'WC_VARIATION_SWATCHES_ADMIN', WC_VARIATION_SWATCHES_PATH . '/includes/admin' );
	}

	/**
	 * Include required files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function includes() {
		require_once __DIR__ . '/functions.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'before_woocommerce_init', array( $this, 'on_before_woocommerce_init' ) );
		add_action( 'admin_notices', array( $this, 'dependencies_notices' ) );
		add_action( 'woocommerce_init', array( $this, 'init' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wc_variation_swatches_enqueue_scripts' ) );
	}

	/**
	 * Run on before WooCommerce init.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function on_before_woocommerce_init() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $this->get_file(), true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', $this->get_file(), true );
		}
	}

	/**
	 * Missing dependencies notice.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function dependencies_notices() {
		if ( $this->is_plugin_active( 'woocommerce' ) ) {
			return;
		}
		$notice = sprintf(
		/* translators: 1: plugin name 2: WooCommerce */
			__( '%1$s requires %2$s to be installed and active.', 'wc-variation-swatches' ),
			'<strong>' . esc_html( $this->get_name() ) . '</strong>',
			'<strong>' . esc_html__( 'WooCommerce', 'wc-variation-swatches' ) . '</strong>'
		);

		echo '<div class="notice notice-error"><p>' . wp_kses_post( $notice ) . '</p></div>';
	}

	/**
	 * Init the plugin after plugins_loaded so environment variables are set.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		$this->set( Products::class );
		// Admin Class.
		if ( is_admin() ) {
			$this->set( Admin\Admin::class );
			$this->set( Admin\Settings::class );
			$this->set( Admin\SettingsAPI::class );
			$this->set( Admin\Notices::class );
		}

		// Init action.
		do_action( 'wc_category_slider_pro_init' );
	}

	/**
	 * Loads all frontend scripts/styles
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wc_variation_swatches_enqueue_scripts() {
		wp_register_style( 'wc-variation-swatches', WC_VARIATION_SWATCHES_ASSETS_URL . 'css/frontend.css', array(), WC_VARIATION_SWATCHES_VERSION );
		wp_register_script( 'wc-variation-swatches', WC_VARIATION_SWATCHES_ASSETS_URL . 'js/frontend.js', array(), WC_VARIATION_SWATCHES_VERSION, true );

		wp_enqueue_style( 'wc-variation-swatches' );
		wp_enqueue_script( 'wc-variation-swatches' );

		wp_localize_script(
			'wc-variation-swatches',
			'wpwvs',
			array(
				'ajaxurl'             => admin_url( 'admin-ajax.php' ),
				'nonce'               => 'wc-variation-swatches',
				'attribute_behaviour' => wc_variation_swatches_get_settings( 'attribute_behaviour', 'with_cross', 'general_settings' ),
			)
		);
	}
}
