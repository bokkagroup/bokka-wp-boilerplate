<?php

namespace BokkaWP\Theme\models;

class FooterModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $menu = wp_get_nav_menu_object('footer');
        $menuitems = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
        $count = 0;
        $menu_object = array();
        foreach ($menuitems as $item) :
            $link = $item->url;
            $title = $item->title;
            $city = get_post_meta($item->object_id, 'city');
            if (!$item->menu_item_parent) :
                $parent_id = $item->ID;
                $menu_object['communities'][$item->ID]['link'] = $link;
                $menu_object['communities'][$item->ID]['title'] = $title;
                if($city)     {
                    $menu_object['communities'][$item->ID]['city'] = $city[0];
                }
                //var_dump($item  );
            endif;
            if ($parent_id == $item->menu_item_parent) :
                $menu_object['communities'][  $item->menu_item_parent ]['subnav'][$item->ID]['link'] = $link;
                $menu_object['communities'][  $item->menu_item_parent ]['subnav'][$item->ID]['title'] = $title;
            endif;
            $count++;
        endforeach;
        $menu_object['content'] = apply_filters('the_content', get_the_content());
        $this->data = $menu_object;

        return $menu_object;
    }
}
