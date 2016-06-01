<?php

namespace BokkaWP\Theme\controllers;

class FloorplanController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model->data);
    }
}
