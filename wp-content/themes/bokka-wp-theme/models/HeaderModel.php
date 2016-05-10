<?php

namespace BokkaWP\Theme\models;

class HeaderModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $primarynav = new \BokkaWP\Theme\models\PrimaryNavModel();
        $this->data["primary_nav"] = $primarynav->data;
    }
}
