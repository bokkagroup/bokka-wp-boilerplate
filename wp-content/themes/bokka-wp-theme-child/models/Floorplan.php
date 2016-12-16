<?php
namespace BokkaWP\Theme\models;

class Floorplan extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->neighborhood_link = get_the_permalink($post->neighborhood);
        $post->neighborhood_title = get_the_title($post->neighborhood);
        $form = gravity_form(4, false, false, false, null, $ajax = true, 0, false);
        $post->brand_window_form = $form;
        $post->pdf = wp_get_attachment_url($post->pdf);
        $form = gravity_form(4, false, false, false, null, $ajax = true, 0, false);
        $post->coming_soon =  array('modal_content'=> $form);
        $gallery_items = get_field('gallery_items', $post->ID);
        $post->gallery_items = prepare_masonry_gallery_data($gallery_items);
        $this->data = $post;
    }
}
