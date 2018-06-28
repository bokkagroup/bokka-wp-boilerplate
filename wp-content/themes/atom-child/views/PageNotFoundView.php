<?php

namespace CatalystWP\AtomChild\views;

class PageNotFoundView extends \CatalystWP\Nucleus\View
{
    public function initialize()
    {
        $this->template = 'pages/404';
    }
}
