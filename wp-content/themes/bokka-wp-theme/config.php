<?php

/**
 * Define theme location
 */
define('BOKKA_THEME_DIR', get_bloginfo('template_directory'));

/**
 * Defines Environment Variables for theme
 */
if (isset($_SERVER) && $_SERVER['HTTP_HOST']) {
    $host = $_SERVER['HTTP_HOST'];
    if (strpos($host, '.local') !== false) {
        define('BOKKA_ENV', "local");
    } elseif (strpos($host, '.com') !== false) {
        define('BOKKA_ENV', "staging");
    } else {
        define('BOKKA_ENV', "production");
    }
}
