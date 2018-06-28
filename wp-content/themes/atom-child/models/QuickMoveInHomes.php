<?php

namespace CatalystWP\AtomChild\models;

class QuickMoveInHomes extends \CatalystWP\Nucleus\Model
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
