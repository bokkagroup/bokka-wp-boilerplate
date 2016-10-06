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
    foreach ($image_ids as $id) {
        setup_postdata($id);
        if ($meta) {
            $images[$id]['caption'] = get_the_excerpt($id);
        }
        foreach ($sizes as $size) {
            $images[$id]['url'][$size] = wp_get_attachment_image_src($id, $size)[0];
        }
        wp_reset_postdata();
    }
    return $images;
}

function get_id_from_video_url($url)
{

    $values = '';

    // YouTube URLs
    if (preg_match('/youtube/', $url, $id)) {
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } elseif (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } elseif (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else {
            return false;
        }
    } elseif (preg_match('/vimeo/', $url, $id)) {
        if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $id)) {
            $values = $id[5];
        } else {
            return false;
        }
    }

    return $values;
}

function get_video_embed_url($url)
{
    
    $id = get_id_from_video_url($url);
    
    if (isset($id) && $id) {
        if (strpos($url, 'youtube')) {
            $url = 'https://www.youtube.com/embed/' . $id . '?autoplay=1';
        } elseif (strpos($url, 'vimeo')) {
            $url = 'https://player.vimeo.com/video/' . $id . '?autoplay=1';
        }
    } else {
        return 'Invalid video ID';
    }

    return $url;
}
