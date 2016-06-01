<?php

namespace BokkaWP\Theme\models;

class FloorplanModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->neighborhood = get_the_title($post->neighborhood);
        $post->gallery = get_image_sizes_src($post->elevations, array('full', 'thumbnail'), true);
        $post->base_price = number_format($post->base_price / 1000, 0);
        $post->features = array_map('prepare_feature_bar_data', get_field('features'));
        $post->tabs = array_map('prepare_tabbed_data', get_field('tabs'));
        $post->pdf = wp_get_attachment_url( $post->pdf  );
        $this->data = $post;
    }
}
