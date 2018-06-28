<?php

namespace CatalystWP\AtomChild\controllers;

class LocationsController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {

        add_action('catatlystwp_nucleus_before_display', array($this, "renderOrganisms"));

        $this->view->display($this->model->data);

    }

    public function renderOrganisms()
    {
        new \CatalystWP\AtomChild\controllers\OrganismsController();
    }
}
