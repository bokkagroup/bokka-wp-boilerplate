<?php

namespace CatalystWP\AtomChild\models;

class Campaign extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        global $post;

        $organisms = new \CatalystWP\AtomChild\models\Organisms();

        $this->data['organisms'] = $organisms->data;
        $this->import($post);
    }

    private function import(\WP_Post $post)
    {
        foreach (get_object_vars($post) as $key => $value) {
            $this->$key = $value;
        }
    }
}
