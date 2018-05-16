<?php

namespace BokkaWP\Theme\models;

class EventArchive extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('/', $path);
        $post = get_page_by_path($path[1]);
        $organisms = new \BokkaWP\Theme\models\Organisms($post->ID);
        $this->data['organisms'] = $organisms->data;
    }
}
