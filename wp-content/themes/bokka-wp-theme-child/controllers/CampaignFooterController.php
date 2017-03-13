<?php

namespace BokkaWP\Theme\controllers;

class CampaignFooterController extends \BokkaWP\MVC\Controller
{
    public function initialize()
    {
        $this->view->display($this->model);
    }
}
