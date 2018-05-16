<?php

/**
 * @param $data
 * Applies the view filters on tabbed products
 */
function setFeaturedImage($data)
{
    $data = array_map(function ($post) {
        $post = setNeighborhoodFeaturedImage($post);
        return $post;
    }, $data);

    return $data;
}
add_filter('posts_results', 'setFeaturedImage');
