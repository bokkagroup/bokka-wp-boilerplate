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
        <meta name="viewport" content="width=device-width,  initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/build/images/favicon.png">
		<title><?php wp_title('|', true, 'right'); ?></title>
		<?php do_action('wp_head'); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="site-wrapper" class="site-wrapper">
