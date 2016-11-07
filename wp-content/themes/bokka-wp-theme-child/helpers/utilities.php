<?php

function get_id_by_slug($page_slug)
{
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

function get_relative_permalink($post_id)
{
    $permalink = get_permalink($post_id);
    if ($permalink) {
        return str_replace(home_url(), "", $permalink);
    } else {
        return null;
    }
}
