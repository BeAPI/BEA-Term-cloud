<?php
/*
 Plugin Name: BEA - Term Cloud
 Version: 1.0.0
 Plugin URI: http://www.beapi.fr
 Description: Allow in visual composer and widgets (to be done) to display a beautiful term cloud.
 Tags: tag, tags, terms, term, taxonomy, taxonomies, visual composer
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: bea-term-cloud
 
 ----
 
 Copyright 2016 BE API Technical team (human@beapi.fr)
 
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'BEA_TERM_CLOUD_VERSION', '1.0.0' );
define( 'BEA_TERM_CLOUD_NAME', 'vc-term-cloud' );

// Plugin URL and PATH
define( 'BEA_TERM_CLOUD_URL', plugin_dir_url( __FILE__ ) );
define( 'BEA_TERM_CLOUD_DIR', plugin_dir_path( __FILE__ ) );

// Function for easy load files
function _bea_term_cloud_load_files( $dir, $files, $prefix = '' ) {
	foreach ( $files as $file ) {
		if ( is_file( $dir . $prefix . $file . ".php" ) ) {
			require_once( $dir . $prefix . $file . ".php" );
		}
	}
}

_bea_term_cloud_load_files( BEA_TERM_CLOUD_DIR . 'classes/', array( 'helper', 'vc' ) );

if ( is_admin() ) {
	_bea_term_cloud_load_files( BEA_TERM_CLOUD_DIR . 'classes/admin/', array( 'vc' ) );
}

add_action( 'plugins_loaded', 'init_bea_term_cloud_plugin' );
function init_bea_term_cloud_plugin() {

	/** Load classes only if VC is activated */
	if( defined( 'WPB_VC_VERSION' ) ) {
		new BEA_TC_VC_Main();
		if ( is_admin() ) {
			new BEA_TC_VC_Admin_Main();
		}
	}

}
