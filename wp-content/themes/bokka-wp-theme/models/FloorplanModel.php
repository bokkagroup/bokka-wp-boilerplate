<?php

namespace BokkaWP\Theme\models;

class FloorplanModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->neighborhood_link = get_the_permalink($post->neiborhood);
        $post->neighborhood_title = get_the_title($post->neighborhood);
        $form = gravity_form(2, false, false, false, null, $ajax = true, 0, false);
        $post->brand_window_form =  $form;
        $post->pdf = wp_get_attachment_url($post->pdf);
        $form = gravity_form(3, false, false, false, null, $ajax = true, 0, false);
        $post->coming_soon =  array('modal_content'=> $form);
        $this->data = $post;
    }
}
