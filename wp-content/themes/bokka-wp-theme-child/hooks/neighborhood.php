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
    $post_type = get_post_type($post_id);

    //only do this for plans & homes as they are only ones with price
    if($post_type !== 'plans')
        return;

    //get the neighborhood & it's price
    $neighborhood_id = get_post_meta($post_id, 'neighborhood')[0];
    $neighborhood_price = get_post_meta($neighborhood_id, 'base_price')[0];

    //get this posts price
    $post_price = get_post_meta($post_id, 'base_price')[0];

    if((isset($post_price) && $post_price < $neighborhood_price)||
        empty($neighborhood_price)){
        update_post_meta($neighborhood_id, 'base_price', $post_price);
    }

    return;
}
add_action( 'save_post', 'setNeighborhoodPricing' );


function setNeighborhoodTypes($post_id)
{
    $post_type = get_post_type($post_id);

    //only do this for plans & homes as they are only ones with price
    if($post_type !== 'plans')
        return;

    //get this posts type
    $type = getProductType($post_id);
    $type = getDefaultType($type);

    error_log($type);

    //get the neighborhood & it's types
    $neighborhood_id = get_post_meta($post_id, 'neighborhood')[0];
    $neighborhood_types = get_post_meta($neighborhood_id, 'types')[0];

    $neighborhood_types = explode(',', $neighborhood_types);

    if(in_array($type, $neighborhood_types))
        return;

    $neighborhood_types[] = $type;

    //make sure there aren't any empty types
    $neighborhood_types = array_filter($neighborhood_types, function($type){ return $type !== '';});

    if (count($neighborhood_types) > 1) {
        $types_string = implode(',', $neighborhood_types);
    } else {
      $types_string = $neighborhood_types[1];
    }

    update_post_meta($neighborhood_id, 'types', $types_string);
}

add_action( 'save_post', 'setNeighborhoodTypes' );