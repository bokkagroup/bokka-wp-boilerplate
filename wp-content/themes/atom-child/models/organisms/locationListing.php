<?php

if (isset($organism['type']) && $organism['type'] === "location-listing") {
    $locations = get_posts(
        array(
            'post_type'         => 'locations',
            'posts_per_page'    => 500,
            'suppress_filters'  => false,
            'orderby'           => 'title',
            'order'             => 'ASC',
        )
    );
    $location_data = array_map('mapLocationData', $locations);
    $organism['item'] = $location_data;
    $organism['form'] = setForm(16);
}

function mapLocationData($post)
{
    setTitle($post);
    setSubTitle($post);
    setImage($post);
    setHours($post);
    setPhone($post);
    setEmail($post);
    setLatLong($post);
    setNeighborhood($post);
    return $post;
}

function setTitle($post)
{
    $post->title = get_the_title($post->ID);
}

function setSubTitle($post)
{
    $city = get_post_meta($post->ID, 'city', true);
    $state = get_post_meta($post->ID, 'state', true);
    $post->sub_title = $city . ', ' . $state;
}

function setImage($post)
{
    $image_id = get_post_meta($post->ID, 'image', true);
    $post->image = wp_get_attachment_image_src($image_id, 'thumb-product-listing')[0];
}

function setHours($post)
{
    $hours = get_post_meta($post->ID, 'hours', true);
    $post->hours = wpautop($hours);
}

function setPhone($post)
{
    $post->phone = get_post_meta($post->ID, 'phone', true);
}

function setEmail($post)
{
    $post->email = get_post_meta($post->ID, 'email', true);
}

function setLatLong($post)
{
    $post->latitude = get_post_meta($post->ID, 'latitude', true);
    $post->longitude = get_post_meta($post->ID, 'longitude', true);
}

function setAddress($post)
{
    $post->address_1 = get_post_meta($post->ID, 'address_1', true);
    $post->address_2 = get_post_meta($post->ID, 'address_2', true);
    $post->city = get_post_meta($post->ID, 'city', true);
    $post->state = get_post_meta($post->ID, 'state', true);
    $post->zip = get_post_meta($post->ID, 'zip', true);
}

function setNeighborhood($post)
{
    $neighborhood = get_post_meta($post->ID, 'neighborhood', true)[0];
    if ($neighborhood) {
        $post->neighborhood_url = get_permalink($neighborhood);
        $post->base_price = get_post_meta($neighborhood, 'base_price')[0];
        if (isset($post->base_price) && $post->base_price) {
            $post->base_price = round(number_format($post->base_price / 1000, 0), -1);
        }
        if ($types = get_post_meta($neighborhood, 'types')) {
            $post->types = explode(',', $types[0]);
        }

        if ($status = get_post_meta($neighborhood, 'status')) {
            if ($status[0] == 'sold_out') {
                $post->sold_out = true;
            }
        }

        $post->models = getModels($neighborhood);
    }
}

function getModels($neighborhood)
{
    $metaQuery = array(
        array(
            'key' => 'neighborhood',
            'value' => "${neighborhood}",
            'compare' => '='
        )
    );

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

    $models = array_map(function ($model) {
         $model->link = get_permalink($model->ID);
         return $model;
    }, $models);

    return $models;
}

function setForm($id)
{
    return gravity_form($id, false, false, false, null, $ajax = true, 0, false);
}
