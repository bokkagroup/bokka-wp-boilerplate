<?php

namespace BokkaWP\Theme\models;

class PrimaryNavModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $menu = wp_get_nav_menu_object('primary');
        $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
        $count = 0;
        $menu_object = array();
        foreach ($menuitems as $item) {
            $link = $item->url;
            $post_type = get_post_type($item->object_id);
            $title = $item->title;

            $slug = get_post_field('post_name', $item->object_id);

            if (!$item->menu_item_parent) {
                $parent_id = $item->ID;
                $menu_object['links'][$item->ID]['link'] = $link;
                $menu_object['links'][$item->ID]['title'] = $title;
                $menu_object['links'][$item->ID]['class'] = $slug;

            }
            if($slug === 'our-neighborhoods'){
                $menu_object['links'][$item->ID]['overview']['url'] = '/our-neighborhoods';
                $menu_object['links'][$item->ID]['overview']['title'] = '+ See all neighborhoods';
                $menu_object['links'][  $item->ID ]['subnav'][0]['link'] = '/our-neighborhoods';
                $menu_object['links'][  $item->ID ]['subnav'][0]['title'] = '+ See all neighborhoods';
            }
            if ($parent_id == $item->menu_item_parent) :

                $menu_object['links'][  $item->menu_item_parent ]['subnav'][$item->ID]['link'] = $link;
                $menu_object['links'][  $item->menu_item_parent ]['subnav'][$item->ID]['title'] = $title;
                if($post_type === 'communities') {
                    $city = get_post_meta($item->object_id, 'city');
                    if ($city) {
                        $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['city'] = $city[0];
                    }
                    $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['price'] = 400;
                }
            endif;
            $count++;
        }//foreach
        $menu_object['content'] = apply_filters('the_content', get_the_content());
        $this->data = $menu_object;

        return $menu_object;
    }
}
