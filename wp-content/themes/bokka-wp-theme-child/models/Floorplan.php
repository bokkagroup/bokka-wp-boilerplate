<?php
namespace BokkaWP\Theme\models;

class Floorplan extends \BokkaWP\MVC\Model
{
    public $neighborhood;
    public $neighborhood_link;
    public $neighborhood_title;
    public $gallery_items;
    public $pdf;
    public $request_info_form;

    public function initialize()
    {
        global $post;
        $this->import($post);
        $this->setNeighborhood($post);
        $this->setNeighborhoodLink($post);
        $this->setNeighborhoodTitle($post);
        $this->setForm($post, 4);
        $this->setPDF($post);
        $this->setGalleryItems($post);
        $this->setElevations();
    }

    private function setNeighborhood($post)
    {
        $this->neighborhood = get_post($post->neighborhood);
    }

    private function setNeighborhoodLink($post)
    {
        $this->neighborhood_link = get_the_permalink($post->neighborhood);
    }

    private function setNeighborhoodTitle($post)
    {
        $this->neighborhood_title = get_the_title($post->neighborhood);
    }

    private function setGalleryItems($post)
    {
        $gallery_items = get_field('gallery_items', $post->ID);
        $this->gallery_items = prepare_masonry_gallery_data($gallery_items);
    }

    private function setPDF($post)
    {
        $this->pdf = wp_get_attachment_url($post->pdf);
    }

    private function setForm($post, $id)
    {
        $form = gravity_form($id, false, false, false, null, $ajax = true, 0, false);
        $this->request_info_form = $form;
    }

    private function setElevations()
    {
        if ($this->elevations) {
            $this->elevations = array_map(function ($image) {
                return $image['id'];
            }, $this->elevations);
        } else {
            $this->elevations = false;
        }
    }

    private function import(\WP_Post $post)
    {
        foreach (get_object_vars($post) as $key => $value) {
            $this->$key = $value;
        }
    }
}
