<?php

namespace BokkaWP\Theme\models;

class EventSingle extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->setImages($post, 946);
        $this->setEventStatus($post);
        $this->setStartTime($post);
        $this->setEndTime($post);
        $this->setDates($post);
        $this->setContent($post);
        $this->setShareShortcode($post);
        $this->setNeighborhood($post);

        $this->data = $post;
    }

    private function setDates($post)
    {
        $post->date = \getEventDate($post);
        $post->month = \getEventDate($post, 'M');
        $post->day = \getEventDate($post, 'j');
    }

    private function setStartTime($post)
    {
        $date_time = get_field('start_date_time', $post->ID);

        if ($date_time) {
            $post->start_time = date("g:ia", strtotime($date_time));
        }
    }

    private function setEndTime($post)
    {
        $date_time = get_field('end_date_time', $post->ID);

        if ($date_time) {
            $post->end_time = date("g:ia", strtotime($date_time));
        }
    }

    private function setEventStatus($post)
    {
        $post->has_past = hasEventPast($post);
    }

    private function setImages($post, $fallback_id)
    {
        $post->images = array(
            'responsive' => wp_get_attachment_image($post->image, 'full'),
            'full' => wp_get_attachment_image_src($post->image, 'full-brand-window')[0],
            'tablet' => wp_get_attachment_image_src($post->image, 'tablet-brand-window')[0],
            'mobile' => wp_get_attachment_image_src($post->image, 'mobile-brand-window')[0],
            'fallback-full' => wp_get_attachment_image_src($fallback_id, 'full-brand-window')[0],
            'fallback-tablet' => wp_get_attachment_image_src($fallback_id, 'tablet-brand-window')[0]
        );
    }

    private function setNeighborhood($post)
    {
        $neighborhood_id = get_field('neighborhood', $post->ID);

        if (isset($neighborhood_id[0])) {
            $neighborhood = get_post($neighborhood_id[0]);

            // Set product type(s)
            $types = get_post_meta($neighborhood->ID, 'types');
            if (count($types) > 0) {
                $neighborhood->types = $types;
            }

            // Set featured image
            $neighborhood = setNeighborhoodFeaturedImage($neighborhood);

            apply_filters('bokkamvc_filter_before_render', $neighborhood);
            $post->neighborhood = $neighborhood;
        }
    }

    private function setContent($post)
    {
        $post->post_content = apply_filters('the_content', $post->post_content);
    }

    private function setShareShortcode($post)
    {
        $post->share = do_shortcode('[addtoany]');
    }
}
