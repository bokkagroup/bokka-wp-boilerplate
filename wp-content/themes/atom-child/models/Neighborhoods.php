<?php

namespace CatalystWP\AtomChild\models;

class Neighborhoods extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        global $post;
        $this->setMap($post, 14);
        $this->setFeaturedImg($post, 'large');
        $this->setAltContent($post);
        $this->setProduct($post);
        $this->setSitemap($post);
        $this->setTypes($post);
        $this->setTestimonial($post);
        $this->setNeighborhoodFeatures($post);
        $this->setGalleryItems($post);
        $this->setForm($post, 6);
        $this->setModalGalleryForm($post, 37);
        $this->setUpcomingEvent($post);
        $this->setStatus($post);
        $this->setFormCTAText($post);
        $this->data = $post;
    }

    private function setMap($post, $zoom)
    {
        $sales_team = getSalesTeamMembers($post->ID);
        $post->map = array(
            'address_1' => $post->address_1,
            'address_2' => $post->address_2,
            'city'      => $post->city,
            'state'     => $post->state,
            'zip'       => $post->zip,
            'hours'     => nl2br($post->hours),
            'phone'     => $post->phone,
            'latitude'  => $post->latitude,
            'longitude' => $post->longitude,
            'zoom'      => $zoom,
            'sale_team_members' => $sales_team,
            'legal' => get_field('legal', $post->ID)
        );
    }

    private function setFeaturedImg($post, $size)
    {
        $post->featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID), $size);
    }

    private function setAltContent($post)
    {
        $post->alternating_content = array('items' => get_field('alternating_content'));
    }

    private function setProduct($post)
    {
        $post->product = tabbedProductData($post->ID);
    }

    private function setSitemap($post)
    {
        $post->site_map_thumbnail = wp_get_attachment_image_src($post->site_map_thumbnail, 'thumbnail')[0];
        $post->site_map_pdf = wp_get_attachment_url($post->site_map_pdf);
    }

    private function setTypes($post)
    {
        if ($types = get_post_meta($post->ID, 'types')) {
            $post->types = explode(',', $types[0]);
        }
    }

    private function setTestimonial($post)
    {
        //prepare testimonial
        $testimonialID = $post->testimonial;

        if ($testimonialID !== '' && get_post_status($testimonialID) !== false) {
            $post->testimonial = array(
                'name'  => get_field('name', $testimonialID),
                'post_content' => get_post_field('post_content', $testimonialID),
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($testimonialID), 'full')[0],
                'excerpt' => get_post_field('excerpt', $testimonialID),
            );
        } else {
            $post->testimonial = false;
        }
    }

    private function setNeighborhoodFeatures($post)
    {
        $post->neighborhood_features = get_field('neighborhood_features', $post->ID);
        if (is_array($post->neighborhood_features)) {
            $post->neighborhood_features = array_map(function ($feature) {
                $feature['icon'] = convertCategoryToIcon($feature['category']);
                return $feature;
            }, $post->neighborhood_features);
        }
    }

    private function setGalleryItems($post)
    {
        $gallery_items = get_field('gallery_items', $post->ID);
        $post->gallery_items = prepare_masonry_gallery_data($gallery_items);
    }

    private function setForm($post, $id)
    {
        $post->request_info_form = gravity_form($id, false, false, false, null, $ajax = true, 0, false);
    }

    private function setModalGalleryForm($post, $id)
    {
        $post->modal_gallery_form = gravity_form($id, false, false, false, null, $ajax = true, 0, false);
    }

    private function setUpcomingEvent($post)
    {
        $today = date('Y-m-d');
        $args = array(
            'post_type' => 'event',
            'posts_per_page' => 2,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'neighborhood',
                    'value' => '"' . $post->ID . '"',
                    'compare' => 'LIKE'
                ),
                array(
                    'key'       => 'end_date_time',
                    'value'     => $today,
                    'compare'   => '>=',
                    'type'      => 'DATE'
                )
            )
        );
        $events = get_posts($args);

        $events = array_map(function ($event) {
            $event->event_url = get_permalink($event->ID);
            $event->banner_btn = 'Get Event Details';
            return $event;
        }, $events);

        if (count($events) > 1) {
            $post->events = array(
                array(
                    'event_url' => '/events',
                    'banner_btn' => 'See Events'
                )
            );
        } else {
            $post->events = $events;
        }
    }

    private function setStatus($post)
    {
        if (isset($post->status)) {
            $status = $post->status;
            $post->{$status} = true;

            // get value of status field
            $field = get_field_object('status', $post->ID);
            $value = $field['value'];
            $post->status_label = $field['choices'][$value];
        }
    }

    private function setFormCTAText($post)
    {
        if (isset($post->ID)) {
            // use different CTA text for Brennan by the Lake only
            if ($post->ID == 80) {
                $post->form_cta_text = 'Get in Touch';
            } else {
                $post->form_cta_text = 'Get Updates';
            }
        }
    }
}
