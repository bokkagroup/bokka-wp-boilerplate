<?php

namespace BokkaWP\Theme\models;

class ModelDetailModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;

        $post->neighborhood = get_post($post->neighborhood);
        $post->neighborhood->link = get_the_permalink($post->neiborhood);
        $post->neighborhood->title = get_the_title($post->neighborhood);

        $post->elevations_gallery = get_image_sizes_src($post->elevations, array('full', 'thumbnail'), true);
        $post->base_price = number_format($post->base_price / 1000, 0);

        $form = gravity_form(2, false, false, false, null, $ajax = true, 0, false);
        $post->brand_window_form = array('modal_content'=> $form);
        if ($tabs = get_field('tabs')) {
            $post->tabs =  array_map('prepare_tabbed_data', $tabs);
        }
        $post->floorplan= get_post(get_field('floorplan'));
        $post->floorplan->permalink = get_the_permalink($post->floorplan->ID);
        $post->floorplan->base_price = number_format($post->floorplan->base_price / 1000, 0);
        if($features = get_field('features', $post->floorplan->ID)) {
            $post->features = array_map('prepare_feature_bar_data', $features);
        }

        $post->map = array(
            'address_1' => $post->address_1,
            'address_2' => $post->address_2,
            'city'      => $post->neighborhood->city,
            'state'     => $post->neighborhood->state,
            'zip'       => $post->neighborhood->zip,
            'hours'     => $post->neighborhood->hours,
            'phone'     => $post->neighborhood->phone,
            'latitude'  => $post->latitude,
            'longitude' => $post->longitude,
            'zoom'      => 14
        );

        $post->pdf = wp_get_attachment_url($post->pdf);
        $form = gravity_form(3, false, false, false, null, $ajax = true, 0, false);
        $post->coming_soon =  array('modal_content'=> $form);
        $this->data = $post;
    }
}

