<?php

namespace CatalystWP\AtomChild\controllers;

class FloorplanController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->view->display($this->model);
    }
}
