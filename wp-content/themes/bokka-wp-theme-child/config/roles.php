<?php

/**
 * release-0.0.19 added custom bokka-admin user role.
 * release-0.0.20 removing custom bokka-admin user role.
 *     User roles are stored in the database - this function can be removed
 *     in release-0.0.21
 *
 * @link https://codex.wordpress.org/Function_Reference/add_role
 * @link https://codex.wordpress.org/Function_Reference/remove_role
 */

function bokka_custom_user_roles()
{
    if (get_role('bokka-admin')) {
        remove_role('bokka-admin');
    }
}

bokka_custom_user_roles();
