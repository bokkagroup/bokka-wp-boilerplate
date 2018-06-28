<?php

namespace CatalystWP\AtomChild\models;

class Header extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        $primarynav = new \CatalystWP\AtomChild\models\PrimaryNav();
        $breadcrumbs = new \CatalystWP\AtomChild\models\Breadcrumbs();
        $this->data['primary_nav'] = $primarynav->data;
        $this->data['breadcrumbs'] = $breadcrumbs->data;
    }
}
