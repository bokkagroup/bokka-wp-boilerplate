<?php

namespace BokkaWP\Theme\models;

class BreadcrumbsModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        if(is_singular(array('plans'))){
            $this->data = $this->breadcrumbs();
        }
    }

    /**
     * Generates an array of links and their title
     * @return array
     */
    public function breadcrumbs()
    {
        global $post;
        $crumbs = [];
        $crumbs[0]['title'] = 'home';
        $crumbs[0]['link'] = '/';
        $crumbs[1]['title'] = get_the_title($post->neighborhood);
        $crumbs[1]['link'] = get_permalink($post->neighborhood);
        $crumbs[1]['class'] = 'icon icon-our-neighborhoods';
        $crumbs[2]['title'] = $post->post_title;
        $crumbs[2]['link'] = '#';
        $crumbs[2]['class'] = 'icon icon-our-homes';
        return $crumbs;
    }
}
