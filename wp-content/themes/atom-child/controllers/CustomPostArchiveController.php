<?php

namespace CatalystWP\AtomChild\controllers;

class CustomPostArchiveController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->view->display($this->model);
    }
}
