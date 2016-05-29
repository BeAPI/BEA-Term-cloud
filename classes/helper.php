<?php class BEA_VC_Helper {
	/**
	 * Get templathe path
	 *
	 * @param string $tpl
	 *
	 * @author Maxime CULEA
	 *
	 * @return string
	 */
	public static function get_component_template_path( $tpl = '' ) {
		if ( empty( $tpl ) ) {
			return '';
		}

		// Display the form, allow take template from child or parent theme
		if ( is_file( STYLESHEETPATH . '/views/vc/' . $tpl . '.tpl.php' ) ) {// Use custom template from child theme
			return ( STYLESHEETPATH . '/views/vc/' . $tpl . '.tpl.php' );
		} elseif ( is_file( TEMPLATEPATH . '/views/vc/' . $tpl . '.tpl.php' ) ) {// Use custom template from parent theme
			return ( TEMPLATEPATH . '/views/vc/' . $tpl . '.tpl.php' );
		} elseif ( is_file( BEA_TERM_CLOUD_DIR . 'views/' . $tpl . '.tpl.php' ) ) {// Use builtin template
			return ( BEA_TERM_CLOUD_DIR . 'views/' . $tpl . '.tpl.php' );
		}

		return false;
	}

	/**
	 * Display the term link or the single related content
	 * @author Maxime CULEA
	 */
	public static function get_term_link( $term_object ) {
		var_dump( $term_object ); die;
		if( ! isset( $term_object_or_id[''] ) ) {
		}
	}
}