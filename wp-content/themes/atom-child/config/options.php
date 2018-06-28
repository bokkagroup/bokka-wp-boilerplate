<?php

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title'    => 'Hatchbuck Settings',
        'menu_title'    => 'Hatchbuck',
        'menu_slug'     => 'hatchbuck-settings',
        'capability'    => 'activate_plugins',
        'redirect'      => false
    ));
}
