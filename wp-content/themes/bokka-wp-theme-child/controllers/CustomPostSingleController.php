<?php

namespace BokkaWP\Theme\controllers;

class CustomPostSingleController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model->data);
    }
}
