<?php
namespace CatalystWP\AtomChild\controllers;

class PageNotFoundController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->view->display();
    }
}
