<?php

namespace BokkaWP\Theme\models;

class QuickMoveInHomes extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->tabs = neighborhoodOverviewData();
        $this->data = $post;
    }
}
