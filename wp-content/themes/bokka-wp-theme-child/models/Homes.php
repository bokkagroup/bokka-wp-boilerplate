<?php

namespace BokkaWP\Theme\models;

class Homes extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->setNeighborhood($post);
        $this->setForms($post, array(
            'request_info_form' => 4,
            'get_brochure_form' => 38
        ));
        $this->setPDF($post);
        $this->setTabs($post);
        $this->setMap($post, 16);
        $this->data = $post;
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

    private function setPDF($post)
    {
        $post->pdf = wp_get_attachment_url($post->pdf);
    }

    private function setTabs($post)
    {
        $post->tabs = get_field('tabs');
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
            'hours'     => 'By Appointment Only',
            'phone'     => $post->neighborhood->phone,
            'latitude'  => $post->latitude,
            'longitude' => $post->longitude,
            'zoom'      => $zoom,
            'sale_team_members' => $sales_team
        );
    }
}
