<?php

namespace BokkaWP\Theme\models;

class Campaign extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;

        $organisms = new \BokkaWP\Theme\models\Organisms();

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
