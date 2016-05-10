<?php
/**
 * @package Bokka_bdx
 * @version a-0.0.1
 */
/*
Plugin Name: Bokka NHF
Plugin URI: http://bokka.com
Description: Bokka NHF
Author: Bokka Group
Version: a0.0.1
Author URI: http://bokkagroup.com
*/

namespace BokkaWP;
define("BGNHF_DIR", plugin_dir_path(__FILE__) );

/**
 * BokkaBuilder
 * @version 0.0.1 Singleton
 */
class NHF {

    private static $instance;



    public function __construct(){

        $upload_dir = wp_upload_dir();

    }

    /**
     * Singleton instantiation
     * @return [static] instance
     */
    public static function get_instance(){
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}

$BGNHF = NHF::get_instance();

register_activation_hook( __FILE__, 'bgwpnhf_install' );