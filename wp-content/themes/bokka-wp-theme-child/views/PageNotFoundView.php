<?php

namespace BokkaWP\Theme\views;

class PageNotFoundView extends \BokkaWP\MVC\View
{
    public function initialize()
    {
        $this->template = 'pages/404';
    }
}
