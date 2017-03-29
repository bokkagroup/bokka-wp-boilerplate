<?php

namespace BokkaWP\BDX\Admin;

require_once(BGBDX_DIR . 'admin/Field.php');

class HtmlGenerator {

    private static $states =  array('AL','AK','AS','AZ','AR','CA','CO','CT','DE','DC',
        'FM','FL','GA','GU','HI','ID','IL','IN','IA','KS','KY','LA','ME','MH',
        'MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC',
        'ND','MP','OH','OK','OR','PW','PA','PR','RI','SC','SD','TN','TX','UT',
        'VT','VI','VA','WA','WV','WI','WY'
    );

    /**
     * This serves as our sole API for this class
     * @param $field
     * @param array $options (indices, post_type)
     * @return Field
     */
    static public function create_field(array $field, array $options = array('indices' => []))
    {

        //get the value of our field
        $value = self::get_value($field, $options['indices']);

        // create a string of our indexes we can use in the name of our fields
        // maintains our data structure
        if (is_array($options['indices'])) {
            //create the string
            $string = implode(
                //create an new array of strings
                array_map(function($index){
                    //stringified indexes
                    return "[${index}]";
                }, $options['indices'])
            );
        }

        //our prefix + indexes + fields name = the path to our field
        $field['name'] = BGBDX_PREFIX . "${string}[${field['name']}]";

        //our html will go here
        $html = "";

        //get our html based on type
        switch ($field['type']) {
            case 'text':
            case 'phone':
            case 'email':
            case 'number':
            case 'password':
                $html = self::create_text_field($field, $value);
                break;
            case 'checkbox':
                $html = self::create_checkbox_field($field, $value);
                break;
            case 'post_type':
                $html = self::create_post_type_field($field, $value);
                $options['post_type'] = $value;
                break;
            case 'acf':
            case 'acf-relationship':
               $html = self::create_acf_field($field, $options['post_type'], $value);
                break;
            case 'post_field-relationship':
               $html = self::create_post_field($field, $value);
                break;
            case 'post_field':
               $html = self::create_post_field($field, $value);
                break;
            case 'state':
                $html = self::create_state_field($field, $value);
                break;
        }

        // add a hidden field for our type
        // we'll need this to translate our configs
        $html .= "<input
                    type=\"hidden\"
                    name=\"${field['name']}[type]\"
                    value=\"${field['type']}\"
                 />";

        //create a new field object and return to requester
        return new Field(array(
            "value" => $value,
            "html"  => $html
        ));
    }

    /**
     * Gets value from array based on array of indexes
     * recursively jumps through each index until it gets to the last one.
     * @param array $array
     * @param array $indexes
     * @return bool/string
     */
    static private function get_array_value(array $data, array $indexes)
    {
        //ensure our arrays are populated
        if (count($data) == 0 || count($indexes) == 0)  {
            return false;
        }

        //get our first index and remove it from our indexes array
        $index = array_shift($indexes);

        //if our data doesn't exist don't even both with going forward
        if(!array_key_exists($index, $data)){
            return false;
        }

        //get the contents of this index
        $value = $data[$index];

        //if we have no more indexes to crawl return the value
        if (count($indexes) == 0) {
            return $value;
        }

        // if we still have an index the value should be an array.
        // avoids null values
        if(!is_array($value)) {
            return;
        }

        //recursively call self
        return self::get_array_value($value, $indexes);
    }

    static private function get_value($field, $indices)
    {
        //pull ourdata
        $data = get_option('bdx-data');
        $data = $data ? $data : [];

        //special handling for post_type fields
        // so they can have child fields and a value by adding an extra index
        if ($field['type'] === 'post_type') {
            $name = [$field['name'], 'post_type'];
        } else {
            //all other fields
            $name = [$field['name']];
        }

        //our value always comes from the "value" index,
        //needs to be our last addition to our indices
        array_push($name, 'value');

        //get values from data
        $indices = array_merge($indices, $name);

        $value = self::get_array_value($data, $indices);
        return $value;

    }

    /**
     * Generic input type, user input gets stored directly as value
     * @param $field
     * @param string $value
     * @return string
     */
    static private function create_text_field($field, $value = "")
    {
        $html = "<input type=\"${field['type']}\" name=\"${field['name']}[value]\" value=\"${value}\" /></td>";
        return $html;

    }

    /**
     * Generic input type for checkbox, provides boolean functionality
     * @param $field
     * @param string $value
     * @return string
     */
    static private function create_checkbox_field($field, $value = '')
    {
        if ($value == 'true'){ 
            $checked = 'checked';
        } else {
            $checked = '';
        }

        $html = "<input type=\"${field['type']}\" ${checked} name=\"${field['name']}[value]\" value=\"true\" /></td>";
        return $html;

    }

    /**
     * Select a post type
     * @param $field
     * @param bool $value
     * @return string
     */
    static private function create_post_type_field($field, $value = false)
    {
        //query our post types
        $post_types = get_post_types(array("publicly_queryable"=>true,"public"=>true));
        $html = "<select name=\"${field['name']}[post_type][value]\">";
        $html .= "<option disabled selected value=\"\">Select Post Type</option>";
            //an option for each post type
            foreach ($post_types as $type) {
                $selected = $value === $type ? "selected" : "";
                $html .= "<option ${selected} value=\"${type}\">${type}</option>";
            }
        $html .= "</select>";
        return $html;
    }

    /**
     * Field allows you to select ACF fields that are available from a post
     * post_type required (best used after a post_type field)
     * @param $field
     * @param $post_type
     * @param bool $value
     * @return string
     */
    static private function create_acf_field($field, $post_type, $value = false)
    {
        //To get our fields we need one post from our post type
        $post_type_post = get_posts(array(
            "post_type"         => $post_type,
            "posts_per_page"    => 1
        ));

        //get the field names from ACF
        $acf_fields = get_field_objects($post_type_post[0]->ID);
        $html = "<select name=\"${field['name']}[value]\">";
        $html .= "<option disabled selected value=\"\">Select ACF Field</option>";
            foreach ($acf_fields as $field) {
                $selected = $value === $field['name'] ? "selected" : "";
                $html .= "<option ${selected} value=\"${field['name']}\">${field['label']}</option>";
            }
        $html .= "</select>";
        return $html;
    }

    /**
     * Post Fields are default post object fields like "post_title" ($post->post_title)
     * https://codex.wordpress.org/Class_Reference/WP_Post#Member_Variables_of_WP_Post
     * @param $field
     * @param bool $value
     * @return string
     */
    static private function create_post_field($field, $value = false)
    {
            $post_fields = array('ID','post_title', 'post_content', 'featured_image', 'attachments', 'permalink');

        $html = "<select name=\"${field['name']}[value]\">";
        $html .= "<option disabled selected value=\"\">Select Post Field</option>";
            foreach ($post_fields as $post_field) {
                $selected = $value === $post_field ? "selected" : "";
                $html .= "<option ${selected} value=\"${post_field}\">${post_field}</option>";
            }
        $html .= "</select>";
        return $html;
    }

    /**
     * Allows user to select a two character state.
     * @param $field
     * @param $value
     * @return string
     */
    static private function create_state_field($field, $value)
    {
        $states = self::$states;
        $html = "<select name=\"${field['name']}[value]\">";
        $html .= "<option disabled selected value=\"${value}\">Select state</option>";
            foreach ( $states as $state) {
                $selected = $value === $state ? "selected" : "";
                $html .= "<option ${selected} value=\"${state}\">$state</option>";
            }
        $html .= "</select>";
        return $html;
    }
}