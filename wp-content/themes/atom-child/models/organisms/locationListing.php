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

    $location_data = array_map(function ($post) {
        $post->title = get_the_title($post->ID);

        $city = get_post_meta($post->ID, 'city', true);
        $state = get_post_meta($post->ID, 'state', true);
        $post->sub_title = $city . ', ' . $state;

        $image_id = get_post_meta($post->ID, 'image', true);
        $post->image = wp_get_attachment_image_src($image_id, 'thumb-product-listing')[0];

        $hours = get_post_meta($post->ID, 'hours', true);
        $post->hours = wpautop($hours);



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

            $post->models = getNeighborhoodModels($neighborhood);
        }
    }, $locations);

    $organism['item'] = $locations;
    $organism['form'] = setForm(16);
}
