<?php

namespace CatalystWP\AtomChild\controllers;

class CampaignHeaderController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        echo $this->view->render($this->model);
    }
}
