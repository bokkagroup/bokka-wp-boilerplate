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
    public $modal_gallery_form;
    public $get_brochure_form;
    public $floorplan = true;

    public function initialize()
    {
        global $post;
        $this->import($post);
        $this->setNeighborhood($post);
        $this->setNeighborhoodLink($post);
        $this->setNeighborhoodTitle($post);
        $this->setForm($post, 38);
        $this->setModalGalleryForm($post, 37);
        $this->setPDF($post);
        $this->setGalleryItems($post);
        $this->setElevations();
        $this->setPostType($post);
    }

    private function setPostType($post)
    {
        $this->post_type = get_post_type($post);
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
        $this->get_brochure_form = $form;
    }

    private function setModalGalleryForm($post, $id)
    {
        $this->modal_gallery_form = gravity_form($id, false, false, false, null, $ajax = true, 0, false);
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
