<?php

// Dynamically populate hidden fields based on custom parameters
// https://www.gravityhelp.com/documentation/article/using-dynamic-population/
function populate_neighborhood_name_field($value)
{
    global $post;
    $neighborhood_name = '';

    if ($post->neighborhood && isset($post->neighborhood->post_title)) {
        $neighborhood_name = $post->neighborhood->post_title;
    }

    return $neighborhood_name;
}
add_filter('gform_field_value_neighborhood_name', 'populate_neighborhood_name_field');

function populate_page_name_field($value)
{
    global $post;
    $post_type = get_post_type($post);

    if ($post_type == 'model') {
        return;
    }

    return $post->post_title;
}
add_filter('gform_field_value_page_name', 'populate_page_name_field');

function populate_post_type_field($value)
{
    global $post;
    $post_type = get_post_type($post);

    if ($post_type == 'home') {
        $post_type = 'qmi';
    } else if ($post_type == 'model') {
        $post_type = 'models';
    }

    return ($post_type) ? $post_type : '';
}
add_filter('gform_field_value_post_type', 'populate_post_type_field');

// Homeowner service request form - changes text inputs in "list" field to textareas
add_filter('gform_column_input_content_14_11_1', 'change_column1_content', 10, 6);
add_filter('gform_column_input_content_15_11_1', 'change_column1_content', 10, 6);
function change_column1_content($input, $input_info, $field, $text, $value, $form_id)
{
    //build field name, must match List field syntax to be processed correctly
    $input_field_name = 'input_' . $field->id . '[]';
    $tabindex = GFCommon::get_tabindex();
    $new_input = '<textarea name="' . $input_field_name . '" ' . $tabindex . ' class="textarea small" rows="10">' . $value . '</textarea>';
    return $new_input;
}
