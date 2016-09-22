<?php

namespace BokkaWP\Theme\controllers;

class ContactUsController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model->data);
    }
}
