<?php

namespace BokkaWP\Theme\models;

class NeighborhoodsOv extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;

        $products = get_posts(
            array(
                'post_type'         => array('communities', 'model', 'home'),
                'posts_per_page'    => 500,
                'suppress_filters'  => false,
                'orderby'           => 'title',
                'order'             => 'ASC',
            )
        );

        $neighborhoods = array();
        $models = array();
        $homes = array();

        foreach ($products as $product) {
            if ($product->post_type == 'communities') {
                $neighborhoods[] = $product;
            } elseif ($product->post_type == 'model') {
                $models[] = $product;
            } else {
                $homes[] = $product;
            }
        }

        $tabs = array(
            'neighborhoods' => array(
                'title' => 'Our Neighborhoods',
                'copy' => get_field('our_neighborhoods_overview'),
                'tab_title' => 'Neighborhoods',
                'products' => formatNeighborhoodTypes($neighborhoods)
            ),
            'models' => array(
                'title' => 'Model Homes',
                'copy' => get_field('models_homes_overview'),
                'tab_title' => 'Model Homes',
                'neighborhoods' => sortProductByNeighborhood($models)
            ),
            'homes' => array(
                'title' => 'Quick Move-In Homes',
                'copy' => get_field('qmi_overview'),
                'tab_title' => 'Quick Move-in',
                'neighborhoods' => sortProductByNeighborhood($homes)
            )
        );

        $tabs = addPostTypeBoolean($tabs);
        $tabs = applyFiltersToProducts($tabs);

        $post->tabs = $tabs;
        $this->data = $post;
    }
}
