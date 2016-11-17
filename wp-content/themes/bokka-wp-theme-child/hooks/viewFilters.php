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
add_filter('bokkamvc_filter_before_render', 'generateElevationsGalleryData');

/**
 * @param $data
 * @return mixed
 * Formats Base_price (seen on floorplans)
 */
function formatBasePrice($data)
{
    if (isset($data->base_price) && $data->base_price) {
        $data->base_price = round(number_format($data->base_price / 1000, 0));
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'formatBasePrice');

/**
 * @param $data
 * @return mixed
 * Formats price (seen on homes qmi)
 */
function formatPrice($data)
{
    if (isset($data->price) && $data->price) {
        $data->price = number_format($data->price);
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'formatPrice');

/**
 * @param $data
 * @return mixed
 * Prepares tabbed data, like thumbnail sizes and the like
 */
function generateTabGalleryData($data)
{
    if (isset($data->ID) &&
        $tabs = get_field('tabs', $data->ID) ) {
        $data->tabs =  array_map('prepare_tabbed_data', $tabs);
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'generateTabGalleryData');

/**
 * @param $data
 * @return mixed
 * Get floorplan post & run filters on it
 */
function getAttachedFloorplan($data)
{
    if (isset($data->floorplan) && $data->floorplan) {
        $data->floorplan = get_post($data->floorplan);
        $data->floorplan->permalink = get_the_permalink($data->floorplan->ID);
        //recursively apply the filters in this file to the attached floorplan as well
        apply_filters('bokkamvc_filter_before_render', $data->floorplan);
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'getAttachedFloorplan');

/**
 * @param $data
 * @return mixed
 * This will prep feature bar data ( such as getting images)
 */
function generateFeatureBarData($data)
{
    if (isset($data->features) && $data->features) {
        $features = get_field('features', $data->ID);
        if (count($features) > 0 && is_array($features)) {
            $data->features = array_map('prepare_feature_bar_data', $features);
        }
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'generateFeatureBarData');

/**
 * @param $data
 * Outputs the Brandwindow fallback form
 */
function includeBrandWindowFallbackForm($data)
{
    if (isset($data->post_type) && (
        $data->post_type == 'plans' ||
        $data->post_type == 'model' ||
        $data->post_type == 'home')) {
            $data->brandwindow_fallback_form = gravity_form(5, false, false, false, null, $ajax = true, 0, false);
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'includeBrandWindowFallbackForm');


/**
 * @param $data
 * Outputs the Brandwindow fallback form
 */
function setupAlternatingContentData($data)
{

    if (isset($data->alternating_content) &&
        isset($data->alternating_content['items']) &&
        count($data->alternating_content['items']) > 0) {
        $array = array();

        foreach ($data->alternating_content['items'] as $item) {
            $item['image'] = wp_get_attachment_url($item['image'], 'medium');
            $array[] = $item;
        }
        $data->alternating_content['items'] = $array;
    }

    return $data;
}
add_filter('bokkamvc_filter_before_render', 'setupAlternatingContentData');

/**
 * @param $data
 * Sets a permalink
 */
function setPermalink($data)
{
    if (isset($data->ID) && $link = get_permalink($data->ID)) {
        $data->permalink = $link;
    }

    return $data;
}
add_filter('bokkamvc_filter_before_render', 'setPermalink');

/**
 * @param $data
 * Applies the view filters on tabbed products
 */
function filterTabbedProduct($data)
{
    if (isset($data->product) &&
       isset($data->product['tabs'])) {
        //array map is like foreach but radder
        $data->product['tabs'] = array_map(function ($tab) {
            $tab['types'] = array_map(function ($type) {
                if (count($type['products']) > 0) {
                    $type['products'] = array_map(function ($product) {
                        apply_filters('bokkamvc_filter_before_render', $product);
                        return $product;
                    }, $type['products']);
                    return $type;
                }
            }, $tab['types']);
            return $tab;
        }, $data->product['tabs']);
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'filterTabbedProduct');


/**
 * @param $data
 * Applies the view filters on tabbed products
 */
function getModelStats($data)
{
    if (isset($data->post_type) &&
        $data->post_type == 'model') {
        if (isset($data->floorplan->ID)) {
            $floorplan = $data->floorplan->ID;
        } else {
            $floorplan = $data->floorplan;
        }
        $data->bathrooms_min = get_field('bathrooms_min', $floorplan);
        $data->bathrooms_max = get_field('bathrooms_max', $floorplan);
        $data->bedrooms_min = get_field('bedrooms_min', $floorplan);
        $data->bedrooms_max = get_field('bedrooms_max', $floorplan);
        $data->main_sqr_ft = get_field('main_sqr_ft', $floorplan);
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'getModelStats');

/**
 * @param $data
 * Attaches the 'city' property to homes and models
 */
function getNeighborhoodCity($data)
{
    if (isset($data->post_type) && (
        $data->post_type == 'model' ||
        $data->post_type == 'home')) {
        $city_name = get_post_meta($data->neighborhood, 'city');
        if (isset($city_name) && $city_name[0]) {
            $data->city = $city_name[0];
        }
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'getNeighborhoodCity');

/**
 * @param $data
 * Attaches the neighborhood logo image source to neighborhood posts
 */
function getNeighborhoodLogo($data)
{
    if (isset($data->post_type) && (
        $data->post_type == 'communities')) {
        $logo_id = get_field('logo', $data->ID);
        if ($logo_id) {
            $data->logo_src = wp_get_attachment_image_src($logo_id, 'full')[0];
        }
    }
    return $data;
}
add_filter('bokkamvc_filter_before_render', 'getNeighborhoodLogo');
