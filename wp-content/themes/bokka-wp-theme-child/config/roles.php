<?php

/*
 * Add custom "Bokka Administrator" user role
 *
 * @link https://codex.wordpress.org/Function_Reference/add_role
 */

function bokka_custom_user_roles()
{
    global $wp_roles;

    if (!isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }

    $admin = $wp_roles->get_role('administrator');

    // Create new Bokka Admin role with full administrator capabilities
    $wp_roles->add_role('bokka-admin', 'Bokka Administrator', $admin->capabilities);
}

bokka_custom_user_roles();
