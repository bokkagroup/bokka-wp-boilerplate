<?php
namespace BokkaWP\Theme\models;

class ModelDetail extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->setNeighborhood($post);
        $this->setNeighborhoodTitle($post);
        $this->setForms($post, array(
            'request_info_form' => 4
        ));
        $this->setMap($post, 14);
        $this->setModalGalleryForm($post, 37);
        $this->setGalleryItems($post);
        $this->setPostType($post);
        $this->data = $post;
    }

    private function setPostType($post)
    {
        $post->type = get_post_type($post);
    }

    private function setNeighborhoodTitle($post)
    {
        $post->neighborhood_title = get_the_title($post->neighborhood);
    }

    private function setModalGalleryForm($post, $id)
    {
        $post->modal_gallery_form = gravity_form($id, false, false, false, null, $ajax = true, 0, false);
    }

    private function setGalleryItems($post)
    {
        $gallery_items = get_field('gallery_items', $post->ID);
        $post->gallery_items = prepare_masonry_gallery_data($gallery_items);
    }

    private function setNeighborhood($post)
    {
        $post->neighborhood = get_post($post->neighborhood);
        $post->neighborhood->link = get_the_permalink($post->neighborhood);
        $post->neighborhood->title = get_the_title($post->neighborhood);
    }

    private function setForms($post, $forms)
    {
        foreach ($forms as $formName => $formID) {
            $form = gravity_form($formID, false, false, false, null, $ajax = true, 0, false);
            $post->$formName = $form;
        }
    }

    private function setMap($post, $zoom)
    {
        $sales_team = getSalesTeamMembers($post->neighborhood->ID);
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
            'zoom'      => $zoom,
            'sale_team_members' => $sales_team
        );
    }
}
