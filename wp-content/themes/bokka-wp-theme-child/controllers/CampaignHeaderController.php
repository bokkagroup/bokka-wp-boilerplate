<?php

namespace BokkaWP\Theme\controllers;

class CampaignHeaderController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model);
    }
}
