<?php

//Page Slug Body Class
function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
        if (is_singular('blog-post') || is_singular('career') || is_singular('event') || is_singular('testimonial')) {
            $classes[] = 'custom-post-single';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'add_slug_body_class');
