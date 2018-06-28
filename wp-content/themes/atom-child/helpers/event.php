<?php

/**
 * Get formatted date from event start date
 * @param  obj       $post      WP_Post
 * @param  string    $format    PHP date format
 * @return string               Visually formatted date
 */
function getEventDate($id, $format = 'l, M j, Y')
{
    $date_time = get_field('start_date_time', $id);
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
function hasEventPast($id)
{
    $end_date_time = get_field('end_date_time', $id);
    $date = new \DateTime($end_date_time);
    $end_date = $date->format('Y-m-d');
    $now = date('Y-m-d');

    if ($end_date < $now) {
        return true;
    }

    return false;
}
