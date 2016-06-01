<?php

namespace BokkaWP\Theme\models;

class HeaderModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $primarynav = new \BokkaWP\Theme\models\PrimaryNavModel();
        $breadcrumbs = new \BokkaWP\Theme\models\BreadcrumbsModel();
        $this->data['primary_nav'] = $primarynav->data;
        $this->data['breadcrumbs'] = $breadcrumbs->data;
    }
}
