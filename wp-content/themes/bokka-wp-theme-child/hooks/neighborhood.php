<?php
/**
 * These filters are to provide functionality when saving a post
 * For example it makes more sense to set base prices for a neighborhood
 * when saving a post, rathe than querying all of the product multiple times to display
 * pricing.
 */

/**
 * @param $post_id
 * historically we've queried all of a communities product to set a base price
 * here we set it on post save for floorplans
 */
function setNeighborhoodPricing($post_id)
{
    //get the neighborhood & it's price
    $neighborhood_id = get_post_meta($post_id, 'neighborhood')[0];

    $neightbordhood_price = getNeighborhoodPrices($neighborhood_id);
    if (isset($neightbordhood_price)) {
        update_post_meta($neighborhood_id, 'base_price', $neightbordhood_price);
    }

    return;
}
add_action('save_post', 'setNeighborhoodPricing');


function setNeighborhoodTypes($post_id)
{
    $post_type = get_post_type($post_id);

    // only do this for plans
    if ($post_type !== 'plans') {
        return;
    }

    //get the neighborhood and all floorplans associated with it
    $neighborhood_id = get_post_meta($post_id, 'neighborhood')[0];
    $neighborhood_plans = get_posts(array(
        'post_type' => 'plans',
        'posts_per_page' => 500,
        'meta_key' => 'neighborhood',
        'meta_value' => "{$neighborhood_id}"
    ));

    // make new array of all plan types for current neighborhood
    $neighborhood_plans_types = array_map(function ($plan) {
        $type = getProductType($plan->ID);
        return getDefaultType($type);
    }, $neighborhood_plans);

    //make sure there aren't any empty types
    $neighborhood_plans_types = array_filter($neighborhood_plans_types, function ($type) {
        return $type !== '';
    });

    // make sure there aren't any duplicate values
    $neighborhood_plans_types = array_unique($neighborhood_plans_types);

    if (count($neighborhood_plans_types) > 1) {
        $types_string = implode(',', $neighborhood_plans_types);
    } else {
        $types_string = $neighborhood_plans_types[0];
    }

    update_post_meta($neighborhood_id, 'types', $types_string);
}

add_action('save_post', 'setNeighborhoodTypes');
