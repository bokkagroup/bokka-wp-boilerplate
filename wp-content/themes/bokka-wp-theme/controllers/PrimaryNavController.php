<?php

namespace BokkaWP\Theme\controllers;

class PrimaryNavController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->index();
    }

    private function index()
    {
         $this->view->display($this->model->data);
    }
}
