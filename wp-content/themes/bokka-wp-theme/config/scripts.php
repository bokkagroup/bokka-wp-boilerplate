<?php

/* ----------------------------------------------------------------------------------
Register Styles & Scripts
---------------------------------------------------------------------------------- */
//using callback style so both child & parent themes
//load their respective files without function name conflicts
add_action('wp_enqueue_scripts', function(){
    if (!is_admin()) {
        wp_register_script(
            //change for child theme
            'bokkawptheme-common',
            //change to get_stylesheet_directory_uri() for child theme
            get_template_directory_uri() . '/assets/build/js/common.js',
            array(),
            null,
            true
        );
        wp_register_script(
            'bokkawptheme-depend',
            get_template_directory_uri() . '/assets/build/js/depend.min.js',
            array('bokkawptheme-common'),
            null,
            true
        );
        wp_register_script(
            'bokkawptheme-initialize',
            get_template_directory_uri() . '/assets/build/js/initialize.min.js',
            array('bokkawptheme-common', 'bokkawptheme-depend'),
            null,
            true
        );
        wp_register_style(
            'bokkawptheme-styles',
            get_template_directory_uri() . '/assets/build/css/main.css'
        );
    } // End if !is_admin()
}, 5);

/* ----------------------------------------------------------------------------------
Enqueue custom Styles & Scripts
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('jquery', false, array(), false, false);
    wp_enqueue_script('bokkawptheme-initialize');
    wp_enqueue_style('bokkawptheme-styles');
}, 100);

/* ----------------------------------------------------------------------------------
Enqueue Parent Styles & Scripts
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function() {
    //wp_enqueue_script('bokkawptheme-initialize');
}, 50);

/* ----------------------------------------------------------------------------------
Enqueue MUST USE Styles & Scripts (loads before other scripts)
---------------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function() {
    // prefer the child theme's common file
    // as to not load multiple copies of backbone & lodash
    if (!wp_script_is('bokkawptheme-common')) {
        wp_enqueue_script('bokkawptheme-common');
    }
    if (!wp_script_is('bokkawptheme-depend')) {
        wp_enqueue_script('bokkawptheme-depend');
    }
}, 25);
