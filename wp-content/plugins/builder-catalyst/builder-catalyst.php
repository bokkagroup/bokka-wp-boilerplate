<?php
/**
 * @package Bokka_bdx
 * @version a-0.0.1
 */
/*
Plugin Name: Builder Catalyst
Plugin URI: http://bokka.com
Description: Builder Catalyst is used to provide preloaded Administration functions for the Bokka WP Suite of Tools
Author: Bokka Group
Version: a0.0.1
Author URI: http://bokkagroup.com
*/

namespace BokkaWP;
define("BGBuilderCatalyst_DIR", plugin_dir_path(__FILE__) );

/**
 * BokkaBuilder
 * @version 0.0.1 Singleton
 */
class BuilderCatalyst {

    private static $instance;



    public function __construct(){

        // create custom plugin settings menu
        add_action('admin_menu', array($this, 'create_menu_page') );
    }

    public function create_menu_page() {

        //create new top-level menu
        $this->menu_page = add_menu_page('Bokka BDX Settings', 'Builder Catalyst', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );
        add_action('load-'.$this->menu_page, array($this, 'load_layout_editor') );

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

$BG = BuilderCatalyst::get_instance();



