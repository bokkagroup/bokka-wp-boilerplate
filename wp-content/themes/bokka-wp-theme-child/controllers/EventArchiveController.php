<?php

namespace BokkaWP\Theme\controllers;

class EventArchiveController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model->data);
    }
}
