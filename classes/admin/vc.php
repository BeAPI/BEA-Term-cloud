<?php class BEA_TC_VC_Admin_Main {

	static $component_name;

	function __construct() {
		self::$component_name = __( 'BEA - Term Cloud', 'bea-term-cloud' );

		// Add the component & his js templates
		add_action( 'admin_footer', array( __CLASS__, 'js_templates' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		// Add component scripts / assets
		add_action( 'admin_init', array( __CLASS__, 'register_assets' ) );
		add_action( 'admin_head', array( __CLASS__, 'enqueue_assets' ) );

		// Register term cloud vc component
		add_action( 'init', array( __CLASS__, 'register_vc_component' ), 20 );
	}

	/**
	 * Add js templates
	 *
	 * @author Maxime CULEA
	 */
	public static function js_templates() {
		include( BEA_VC_Helper::get_component_template_path( 'admin-js-templates' ) );
	}

	/**
	 * Ad the visual composer param
	 *
	 * @author Maxime CULEA
	 */
	public static function admin_init() {
		if ( function_exists( 'add_shortcode_param' ) ) {
			add_shortcode_param( 'bea-taxonomies', array(
				__CLASS__,
				'content_callback'
			), BEA_TERM_CLOUD_URL . 'assets/js/component.js' );
		}
	}

	/**
	 * Display the shortcode param UI
	 *
	 * @param $settings
	 * @param $value
	 *
	 * @return string
	 * @author Maxime CULEA
	 */
	public static function content_callback( $settings, $value ) {
		$dependency = vc_generate_dependencies_attributes( $settings );

		$taxonomies = isset( $settings['taxonomies'] ) ? array_map( 'get_taxonomy', $settings['taxonomies'] ) : get_taxonomies( array(), 'objects' );

		// Add vars for the field
		$vars = array(
			'taxonomies' => array_filter( $taxonomies ),
			'taxonomy'   => __( 'Taxonomy', 'bea-term-cloud' ),
		);

		ob_start();
		include( BEA_VC_Helper::get_component_template_path( 'admin-component' ) );
		return ob_get_clean();
	}

	/**
	 * Register assets
	 *
	 * @author Maxime CULEA
	 */
	public static function register_assets() {
		// Styles
		wp_register_style( 'select2', BEA_TERM_CLOUD_URL . 'assets/js/lib/select2/select2.css' );
		wp_register_style( 'bea-tp-param', BEA_TERM_CLOUD_URL . 'assets/css/admin.css', array( 'select2' ), BEA_TERM_CLOUD_VERSION );

		// Scripts
		wp_register_script( 'select2', BEA_TERM_CLOUD_URL . 'assets/js/lib/select2/select2.min.js', array( 'jquery' ), true );
	}

	/**
	 * Enqueue assets
	 *
	 * @author Maxime CULEA
	 */
	public static function enqueue_assets() {
		if ( ! isset( $_GET['vc_action'] ) ) {
			return;
		}

		wp_enqueue_style( 'bea-tp-param' );
		wp_enqueue_script( 'select2' );
	}

	/**
	 * Register the VC component
	 *
	 * @author Maxime CULEA
	 */
	public static function register_vc_component() {
		if ( ! function_exists( 'vc_map' ) ) {
			return false;
		}

		vc_map( array(
			"name"     => self::$component_name,
			"base"     => BEA_TC_VC_Main::shortcode_tag,
			"class"    => '',
			"category" => __( 'BeAPI Widgets', 'bea-term-cloud' ),
			"icon"     => "icon-wpb-" . BEA_TC_VC_Main::shortcode_tag,
			"params"   => array(
				array(
					'type'       => 'textarea',
					'heading'    => __( 'Text before', 'bea-term-cloud' ),
					'param_name' => 'text_before',
					'value'      => ''
				),
				array(
					'type'       => 'dropdown',
					'heading'    => __( 'Hide empty terms ?', 'bea-term-cloud' ),
					'param_name' => 'hide_empty',
					'value'      => array(
						'true'  => __( 'True', 'bea-term-cloud' ),
						'false' => __( 'False', 'bea-term-cloud' ),
					),
				),
				array(
					'type'       => 'bea-taxonomies',
					'heading'    => __( 'Taxonomy', 'bea-term-cloud' ),
					'param_name' => 'contents',
				),
				array(
					'type'       => 'textarea',
					'heading'    => __( 'Text after', 'bea-term-cloud' ),
					'param_name' => 'text_after',
					'value'      => ''
				)
			),
		) );
	}
}