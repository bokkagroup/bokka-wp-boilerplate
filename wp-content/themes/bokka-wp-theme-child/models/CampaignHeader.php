<?php

namespace BokkaWP\Theme\models;

class CampaignHeader extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->import($post);
    }

    private function import(\WP_Post $post)
    {
        foreach (get_object_vars($post) as $key => $value) {
            $this->$key = $value;
        }
    }
}
