<?php

namespace CatalystWP\AtomChild\models;

class EventArchive extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('/', $path);
        $post = get_page_by_path($path[1]);
        $organisms = new \CatalystWP\AtomChild\models\Organisms();
        $organisms->getFields($post->ID);
        $this->data['organisms'] = $organisms->data;
    }
}
