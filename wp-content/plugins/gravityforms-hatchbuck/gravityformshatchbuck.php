<?php
/*
  Plugin Name: Gravity Forms Hatchbuck Add-On
  Plugin URI: http://bokkagroup.com
  Description: Integrates Gravity Forms with Hatchbuck. 
  Author: Jason Bahl, Dallas Johnson, Bokka Group
  Author URI: http://bokkagroup.com
  Version: 0.0.1
  Changelog: (see changelog.txt)
 */


define( 'GF_HATCHBUCK_VERSION', '0.0.1' );

add_action( 'gform_loaded', array( 'GF_Hatchbuck_Bootstrap', 'load' ), 5 );

class GF_Hatchbuck_Bootstrap {
	
	public static function load(){
		
		if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
			return;
		}

		require_once( 'includes/class-gf-hatchbuck.php' );

		GFAddOn::register( 'GFHatchbuck' );
		
	}
	
}

function gf_hatchbuck(){
	return GFHatchbuck::get_instance();
}