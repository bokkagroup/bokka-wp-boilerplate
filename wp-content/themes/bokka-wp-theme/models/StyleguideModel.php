<?php

namespace BokkaWP\Theme\models;

class StyleguideModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $organisms = new \BokkaWP\Theme\models\OrganismModel();
        $this->data['organisms'] = $organisms->data;
    }
}
