<?php

namespace CatalystWP\AtomChild\controllers;

class FloorplansController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
            $this->view->display($this->model);
    }
}
