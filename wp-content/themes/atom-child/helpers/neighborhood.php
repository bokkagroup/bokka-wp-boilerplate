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
            'compare' => '='
        )
    );

    //plans
    $plans = get_posts(
        array(
            'posts_per_page' => 500,
            'post_type' => 'plans',
            'meta_query' => $metaQuery,
            'suppress_filters' => false,
            'orderby' => 'title',
            'order' => 'ASC'
        )
    );

    if (count($plans) > 0) {
        $data['tabs'][] = array(
            'title' => 'Floorplans',
            'id' => 'floorplans',
            'types' => sortProductByType($plans)
        );
    }

    //homes (qmi)
    $homes = get_posts(
        array(
            'posts_per_page' => 500,
            'post_type' => 'home',
            'meta_query' => $metaQuery,
            'suppress_filters' => false,
            'orderby' => 'title',
            'order' => 'ASC'
        )
    );

    if (count($homes) > 0) {
        $data['tabs'][] = array(
            'title' => 'Quick Move-In Homes',
            'id' => 'qmi-homes',
            'types' => sortProductByType($homes)
        );
    }

    //models
    $models = get_posts(
        array(
            'posts_per_page' => 500,
            'post_type' => 'model',
            'meta_query' => $metaQuery,
            'suppress_filters' => false,
            'orderby' => 'title',
            'order' => 'ASC'
        )
    );

    if (count($models) > 0) {
        $data['tabs'][] = array(
            'title' => 'Model Homes',
            'id' => 'model-homes',
            'types' => sortProductByType($models)
        );
    }

    $data = applyFiltersToProducts($data);

    return $data;
}

/**
 * gather all data for Neighborhood Overview (and QMI/Model Homes) tabs
 * @param none
 * @return array
 */
