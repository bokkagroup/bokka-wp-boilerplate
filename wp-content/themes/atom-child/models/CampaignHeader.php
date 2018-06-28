<?php

namespace CatalystWP\AtomChild\models;

class CampaignHeader extends \CatalystWP\Nucleus\Model
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
