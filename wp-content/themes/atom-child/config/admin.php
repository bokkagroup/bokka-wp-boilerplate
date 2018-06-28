<?php

/**
 * Hide content editor on all pages
 */

function admin_hide_editor()
{
    global $post;

    if (!isset($post->ID)) {
        return;
    }

    $post_type = get_post_type($post->ID);

    if ($post_type == 'page') {
        remove_post_type_support('page', 'editor');
    }
}

add_action('admin_head', 'admin_hide_editor');
