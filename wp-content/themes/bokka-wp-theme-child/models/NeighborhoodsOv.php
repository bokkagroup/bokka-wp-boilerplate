<?php

namespace BokkaWP\Theme\models;

class NeighborhoodsOv extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;

        // 'Neighborhoods' tab
        $neighborhoods = get_posts(
            array(
                'post_type'         => 'communities', // needs to be changed from communities to neighborhoods
                'posts_per_page'    => 500,
                'suppress_filters'  => false,
                'orderby'           => 'title',
                'order'             => 'ASC'
            )
        );

        // 'Model Homes' tab
        $models = get_posts(
            array(
                'post_type'         => 'model',
                'posts_per_page'    => 500,
                'suppress_filters'  => false,
                'orderby'           => 'title',
                'order'             => 'ASC'
            )
        );

        // 'Quick-Move-in' tab
        $homes = get_posts(
            array(
                'post_type'         => 'home',
                'posts_per_page'    => 500,
                'suppress_filters'  => false,
                'orderby'           => 'title',
                'order'             => 'ASC'
            )
        );

        $tabs = array(
            'neighborhoods' => array(
                'title' => 'Neighborhoods',
                'products' => formatNeighborhoodTypes($neighborhoods)
            ),
            'models' => array(
                'title' => 'Model Homes',
                'neighborhoods' => sortProductByNeighborhood($models)
            ),
            'homes' => array(
                'title' => 'Quick Move-in',
                'neighborhoods' => sortProductByNeighborhood($homes)
            )
        );

        $tabs = addPostTypeBoolean($tabs);
        $tabs = applyFiltersToProducts($tabs);

        $post->tabs = $tabs;
        $this->data = $post;

        // echo "<pre>";
        // print_r($tabs['homes']['neighborhoods']);
        // echo "</pre>";
    }
}
