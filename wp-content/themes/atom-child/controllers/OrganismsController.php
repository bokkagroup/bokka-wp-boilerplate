<?php

namespace CatalystWP\AtomChild\controllers;

class OrganismsController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->model->data['organisms'] = $this->model->data;
        echo $this->view->render($this->model->data);
    }
}
