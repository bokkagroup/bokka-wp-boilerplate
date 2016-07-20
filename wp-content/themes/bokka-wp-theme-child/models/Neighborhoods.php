<?php

namespace BokkaWP\Theme\models;

class Neighborhoods extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'large');
        $post->alternating_content = array('items' => get_field('alternating_content'));
        $post->product = tabbedProductData($post->ID);
        $post->site_map_thumbnail = wp_get_attachment_image_src($post->site_map_thumbnail, 'thumbnail')[0];
        $post->site_map_pdf = wp_get_attachment_url($post->site_map_pdf);

        //prepare testimonial
        $testimonialID = $post->testimonial;
        $post->testimonial = get_post($testimonialID);
        $post->testimonial->name = get_field('name', $testimonialID);
        $post->testimonial->image = wp_get_attachment_image_src(get_post_thumbnail_id($testimonialID), 'full')[0];

        //prepare map w info data
        $post->map = array(
            'address_1' => $post->address_1,
            'address_2' => $post->address_2,
            'city'      => $post->city,
            'state'     => $post->state,
            'zip'       => $post->zip,
            'hours'     => $post->hours,
            'phone'     => $post->phone,
            'latitude'  => $post->latitude,
            'longitude' => $post->longitude,
            'zoom'      => 14
        );
        $post->map['sale_team_members'] = get_field('sale_team_members', $post->ID);
        if (is_array($post->map['sale_team_members'])) {
            $post->map['sale_team_members'] = array_map(function ($member) {
                $member['image'] = wp_get_attachment_image_src($member['image'], 'thumbnail')[0];
                return $member;
            }, $post->map['sale_team_members']);
        }

        $post->neighborhood_features = get_field('neighborhood_features', $post->ID);
        if (is_array($post->neighborhood_features)) {
            $post->neighborhood_features = array_map(function ($feature) {
                $feature['icon'] = convertCategoryToIcon($feature['category']);
                return $feature;
            }, $post->neighborhood_features);
        }

        //forms
        $post->request_info_form = gravity_form(6, false, false, false, null, $ajax = true, 0, false);
        $post->get_updates_form = gravity_form(7, false, false, false, null, $ajax = true, 0, false);

        if (isset($post->status)) {
            $status = $post->status;
            $post->{$status} = true;
        }

        $this->data = $post;
    }
}
