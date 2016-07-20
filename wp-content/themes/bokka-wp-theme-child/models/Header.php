<?php

namespace BokkaWP\Theme\models;

class Header extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $primarynav = new \BokkaWP\Theme\models\PrimaryNav();
        $breadcrumbs = new \BokkaWP\Theme\models\Breadcrumbs();
        $this->data['primary_nav'] = $primarynav->data;
        $this->data['breadcrumbs'] = $breadcrumbs->data;
    }
}
