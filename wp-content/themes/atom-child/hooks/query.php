<?php

/**
 * @param $data
 * Applies the view filters on tabbed products
 */
function setFeaturedImageFilter($data)
{
    $data = array_map(function ($post) {
        $post = setFeaturedImage($post);
        return $post;
    }, $data);

    return $data;
}
add_filter('posts_results', 'setFeaturedImageFilter');


function setFeaturedImage($post)
{
    if (isset($post->ID)) {
        if ($post->post_type === 'communities') {
            if (isset($post->thumbnail) && ($post->thumbnail)) {
                // use thumbnail field if available
                $post->featured_image = wp_get_attachment_image_src(
                    $post->thumbnail,
                    'thumb-product-listing'
                )[0];
            } else {
                // otherwise use brand window image (featured image)
                $post->featured_image = wp_get_attachment_image_src(
                    get_post_thumbnail_id($post->ID),
                    'thumb-product-listing'
                )[0];
            }
        } elseif (isset($post->post_type) && ( $post->post_type === 'model' || $post->post_type === 'home' || $post->post_type === 'plans' )) {
            if (isset($post->elevations) && isset($post->elevations[0])) {
                $post->featured_image = wp_get_attachment_url(
                    $post->elevations[0],
                    'medium'
                );
            }
        }
    }
    return $post;
}
