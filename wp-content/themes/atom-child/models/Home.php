<?php

namespace CatalystWP\AtomChild\models;

class Home extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        global $post;
        $organisms = new \CatalystWP\AtomChild\models\Organisms($post->ID);
        $this->data['organisms'] = $organisms->data;
    }
}
