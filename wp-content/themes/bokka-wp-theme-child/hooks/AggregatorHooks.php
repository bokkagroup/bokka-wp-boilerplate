<?php

if (!function_exists('\BokkaWP\bdx_add_filter')) {
    return;
}

\BokkaWP\bdx_add_filter('bdx-adapter-configuration', 'NeighborhoodImages');
function NeighborhoodImages(array $configuration, array $options)
{
    $name = key($configuration);

    if ($name != 'images' || $options['post_type'] != 'communities') {
        return $options['value'];
    }

    //make sure we grab featured images
    $thumbnail = get_post_thumbnail_id($options['parent_post']->ID, 'large');

    if (!$thumbnail) {
        return $options['value'];
    }


    if ($thumbnail) {
        $images[] = array(
            'name'  =>  get_the_title($thumbnail),
            'url'   =>  wp_get_attachment_url($thumbnail)
        );
    }

    //photo gallery images
    if (is_array($options['value'])) {
        $images_gallery =  [];
        foreach ($options['value'] as $image) {

            if ($image['type'] !== 'image' || !isset($image['image'])) {
                continue;
            }

            $images_gallery[] = array(
                'name'  =>  get_the_title($image['image']),
                'url'   =>  wp_get_attachment_url($image['image'])
            );
        }

        $images = array_merge($images, $images_gallery);
    }

    return $images;
}


\BokkaWP\bdx_add_filter('bdx-adapter-configuration', 'convertPhone');
function convertPhone($configuration, $options)
{
    $name = key($configuration);

    if ($name != 'phone' || !is_string($options['value'])) {
        return $options['value'];
    }

    $phone = preg_replace("/[^0-9]/", "", $options['value']);
    $value = array(
        "areacode"  => substr($phone, 0, 3),
        "prefix"    => substr($phone, 3, 3),
        "suffix"    => substr($phone, 6, 4)
    );

    $value = array_map(function ($value) {
        return (is_numeric($value) ? (int)$value : $value);
    }, $value);

    return $value;
}


/**
 * There are a few fields we have to grab from the neighborhood for home post type
 */
\BokkaWP\bdx_add_filter('bdx-adapter-configuration', 'convertHomeFields');
function convertHomeFields($configuration, $options)
{
    $name = key($configuration);

    if (!isset($options['post_type'])) {
        return $options['value'];
    }

    if (($name == 'state' ||
        $name == 'city' ||
        $name == 'zip') &&
        $options['post_type'] == 'home') {
        $neighborhood = get_field('neighborhood', $options['parent_post']->ID);

        return get_field($name, $neighborhood);
    }

    return $options['value'];
}


\BokkaWP\bdx_add_filter('bdx-adapter-relationship-filter', 'homeRelationship');
function homeRelationship($configuration, $options)
{

    $name = key($configuration);

    if ($name != 'post_type' || $configuration[$name]['value'] != 'home') {
        return false;
    }

    if ($options['value'] == $options['parent_post']->ID) {
        return true;
    }

    return false;
}


\BokkaWP\bdx_add_filter('bdx-adapter-relationship-filter', 'floorplanRelationship');
function floorplanRelationship($configuration, $options)
{

    $name = key($configuration);

    if ($name != 'post_type' || $configuration[$name]['value'] != 'plans') {
        return false;
    }

    if ($options['value'] == $options['parent_post']->ID) {
        return true;
    }

    return false;
}

/**
 * Translates floorplan images
 */

\BokkaWP\bdx_add_filter('bdx-adapter-configuration', 'floorplanImages');
function floorplanImages(array $configuration, array $options)
{
    $name = key($configuration);

    if ($name != 'images' || $options['post_type'] != 'plans') {
        return $options['value'];
    }

    $images = [];

    $configs = [$configuration['images']['elevation-images'], $configuration['images']['floorplan-images']];

    foreach ($configs as $type => $config) {
        $type = $type == 'elevation-images' ? 'elevation' : 'floorplan';

        $value = get_field($config['value'], $options['parent_post']->ID);

        if (!$value) {
            return;
        }

        $converted = array_map(function ($image) use ($type) {

            if ($type == 'floorplan') {
                $image['ID'] = $image['image'];
            }

            $converted_image = array(
                'name'  => substr($image['title'], 0, 99),
                'type'  => $type,
                'url'   => wp_get_attachment_image_src($image['ID'], 'large')[0]
            );

            return $converted_image;
        }, $value);

        $images = array_merge($images, $converted);
    }

    return $images;
}


\BokkaWP\bdx_add_filter('bdx-adapter-configuration','convertBaths');
function convertBaths($configuration, $options)
{

    //baths need to be integers only.
    $name = key($configuration);
    if($name != 'baths') {
        return $options['value'];
    }

    $floor = floor($options['value']);
    $decimal = $options['value'] - $floor;

    //if there is 3/4 bath, BDX counts it as a full bath
    if ($decimal > 0) {
        $value = $floor + ( $decimal == 0.75 ? 1 : 0);
    } else {
        $value = $options['value'];
    }


    return $value;
}
