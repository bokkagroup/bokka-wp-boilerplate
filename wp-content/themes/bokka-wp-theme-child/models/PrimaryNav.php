<?php

namespace BokkaWP\Theme\models;

class PrimaryNav extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        //get array of menu items
        $menu = wp_get_nav_menu_object('primary');
        $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
        $count = 0;
        $menu_object = array();

        //loop throug menu items
        foreach ($menuitems as $item) {
            $link = $item->url;
            $post_type = get_post_type($item->object_id);
            $title = $item->title;

            $slug = get_post_field('post_name', $item->object_id);

            if (strpos($slug, '720') !== false) {
                $slug = 'phone';
            }

            if (strpos($slug, 'our-locations') !== false) {
                $slug = 'map-signs';
            }

            //parent level menu items
            if (!$item->menu_item_parent) {
                $parent_id = $item->ID;
                $menu_object['links'][$item->ID]['link'] = $link;
                $menu_object['links'][$item->ID]['title'] = $title;
                $menu_object['links'][$item->ID]['class'] = $slug;
            }

            //handle our neighborhoods parent item
            if ($slug === 'our-neighborhoods') {
                $menu_object['links'][$item->ID]['overview']['url'] = '/our-neighborhoods';
                $menu_object['links'][$item->ID]['overview']['title'] = '+ See all neighborhoods';
                $menu_object['links'][  $item->ID ]['subnav'][0]['link'] = '/our-neighborhoods';
                $menu_object['links'][  $item->ID ]['subnav'][0]['title'] = '+ See all neighborhoods';
            }

            //handle items that are children of parent
            //variables aren't destroyed in foreach scope ($parent_id references the last time it was set)
            //child nav items always come after parent from our array
            if ($parent_id == $item->menu_item_parent) {
                //build up our array
                $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['link'] = $link;
                $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['title'] = $title;

                //add extra data for neighborhoods
                if ($post_type === 'communities') {
                    $city = get_post_meta($item->object_id, 'city');
                    $price = get_post_meta($item->object_id, 'base_price');
                    $types = get_post_meta($item->object_id, 'types');
                    if ($city) {
                        $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['city'] = $city[0];
                    }
                    if (isset($price[0])) {
                        $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['price'] = round(number_format($price[0] / 1000, 0), -1);
                    }
                    if (isset($types[0])) {
                        $types = explode(',', $types[0]);
                        $menu_object['links'][$item->menu_item_parent]['subnav'][$item->ID]['types'] = $types;
                    }
                }
            }
            $count++;
        }//foreach

        $this->data = $menu_object;

        return $menu_object;
    }
}
