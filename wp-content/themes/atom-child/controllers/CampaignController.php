<?php

namespace CatalystWP\AtomChild\controllers;

class CampaignController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        echo $this->view->render($this->model->data);
    }
}
