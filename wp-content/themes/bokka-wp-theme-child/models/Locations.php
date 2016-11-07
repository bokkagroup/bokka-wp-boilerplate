<?php

namespace BokkaWP\Theme\models;

class Locations extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $locations = get_posts(
            array(
                'post_type'         => 'locations',
                'posts_per_page'    => 500,
                'suppress_filters'  => false,
                'orderby'           => 'title',
                'order'             => 'ASC',
            )
        );
        $location_data = array();
        foreach ($locations as $item) {
            $title = $item->post_title;
            $keys = array('image', 'phone');
            $image_id = get_post_meta($item->ID, 'image', true);
            $image = wp_get_attachment_image_src($image_id, 'thumb-product-listing')[0];
            $phone = get_post_meta($item->ID, 'phone', true);
            $hours = get_post_meta($item->ID, 'hours', true);
            $hours_formatted = wpautop($hours);
            $email = get_post_meta($item->ID, 'email', true);
            $address_1 = get_post_meta($item->ID, 'address_1', true);
            $address_2 = get_post_meta($item->ID, 'address_2', true);
            $city = get_post_meta($item->ID, 'city', true);
            $state = get_post_meta($item->ID, 'state', true);
            $zip = get_post_meta($item->ID, 'zip', true);
            $latitude = get_post_meta($item->ID, 'latitude', true);
            $longitude = get_post_meta($item->ID, 'longitude', true);
            $neighborhood = get_post_meta($item->ID, 'neighborhood', true);
            if ($neighborhood) {
                $neighborhood_url = get_permalink($neighborhood[0]);
                $location_data['locations'][$item->ID]['neighborhood_url'] = $neighborhood_url;
            }
            
            $location_data['locations'][$item->ID]['title'] = $title;
            $location_data['locations'][$item->ID]['image'] = $image;
            $location_data['locations'][$item->ID]['phone'] = $phone;
            $location_data['locations'][$item->ID]['hours'] = $hours_formatted;
            $location_data['locations'][$item->ID]['email'] = $email;
            $location_data['locations'][$item->ID]['address_1'] = $address_1;
            $location_data['locations'][$item->ID]['address_2'] = $address_2;
            $location_data['locations'][$item->ID]['city'] = $city;
            $location_data['locations'][$item->ID]['state'] = $state;
            $location_data['locations'][$item->ID]['zip'] = $zip;
            $location_data['locations'][$item->ID]['latitude'] = $latitude;
            $location_data['locations'][$item->ID]['longitude'] = $longitude;
        }

        $form = gravity_form(16, false, false, false, null, $ajax = true, 0, false);
        $location_data['form'] = $form;

        $this->data = $location_data;
    }
}