function neighborhoodOverviewData()
{
    // Get post ID for neighborhood overview page in order to get
    // ACF tab header descriptions
    $neighborhoodOV_page_id = get_id_by_slug('our-neighborhoods');

    $products = get_posts(
        array(
            'post_type'         => array('communities', 'model', 'home'),
            'posts_per_page'    => 500,
            'suppress_filters'  => false,
            'orderby'           => 'title',
            'order'             => 'ASC',
        )
    );

    apply_filters('catatlystwp_nucleus_filter_before_render', $products);

    $neighborhoods = array();
    $models = array();
    $homes = array();

    foreach ($products as $product) {
        if ($product->post_type == 'communities') {
            if (isset($product->status)) {
                $status = $product->status;
                $product->{$status} = true;

                // get value of status field
                $field = get_field_object('status', $product->ID);
                $value = $field['value'];
                $product->status_label = $field['choices'][$value];
            }
            $neighborhoods[] = $product;
        } elseif ($product->post_type == 'model') {
            $models[] = $product;
        } else {
            $homes[] = $product;
        }
    }

    $tabs = array(
        'models' => array(
            'title' => 'Model Homes',
            'copy' => get_field('model_homes_overview', $neighborhoodOV_page_id),
            'tab_title' => 'Model Homes',
            'neighborhoods' => sortProductByNeighborhood($models),
            'class' => 'model-homes',
            'permalink' => get_relative_permalink(60)
        ),
        'neighborhoods' => array(
            'title' => 'Our Neighborhoods',
            'copy' => get_field('our_neighborhoods_overview', $neighborhoodOV_page_id),
            'tab_title' => 'Neighborhoods',
            'products' => formatNeighborhoodTypes($neighborhoods),
            'class' => 'our-neighborhoods',
            'permalink' => get_relative_permalink(54)
        ),
        'homes' => array(
            'title' => 'Quick Move-In Homes',
            'copy' => get_field('quick_move-ins_overview', $neighborhoodOV_page_id),
            'tab_title' => 'Quick Move-In',
            'neighborhoods' => sortProductByNeighborhood($homes),
            'class' => 'quick-move-in-homes',
            'permalink' => get_relative_permalink(58)
        )
    );

    $tabs = addPostTypeBoolean($tabs);
    $tabs = applyFiltersToProducts($tabs);

    return $tabs;
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
        $type = getProductType($post->ID);
        $type = getDefaultType($type);
        $price = round(number_format(getProductPrice($post) / 1000, 0), -1);
        if (isset($types[$type]['price']) && $types[$type]['price'] < $price) {
            $price = $types[$type]['price'];
        }
        if ($price > 0) {
            $types[$type]['price'] = $price;
        }
        $types[$type]['title'] = $type;
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

/**
 * take a list of products and sort them in an array based neighborhood
 * @param $posts
 * @return array
 */
function sortProductByNeighborhood($posts)
{
    $neighborhoods = array();

    // get our products and their types in a basic array structure
    foreach ($posts as $post) {
        $neighborhood = $post->neighborhood;
        $neighborhoods[$neighborhood]['title'] = get_the_title($neighborhood);
        $neighborhoods[$neighborhood]['products'][] = $post;
    }

    // attach additional data to post objects
    $neighborhoods = array_map(function ($item) {
        // attach neighborhood_name propery to each product
        iterateNeighborhoodData($item, 'addNeighborhoodNameToProducts');
        return $item;
    }, $neighborhoods);

    // alphabetize neighborhoods
    sort($neighborhoods);

    return $neighborhoods;
}

/**
 * Format neighborhood data
 * @param $data
 * @return array
 */
function formatNeighborhoodTypes($data)
{
    $data = array_map(function ($item) {
        iterateNeighborhoodData($item, function ($item) {
            //format the floorplan types associated with a neighborhood as "Patio Homes & Townhomes"
            $types = explode(',', get_post_meta($item->ID, 'types')[0]);
            $item->types = implode(' & ', $types);

            //set custom message if pricing data unavailable
            if (!$item->base_price || $item->base_price < 1) {
                if (($item->status === 'under_development') || ($item->status === 'coming_soon')) {
                    $item->pricing_message = 'Pricing coming soon';
                } elseif ($item->status === 'closeout') {
                    $item->pricing_message = 'Almost sold out';
                } else {
                    $item->pricing_message = false;
                }
            }
        });
        return $item;
    }, $data);
    return $data;
}

/**
 * Call iterateNeighborhoodData to apply Bokka filters on each post object in $data
 * @param $WP_Post object
 * @return array
 */
function applyFiltersToProducts($data)
{
    $data = array_map(function ($item) {
        iterateNeighborhoodData($item, 'applyBokkaFilters');
        return $item;
    }, $data);
    return $data;
}

/**
 * Callback function passed into iterateNeighborhoodData
 * @param $data (WP_Post object)
 * @return null
 */
function addNeighborhoodNameToProducts($data)
{
    if (isset($data->neighborhood) && $data->neighborhood) {
        $data->neighborhood_name = get_the_title($data->neighborhood);
    }
}

/**
 * Attach the post type = true as a property key for use in templates
 * @param $posts
 * @return array
 */
function addPostTypeBoolean($data)
{
    if (isset($data) && $data) {
        $data = array_map(function ($item) {
            iterateNeighborhoodData($item, function ($item) {
                $post_type = get_post_type($item->ID);
                $item->$post_type = true;
            });
            return $item;
        }, $data);
    }
    return $data;
}

/**
 * Callback function passed into iterateNeighborhoodData
 * @param $data (WP_Post object)
 * @return null
 */
function applyBokkaFilters($data)
{
    if (isset($data) && $data) {
        apply_filters('catatlystwp_nucleus_filter_before_render', $data);
    }
}

/**
 * Recursively iterate over nested array of product data and apply a callback function to each post object
 * @param $data, $callback
 * @return
 */
function iterateNeighborhoodData($data, $callback)
{
    if (is_object($data) && is_a($data, 'WP_Post')) {
        $callback($data);
        return $data;
    }
    if (is_array($data)) {
        foreach ($data as $item) {
            iterateNeighborhoodData($item, $callback);
        }
    }
    if (!is_array($data)) {
        return;
    }
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

function getNeighborhoodMinPrice($id)
{
    $posts = get_posts(
        array(
            'posts_per_page' => 500,
            'post_type' =>  array('home', 'plans'),
            'meta_key' =>   'neighborhood',
            'meta_value' => $id
        )
    );

    $prices = array();
    foreach ($posts as $post) {
        if ($post->post_type === 'home') {
            $price = get_post_meta($post->ID, 'price');
        } else {
            $price = get_post_meta($post->ID, 'base_price');
        }
        $prices[] = $price[0];
    }

    $prices = array_diff($prices, array(0, ''));
    $min_price = min($prices);
    return $min_price;
}


