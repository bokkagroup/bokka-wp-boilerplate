<?php
namespace CatalystWP\AtomChild\models;

class Floorplan extends \CatalystWP\Nucleus\Model
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
    public $garage_display;

    public function initialize()
    {
        $this->setNeighborhood();
        $this->setNeighborhoodLink();
        $this->setNeighborhoodTitle();
        $this->setElevations();
        $this->setForms(array(
            'request_info_form' => 4,
            'modal_gallery_form' => 37
        ));
        $this->setFormOrBrochure();
        $this->setPDF();
        $this->setGalleryItems();
        $this->setPostType();
        $this->setGarageDisplay();
    }

    private function setPostType()
    {
        $this->post_type = $this->post_type;
    }

    private function setNeighborhood()
    {
        $this->neighborhood = get_post($this->neighborhood);
    }

    private function setNeighborhoodLink()
    {
        $this->neighborhood_link = get_the_permalink($this->neighborhood);
    }

    private function setNeighborhoodTitle()
    {
        $this->neighborhood_title = get_the_title($this->neighborhood);
    }

    private function setGalleryItems()
    {
        $gallery_items = get_field('gallery_items', $this->ID);
        $this->gallery_items = prepare_masonry_gallery_data($gallery_items);
    }

    private function setPDF()
    {
        $this->pdf = wp_get_attachment_url($this->pdf);
    }

    private function setFormOrBrochure()
    {
        $email = isset($_COOKIE['email']) ? $_COOKIE['email'] : false;

        if ($email) {
            $this->displayBrochureForm = false;
            $this->userEmail = $email;
            $this->pdfSrc = get_attached_file($this->pdf);

            if (isset($this->elevations[0])) {
                $src = wp_get_attachment_image_src($this->elevations[0], 'medium');
                $this->thumbnail = $src[0];
            }
        } else {
            $this->displayBrochureForm = true;
            $this->setForms(array('get_brochure_form' => 38));
        }
    }

    private function setForms($forms)
    {
        foreach ($forms as $formName => $formID) {
            $form = gravity_form($formID, false, false, false, null, $ajax = true, 0, false);
            $this->$formName = $form;
        }
    }

    private function setElevations()
    {
        if (isset($this->elevations)) {
            $this->elevations = array_map(function ($image) {
                return $image['id'];
            }, $this->elevations);
        } else {
            $this->elevations = false;
        }
    }

    private function setGarageDisplay()
    {
        $garage_min = get_field('garage_min', $this->ID);
        $garage_max = get_field('garage', $this->ID);
        if ($garage_max > $garage_min) {
            $this->garage_display = $garage_min . ' - ' . $garage_max;
        } else {
            $this->garage_display = $garage_min;
        }
    }
}
