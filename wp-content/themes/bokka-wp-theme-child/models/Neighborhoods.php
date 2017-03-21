<?php

namespace BokkaWP\Theme\models;

class Neighborhoods extends \BokkaWP\MVC\Model
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
        $this->setUpcomingEvent($post);
        $this->setStatus($post);
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
                'image' => wp_get_attachment_image_src(get_post_thumbnail_id($testimonialID), 'full')[0]
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

    private function setUpcomingEvent($post)
    {
        if ($post->upcoming_event) {
            $post->upcoming_event_url = get_permalink(get_field('upcoming_event', $post->ID));
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
}
