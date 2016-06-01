<?php

namespace BokkaWP\Theme\models;

class FloorplanModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->neighborhood_link = get_the_permalink($post->neiborhood);
        $post->neighborhood_title = get_the_title($post->neighborhood);
        $post->elevations_gallery = get_image_sizes_src($post->elevations, array('full', 'thumbnail'), true);
        $post->base_price = number_format($post->base_price / 1000, 0);
        $post->features = array_map('prepare_feature_bar_data', get_field('features'));
        $form = gravity_form(2, false, false, false, null, $ajax = true, 0, false);
        $post->brand_window_form = array('modal_content'=> $form);
        if ($tabs = get_field('tabs')) {
            $post->tabs =  array_map('prepare_tabbed_data', $tabs);
        }
        $post->pdf = wp_get_attachment_url($post->pdf);
        $form = gravity_form(3, false, false, false, null, $ajax = true, 0, false);
        $post->coming_soon =  array('modal_content'=> $form);
        $this->data = $post;
    }
}
