<?php
namespace CatalystWP\AtomChild\controllers;

class StyleguideController extends \CatalystWP\Nucleus\Controller
{
    public function initialize()
    {
        $this->index();
    }

    private function index()
    {
        $this->view->display($this->model->data);
    }
}
