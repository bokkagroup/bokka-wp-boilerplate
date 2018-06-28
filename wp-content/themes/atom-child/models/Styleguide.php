<?php

namespace CatalystWP\AtomChild\models;

class Styleguide extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        $organisms = new \CatalystWP\AtomChild\models\Organisms();
        $this->data['organisms'] = $organisms->data;
    }
}
