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

function limit_words($text, $limit)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]);
        $text = trim($text);
    }
    return $text;
}
