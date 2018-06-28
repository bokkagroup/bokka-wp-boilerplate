<?php

namespace CatalystWP\AtomChild\controllers;

class HeaderController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->index();
    }

    private function index()
    {
        echo $this->view->render($this->model->data);
    }
}
