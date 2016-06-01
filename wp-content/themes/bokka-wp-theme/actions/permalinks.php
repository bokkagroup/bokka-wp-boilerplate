<?php
/**
 * This file Provides filters for our permalinks
 */
function available_homes_permalink($post_link, $post, $leavename, $sample)
{
    $post_type = get_post_type($post->ID);
    // global $post;
    if (strpos($post_link, '%location%') ||
        strpos($post_link, '%community%')) {
        if ($post_type === 'home' || $post_type === 'plans' || $post_type === 'model') {
            $id = get_post_meta($post->ID, 'neighborhood')[0];
            $community = get_post($id, 'community');
        } else {
            $id = $post->ID;
            $community = get_post($id, 'community');
        }

        $location =   strtolower(
            get_post_meta($id, 'city')[0].'-'.get_post_meta($id, 'state')[0]
        );
        $post_link = str_replace('%community%', $community->post_name, $post_link);
        $post_link = str_replace('%location%', $location, $post_link);
    }
    return $post_link;
}
add_filter('post_type_link', 'available_homes_permalink', 10, 4);

function custom_rewrite_basic()
{
    #plans permalink
    add_rewrite_rule(
        'homes/(.*)/(.*)/plans/(.*)',
        'index.php?post_type=plans&name=$matches[3]',
        'top'
    );

    #plans permalink
    add_rewrite_rule(
        'homes/(.*)/(.*)/models/(.*)',
        'index.php?post_type=model&name=$matches[3]',
        'top'
    );

    #homes permalink
    add_rewrite_rule(
        'homes/(.*)/(.*)/(.*)',
        'index.php?post_type=home&name=$matches[3]',
        'top'
    );
    #communities permalink
    add_rewrite_rule(
        'homes/(.*)/(.*)',
        'index.php?post_type=communities&name=$matches[2]',
        'top'
    );

}
add_action('init', 'custom_rewrite_basic');
