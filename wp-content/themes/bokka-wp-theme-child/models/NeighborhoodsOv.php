<?php

namespace BokkaWP\Theme\models;

class NeighborhoodsOv extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->setTabs($post);
        $this->data = $post;
    }

    private function setTabs($post)
    {
        $post->tabs = neighborhoodOverviewData();
    }
}
