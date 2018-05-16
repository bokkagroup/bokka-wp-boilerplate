<?php

/**
 * Get formatted date from event start date
 * @param  obj       $post      WP_Post
 * @param  string    $format    PHP date format
 * @return string               Visually formatted date
 */
function getEventDate($post, $format = 'l, M j, Y')
{
    $date_time = get_field('start_date_time', $post->ID);
    $date = explode(' ', $date_time);
    if ($date_time && isset($date[0])) {
        return date($format, strtotime($date[0]));
    }
}

/**
 * Check if event end date is in the past
 * @param  obj  $post   WP_Post
 * @return boolean
 */
function hasEventPast($post)
{
    $end_date_time = get_field('end_date_time', $post->ID);
    $date = new \DateTime($end_date_time);
    $end_date = $date->format('Y-m-d');
    $now = date('Y-m-d');

    if ($end_date < $now) {
        return true;
    }

    return false;
}
