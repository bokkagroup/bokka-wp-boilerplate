<?php

namespace CatalystWP\AtomChild\controllers;

class PageController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        add_action('catatlystwp_nucleus_before_display', array($this, "renderOrganisms"));
        $this->view->display();
    }

    public function renderOrganisms()
    {
        new \CatalystWP\AtomChild\controllers\OrganismsController();
    }
}
