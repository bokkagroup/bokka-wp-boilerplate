<?php

namespace CatalystWP\AtomChild\models;

class CustomPostArchive extends \CatalystWP\Nucleus\Model
{
    public function initialize()
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = explode('/', $path);
        $post = get_page_by_path($path[1]);
        $organisms = new \CatalystWP\AtomChild\models\Organisms(array('post_id'=> $post->ID));
        $this->organisms = $organisms->data;
    }
}
