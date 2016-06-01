<?php
/**
 * Takes an array of IDs and returns array of urls
 * @param array $image_ids
 * @param array/string $size
 * @param meta boolean $meta
 * @return array
 */
function get_image_sizes_src($image_ids, $sizes = array('full'), $meta = false)
{
    $images = [];
    foreach($image_ids as $id){
        if ($meta) {
            $images[$id]['caption'] = get_the_excerpt($id);
        }
        foreach($sizes as $size) {
            $images[$id]['url'][$size] = wp_get_attachment_image_src($id, $size)[0];
        }
    }
    return $images;
}
