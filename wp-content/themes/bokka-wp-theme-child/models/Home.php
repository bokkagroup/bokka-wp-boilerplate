<?php

namespace BokkaWP\Theme\models;

class Home extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $organisms = new \BokkaWP\Theme\models\Organisms();
        $this->data['organisms'] = $organisms->data;
    }
}
