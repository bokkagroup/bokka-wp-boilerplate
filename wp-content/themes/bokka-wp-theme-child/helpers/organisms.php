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
