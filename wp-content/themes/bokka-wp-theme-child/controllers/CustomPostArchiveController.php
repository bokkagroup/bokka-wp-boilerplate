<?php

namespace BokkaWP\Theme\controllers;

class CustomPostArchiveController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model->data);
    }
}
