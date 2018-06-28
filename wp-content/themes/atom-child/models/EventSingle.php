<?php

namespace CatalystWP\AtomChild\models;

class EventSingle extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        $this->setImages(946);
        $this->setEventStatus();
        $this->setStartTime();
        $this->setEndTime();
        $this->setDates();
        $this->setContent();
        $this->setShareShortcode();
        $this->setNeighborhood();
        $this->data = $this;
    }

    private function setDates()
    {
        $this->date = \getEventDate($this->ID);
        $this->month = \getEventDate($this->ID, 'M');
        $this->day = \getEventDate($this->ID, 'j');
    }

    private function setStartTime()
    {
        $date_time = get_field('start_date_time', $this->ID);

        if ($date_time) {
            $this->start_time = date("g:ia", strtotime($date_time));
        }
    }

    private function setEndTime()
    {
        $date_time = get_field('end_date_time', $this->ID);

        if ($date_time) {
            $this->end_time = date("g:ia", strtotime($date_time));
        }
    }

    private function setEventStatus()
    {
        $this->has_past = hasEventPast($this->ID);
    }

    private function setImages($fallback_id)
    {
        $this->images = array(
            'responsive' => wp_get_attachment_image($this->image, 'full'),
            'full' => wp_get_attachment_image_src($this->image, 'full-brand-window')[0],
            'tablet' => wp_get_attachment_image_src($this->image, 'tablet-brand-window')[0],
            'mobile' => wp_get_attachment_image_src($this->image, 'mobile-brand-window')[0],
            'fallback-full' => wp_get_attachment_image_src($fallback_id, 'full-brand-window')[0],
            'fallback-tablet' => wp_get_attachment_image_src($fallback_id, 'tablet-brand-window')[0]
        );
    }

    private function setNeighborhood()
    {
        $neighborhood_id = get_field('neighborhood', $this->ID);

        if (isset($neighborhood_id[0])) {
            $neighborhood = get_post($neighborhood_id[0]);

            // Set product type(s)
            $types = get_post_meta($neighborhood->ID, 'types');
            if (count($types) > 0) {
                $neighborhood->types = $types;
            }

            // Set featured image
            $neighborhood = setFeaturedImage($neighborhood);

            apply_filters('catatlystwp_nucleus_filter_before_render', $neighborhood);
            $this->neighborhood = $neighborhood;
        }
    }

    private function setContent()
    {
        $this->post_content = apply_filters('the_content', $this->post_content);
    }

    private function setShareShortcode()
    {
        $this->share = do_shortcode('[addtoany]');
    }
}
