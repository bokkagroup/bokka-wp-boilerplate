<?php
/**
 * The head for our theme.
 *
 * This is the template that opens and specified our HTML document providing markup up to <div id="site-wrapper">
 *
 * @package bokka_wp_theme
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/build/images/favicon.png">
            <?php do_action('wp_head'); ?>
    </head>
    <body <?php body_class(); ?>>
        <div id="site-wrapper" class="site-wrapper">
