<?php

namespace CatalystWP\AtomChild\models;

class ContactUs extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        $organisms = new \CatalystWP\AtomChild\models\Organisms();
        $this->data['organisms'] = $organisms->data;
    }
}
