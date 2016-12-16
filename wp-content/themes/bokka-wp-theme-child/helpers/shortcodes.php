<?php

function button_shortcode($atts, $content = "")
{
    $a = shortcode_atts(array(
        'url' => '',
        'color' => ''
    ), $atts);
    $color = esc_attr($a['color']);
    $output = '<a class="button ' . $color . '" href="' . esc_attr($a['url']) . '" target="_blank">' . $content . '</a>';
    return $output;
}
add_shortcode('button', 'button_shortcode');
