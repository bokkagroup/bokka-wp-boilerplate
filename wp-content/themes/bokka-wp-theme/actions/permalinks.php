<?php
/**
 * This file Provides filters for our permalinks
 */
function available_homes_permalink($post_link, $post, $leavename, $sample)
{
    // global $post;
    if (strpos($post_link, '%location%') ||
        strpos($post_link, '%community%')) {
        $community = get_post($post->ID, 'community');
        if(get_post_type($post->ID) === 'home') {
            $id = get_post_meta($post->ID, 'community')[0];
        } else {
            $id = $post->ID;
        }
        $location =   strtolower (
            get_post_meta($id, 'city')[0].'-'.get_post_meta($id, 'state')[0]
        );
        $post_link = str_replace('%community%', $community->post_title, $post_link);
        $post_link = str_replace('%location%', $location, $post_link);
    }
    return $post_link;
}
add_filter('post_type_link', 'available_homes_permalink', 10, 4);

function custom_rewrite_basic()
{
    #homes permalink
    add_rewrite_rule(
        'homes/(.*)/(.*)/(.*)',
        'index.php?post_type=home&name=$matches[3]',
        'top');
    #communities permalink
    add_rewrite_rule(
        'homes/(.*)/(.*)',
        'index.php?post_type=communities&name=$matches[2]',
        'top'
    );

}
add_action('init', 'custom_rewrite_basic');
