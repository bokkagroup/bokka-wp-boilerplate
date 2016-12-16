<?php

function setSizeMedium($item)
{
    $item['url'] = wp_get_attachment_image_src($item['ID'], 'medium')[0];
    return $item;
}


/**
 * Takes feature data and prepares it for template
 * @param $feature
 * @return mixed
 */
function prepare_feature_bar_data($feature)
{
    $feature['image'] = wp_get_attachment_image_src($feature['image'], 'medium')[0];
    $feature['description'] = get_feature_description($feature['feature']);
    return $feature;
}

/**
 * Prepares data for tabbed organism
 * @param $tab
 * @return mixed
 */
function prepare_tabbed_data($tab)
{
    $tab['image'] = wp_get_attachment_image_src($tab['image'], 'full')[0];
    return $tab;
}

/**
 * Prepares data for masonry gallery organism
 * @param array
 * @return array
 */
function prepare_masonry_gallery_data($gallery_items)
{
    $gallery_data = array();

    if (isset($gallery_items) && is_array($gallery_items)) {
        $gallery_data = array_map(function ($item) {
            if ($item['type'] === 'image') {
                $item['caption'] = get_the_excerpt($item['image']);
                $item['image'] =  wp_get_attachment_image_src($item['image'], 'medium')[0];
                return $item;
            }
            if ($item['type'] === 'video') {
                $item['video'] = true;
                $item['embed_url'] = get_video_embed_url($item['video_url']);
                $item['thumbnail'] =  wp_get_attachment_image_src($item['thumbnail'], 'medium')[0];
                return $item;
            }
        }, $gallery_items);
    }

    return $gallery_data;
}
