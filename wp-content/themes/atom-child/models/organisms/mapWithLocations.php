<?php
if (isset($organism['type']) && $organism['type'] === "map-w-locations") {
    if ($organism['custom_locations']) {
        return;
    } else {
        // if 'use custom locations' is not selected, populate location data with neighborhoods
        $neighborhoods = get_posts(
            array(
                'post_type'         => 'communities',
                'posts_per_page'    => 500,
                'suppress_filters'  => false,
                'orderby'           => 'title',
                'order'             => 'ASC',
            )
        );

        $neighborhoods = applyFiltersToProducts($neighborhoods);

        $neighborhoods = array_map(function ($post) {
            $post->latitude = get_post_meta($post->ID, 'latitude', true);
            $post->longitude = get_post_meta($post->ID, 'longitude', true);
            $title = get_the_title($post->ID);
            $city = get_post_meta($post->ID, 'city', true);
            $post->title = $title . ' in ' . $city;
            if ($post->base_price) {
                $post->sub_title = 'From the $' . $post->base_price . 's';
            }
            return $post;
        }, $neighborhoods);

        $organism['item'] = $neighborhoods;
    }
}
