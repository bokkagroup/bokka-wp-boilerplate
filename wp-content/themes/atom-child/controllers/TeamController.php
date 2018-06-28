<?php

namespace CatalystWP\AtomChild\controllers;

class TeamController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->view->display($this->model->data);
    }
}
