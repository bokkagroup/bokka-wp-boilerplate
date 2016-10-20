<?php

function getProductType($post_id)
{

    $post = get_post($post_id);
    $post_type = $post->post_type;

    if ($post_type == 'home' ||
        $post_type == 'model') {
        $floorplan = get_post($post->floorplan);
        return $floorplan->type;
    } elseif ($post_type == 'plans') {
        return $post->type;
    }
}

function getProductPrice($post)
{
    $price = 0;
    $post_type = get_post_type($post);
    if ($post_type !== 'plans' && isset($post->price)) {
        $price = $post->price;
    } elseif (isset($post->base_price)) {
        // Floorplans use base price
        $price = $post->base_price;
    }
    return $price;
}

function getDefaultType($type)
{
    if (strpos($type, 'Townhome')) {
        return "Townhomes";
    } elseif (strpos($type, 'Patio')) {
        return "Patio Homes";
    }
}
