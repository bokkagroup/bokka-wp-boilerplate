<?php
/* ----------------------------------------------------------------------------------
Register Styles & Scripts
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        wp_register_script(
            'bokkawptheme-common',
            get_stylesheet_directory_uri() . '/assets/build/js/common.js',
            array(),
            null,
            true
        );
        wp_register_script(
            'bokkawptheme-depend',
            get_stylesheet_directory_uri() . '/assets/build/js/depend.min.js',
            array('bokkawptheme-common'),
            null,
            true
        );
        wp_register_script(
            'bokkawptheme-initialize',
            get_stylesheet_directory_uri() . '/assets/build/js/initialize.min.js',
            array('bokkawptheme-common', 'bokkawptheme-depend'),
            null,
            true
        );

        wp_register_style(
            'bokkawptheme-child-styles',
            get_stylesheet_directory_uri() . '/assets/build/css/main.css',
            array("bokkawptheme-styles")
        );
    } // End if !is_admin()
}, 5);
/* ----------------------------------------------------------------------------------
Enqueue Styles & Scripts
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery', false, array(), false, false);
    wp_enqueue_script('bokkawptheme-initialize');
    wp_enqueue_style('bokkawptheme-child-styles');
}, 100);

/* ----------------------------------------------------------------------------------
Enqueue MUST USER Styles & Scripts (loads before other scripts)
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {
    if (!wp_script_is('bokkawptheme-common')) {
        wp_enqueue_script('bokkawptheme-common');
    }
    if (!wp_script_is('bokkawptheme-depend')) {
        wp_enqueue_script('bokkawptheme-depend');
    }
}, 25);
