<?php

function getProductType($post)
{
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
    if (isset($post->price)) {
        $price = $post->price;
    } elseif ($post->base_price) {
        $price = $post->base_price;
    }
    return $price;
}
