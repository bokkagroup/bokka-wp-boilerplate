<?php

namespace BokkaWP\BDX;

class Admin {
    public function __construct(){

        // create custom plugin settings menu
        add_action('admin_menu', array($this, 'create_menu_page') );
    }

    public function create_menu_page() {

        //create new top-level menu
        $this->menu_page = add_menu_page('Bokka BDX Settings', 'Bokka BDX', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );
        add_action('load-'.$this->menu_page, array($this, 'load_layout_editor') );

    }
}