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

        <?php //local reloading
        if (BOKKA_ENV === "local") {
            echo '<script src="http://localhost:35729/livereload.js?snipver=1"></script>';
        } ?>

        <?php do_action('wp_head'); ?>

        <!-- BugHerd feedback -->
        <script type='text/javascript'>
        (function (d, t) {
          var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
          bh.type = 'text/javascript';
          bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=cxvx4zwdaisujxj5behrng';
          s.parentNode.insertBefore(bh, s);
          })(document, 'script');
        </script>
        <!-- BugHerd feedback -->
	</head>
	<body <?php body_class(); ?>>
		<div id="site-wrapper" class="site-wrapper">
