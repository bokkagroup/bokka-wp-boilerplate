<?php

namespace BokkaWP\Theme\models;

class Neighborhoods extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;

        $post->featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'large');
        $post->alternating_content = array('items' => get_field('alternating_content'));

        //gnarly tabbed product formatting
        $post->product = tabbedProductData($post->ID);

        //sitemap
        $post->site_map_thumbnail = wp_get_attachment_image_src($post->site_map_thumbnail, 'thumbnail')[0];
        $post->site_map_pdf = wp_get_attachment_url($post->site_map_pdf);

        //types
        if ($types = get_post_meta($post->ID, 'types')) {
            $post->types = explode(',', $types[0]);
        }

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

        //sales team
        $post->map['sale_team_members'] = get_field('sale_team_members', $post->ID);
        if (is_array($post->map['sale_team_members'])) {
            $post->map['sale_team_members'] = array_map(function ($member) {
                $member['image'] = wp_get_attachment_image_src($member['image'], 'thumbnail')[0];
                return $member;
            }, $post->map['sale_team_members']);
        }

        //Content with icons & text
        $post->neighborhood_features = get_field('neighborhood_features', $post->ID);
        if (is_array($post->neighborhood_features)) {
            $post->neighborhood_features = array_map(function ($feature) {
                $feature['icon'] = convertCategoryToIcon($feature['category']);
                return $feature;
            }, $post->neighborhood_features);
        }

        $mason_items = get_field('gallery_items', $post->ID);

        if (isset($mason_items) && is_array($mason_items)) {
            $post->gallery_items = array_map(function ($item) {
                if ($item['type'] === 'image') {
                    $item['caption'] = get_the_excerpt($item['image']);
                    $item['image'] =  wp_get_attachment_image_src($item['image'], 'medium')[0];
                    return $item;
                }
                if ($item['type'] === 'video') {
                    $item['video'] = true;
                    $item['embed_url'] = get_video_embed_url($item['video_url']);
                    $item['thumbnail'] =  wp_get_attachment_image_src($item['thumbnail'], 'medium')[0];
                    return $item;
                }
            }, $mason_items);
        }

        //forms
        $post->request_info_form = gravity_form(6, false, false, false, null, $ajax = true, 0, false);

        //legal copy (appears as part of map)
        $post->map['legal'] = get_field('legal', $post->ID);

        if (isset($post->status)) {
            $status = $post->status;
            $post->{$status} = true;
        }
        $this->data = $post;
    }
}
