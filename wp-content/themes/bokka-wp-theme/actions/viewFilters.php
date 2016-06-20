<?php
/**
 * Use this file to hook into data before being rendered in a view
 * Great for changes that need to happen globally
 * or code you don't want to repeat in your models
 */

/**
 * @param $data
 * @return mixed
 * Retrieves Image urls for elevations_gallery
 */
function generateElevationsGalleryData($data)
{
    if (isset($data->elevations) && $data->elevations) {
        $data->elevations_gallery = get_image_sizes_src($data->elevations, array('full', 'thumbnail'), true);
    }
    return $data;
}
add_filter( 'filter_before_render', 'generateElevationsGalleryData' );


/**
 * @param $data
 * @return mixed
 * Formats Base_price (seen on floorplans)
 */
function formatBasePrice($data)
{
    if (isset($data->base_price) && $data->base_price) {
        $data->base_price = number_format($data->base_price / 1000, 0);
    }
    return $data;
}
add_filter( 'filter_before_render', 'formatBasePrice' );


/**
 * @param $data
 * @return mixed
 * Formats price (seen on homes qmi)
 */
function formatPrice($data)
{
    if (isset($data->price) && $data->price) {
        $data->price = number_format( $data->price );
    }
    return $data;
}
add_filter( 'filter_before_render', 'formatPrice' );


/**
 * @param $data
 * @return mixed
 * Prepares tabbed data, like thumbnail sizes and the like
 */
function generateTabGalleryData($data)
{
    if (isset($data->tabs) && $data->tabs) {
        $data->tabs =  array_map('prepare_tabbed_data', get_field('tabs', $data->ID));
    }
    return $data;
}
add_filter( 'filter_before_render', 'generateTabGalleryData' );


/**
 * @param $data
 * @return mixed
 * Get floorplan post & run filters on it
 */
function getAttachedFloorplan($data)
{
    if(isset($data->floorplan) && $data->floorplan) {
        $data->floorplan = get_post($data->floorplan);
        $data->floorplan->permalink = get_the_permalink($data->floorplan->ID);
        //recursively apply the filters in this file to the attached floorplan as well
        apply_filters('filter_before_render', $data->floorplan);
    }
    return $data;
}
add_filter( 'filter_before_render', 'getAttachedFloorplan' );


/**
 * @param $data
 * @return mixed
 * This will prep feature bar data ( such as getting images)
 */
function generateFeatureBarData($data)
{
    if(isset($data->features) && $data->features){
        $features = get_field('features', $data->ID);
        if(count($features) > 0 && is_array($features)){
            $data->features = array_map('prepare_feature_bar_data', $features);
        }
    }
    return $data;
}
add_filter( 'filter_before_render', 'generateFeatureBarData' );

/**
 * @param $data
 * Outputs the Brandwindow fallback form
 */
function includeBrandWindowFallbackForm($data){

    if ( isset($data->post_type) && (
        $data->post_type == 'plans' ||
        $data->post_type == 'model' ||
        $data->post_type == 'home')
    ) {
            $data->brandwindow_fallback_form = gravity_form(5, false, false, false, null, $ajax = true, 0, false);
    }
    return $data;
}
add_filter( 'filter_before_render', 'includeBrandWindowFallbackForm' );