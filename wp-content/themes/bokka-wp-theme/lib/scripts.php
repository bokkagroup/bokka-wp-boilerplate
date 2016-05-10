<?php

/* ----------------------------------------------------------------------------------
Register Styles & Scripts
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', 'catalyst_register_scripts', 100);
function catalyst_register_scripts() {

    if (!is_admin()) {

        wp_register_script(
            'theme-common',
            get_stylesheet_directory_uri() . '/assets/build/js/common.js',
            array(),
            null,
            true
        );

        wp_register_script(
            'initialize',
            get_stylesheet_directory_uri() . '/assets/build/js/initialize.min.js',
            array('common'),
            null,
            true
        );

        wp_register_style(
            'theme',
            get_stylesheet_directory_uri() . '/assets/build/css/main.css'
        );



    } // End if !is_admin()

}

/* ----------------------------------------------------------------------------------
Enqueue Styles & Scripts
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts','catalyst_enqueue_scripts', 100);
function catalyst_enqueue_scripts()
{
    wp_enqueue_script('theme-common');
    wp_enqueue_script('initialize');
    wp_enqueue_style('theme');
}