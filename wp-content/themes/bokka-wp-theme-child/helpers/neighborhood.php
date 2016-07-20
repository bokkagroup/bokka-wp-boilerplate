<?php

/**
 * Generates an array to display in the tabbed product listing organism
 * @param $id
 * @return mixed
 */
function tabbedProductData($id)
{
    $data['tabs'] = array();
    //meta_query for this neighborhood
    $metaQuery = array(
        array(
            'key' => 'neighborhood',
            'value' => "${id}",
            'compare' => 'LIKE'
        )
    );

    //plans
    $plans = get_posts(
        array('post_type' => 'plans', 'meta_query' => $metaQuery)
    );
    apply_filters('bokkamvc_filter_before_render', $plans);
    if (count($plans) > 0) {
        $data['tabs'][] = array(
            'title' => 'Floorplans',
            'types' => sortProductByType($plans)
        );
    }

    //homes (qmi)
    $homes = get_posts(
        array('post_type' => 'home', 'meta_query' => $metaQuery)
    );
    apply_filters('bokkamvc_filter_before_render', $homes);
    if (count($homes) > 0) {
        $data['tabs'][] = array(
            'title' => 'Quick Move-In Homes',
            'types' => sortProductByType($homes)
        );
    }

    //models
    $models = get_posts(
        array('post_type' => 'model', 'meta_query' => $metaQuery)
    );
    apply_filters('bokkamvc_filter_before_render', $models);
    if (count($models) > 0) {
        $data['tabs'][] = array(
            'title' => 'Model Homes',
            'types' => sortProductByType($models)
        );
    }
    return $data;
}

/**
 * take a list of products and sort them in an array based on type
 * @param $posts
 * @return array
 */
function sortProductByType($posts)
{
    $types = array();
    //get our products and their types in a basic array structure
    foreach ($posts as $post) {
        $type = getProductType($post);
        if (strpos ( $type, 'Townhome')) {
            $type = "Townhomes";
        } elseif (strpos($type, 'Patio')) {
            $type = "Patio Homes";
        }
        $price = round(number_format(getProductPrice($post) / 1000, 0), -1);
        if (isset($types[$type]['price']) &&
            $types[$type]['price'] < $price) {
            $price = $types[$type]['price'];
        }
        $types[$type]['title'] = $type;
        $types[$type]['price'] = $price;
        $types[$type][$post->post_type] = true;
        $types[$type]['products'][] = $post;
    }

    //if there is only one types return it
    if (count($types) <= 1) {
        return $types;

    //make simple quick sort to sort types by length
    } else {
        $pivot = count(reset($types)['products']);
        $left = array();
        $right = array();
        foreach ($types as $key => $value) {
            if ($value['title'] !== $pivot['title']) {
                if (count($value['products']) > $pivot) {
                    $left[$key] = $value;
                } else {
                    $right[$key] = $value;
                }
            }
        }
        $types = array_merge($left, array($pivot), $right);
    }
    return $types;
}

function convertCategoryToIcon($category)
{
    switch ($category) {
        case 'location':
            return 'icon-amenities-location';
            break;
        case 'active_lifestyle':
            return 'icon-amenities-active-lifestyle';
            break;
        case 'scenery':
            return 'icon-amenities-scenery';
            break;
        case 'shopping_dinning':
            return 'icon-amenities-shopping';
            break;
        case 'low_maintenance':
            return 'icon-amenities-low-maint';
            break;
    }
}

/**
 * @param $id
 * @return float
 * gets a base price from all floorplans associated to a neighborhood
 */
function getNeighborhoodPrice($id)
{

    $plans = get_posts(array(
        'post_type'         => 'plans',
        'meta_query'        => array(
            array(
                'key'       => 'neighborhood',
                'value'     => $id,
                'compare'   => 'LIKE',
            )
        )
    ));

    $price = 0;

    foreach ($plans as $plan) {
        if ($price === 0) {
            $price = $plan->base_price;
        } elseif ($plan->base_price < $price) {

            $price = $plan->base_price;
        }
    }
    return round(number_format($price / 1000, 0), -1);
}