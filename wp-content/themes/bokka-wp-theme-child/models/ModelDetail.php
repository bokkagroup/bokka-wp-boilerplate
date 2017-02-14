<?php
namespace BokkaWP\Theme\models;

class ModelDetail extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->setNeighborhood($post);
        $this->setMap($post, 14);
        $this->data = $post;
    }

    private function setNeighborhood($post)
    {
        $post->neighborhood = get_post($post->neighborhood);
        $post->neighborhood->link = get_the_permalink($post->neighborhood);
        $post->neighborhood->title = get_the_title($post->neighborhood);
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
