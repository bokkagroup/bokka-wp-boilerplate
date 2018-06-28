<?php

namespace CatalystWP\AtomChild\models;

class ModelHomes extends \CatalystWP\Nucleus\Model
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
