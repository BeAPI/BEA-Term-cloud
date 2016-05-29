<?php class BEA_TC_VC_Main {

	const shortcode_tag = 'bea-vc-term-cloud';

	function __construct() {
		// init the shortcode
		add_action( 'init', array( __CLASS__, 'init_shortcode' ) );
	}

	/**
	 * Init the shortcode tag
	 *
	 * @author Maxime CULEA
	 */
	public static function init_shortcode() {
		add_shortcode( self::shortcode_tag, array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * Convert widget into shortcode
	 *
	 * @author Maxime CULEA
	 */
	public static function shortcode( $atts = array() ) {
		$atts = shortcode_atts( array(
			'text_before' => '',
			'contents'    => '',
			'hide_empty'  => 'true',
			'text_after'  => '',
		), $atts );

		$atts['text_before'] = esc_html( $atts['text_before'] );
		$atts['text_after']  = esc_html( $atts['text_after'] );
		$atts['hide_empty']  = boolval( $atts['hide_empty'] );
		$atts['contents']    = json_decode( base64_decode( $atts['contents'] ) );

		if ( empty( $atts['contents'] ) ) {
			return false;
		}

		// Get the data
		$contents = new get_terms( $atts['contents']->taxonomies, array( 'hide_empty' => $atts['hide_empty'] ) );
		if( is_wp_error( $contents ) ) {
			return false;
		}

		// Load the slider type template
		$tpl = BEA_VC_Helper::get_component_template_path( self::shortcode_tag );
		if ( empty( $tpl ) ) {
			return false;
		}

		//include template and display it
		ob_start();
		include( $tpl );
		return ob_get_clean();
	}
}