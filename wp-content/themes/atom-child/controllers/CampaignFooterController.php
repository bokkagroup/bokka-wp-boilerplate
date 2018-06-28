<?php

namespace CatalystWP\AtomChild\controllers;

class CampaignFooterController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        echo $this->view->render($this->model);
    }
}
