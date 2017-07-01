<?php

namespace BokkaWP\BDX;

class Adapter {

    public function __construct($configurations)
    {
        $this->configurations = $configurations;

        $this->parsed = $this->parseConfiguration($this->configurations);
    }

    /**
     * Takes a confiration and parses out a new data set. Called recursively.
     * @param $configurations
     * @param array $options
     * @return array|bool
     */
    protected function parseConfiguration($configurations, $options = array(), $test = false)
    {
        //The parsed data will fill this array
        $array = [];



        //loop through each of our fields
        foreach($configurations as $key => $configuration){

            //handling is based on type
            //value dictates the functionality
            $type           =   isset($configuration['type']) ? $configuration['type'] : null;
            $value          =   isset($configuration['value']) ? $configuration['value'] : null;
            $options['key'] = $key;


            //reset final value
            unset($finalValue);

            switch ($type) {

                case 'text':
                case 'number':
                case 'email':
                case 'state':
                case 'password':
                case 'checkbox':


                    // These values are pretty much hard coded.
                    // Just set key and value
                    $finalValue = $value;
                    break;
                case 'number':
                // If this is a number field our API os likely expecting a
                    // number not a string our $_POST fields are always
                    // returned as strings. Regardless of field type
                    $value = (is_numeric($value) ? $value+0 : $value);
                    $finalValue = $value;
                    break;
                case 'acf':
                    $acf_value = get_field($value, $options['parent_post']->ID);
                    $acf_value = (is_numeric($acf_value) ? $acf_value+0 : $acf_value);
                    $finalValue = $acf_value;

                    break;
                case 'post_field':
                    if (isset($options['parent_post']->$value)) {
                        $finalValue = $options['parent_post']->$value;
                    } else if($value == 'permalink') {
                        $finalValue = get_the_permalink($options['parent_post']->ID);
                    }

                    break;
                case 'parent':
                    $bad_keys = array(
                        "type"              =>  false,
                        "post_type"         =>  false
                    );

                    // Skip our configuration
                    $child_configs = array_diff_key($configuration, $bad_keys);
                    $finalValue = $this->parseConfiguration($child_configs, $options);
                    break;
                case 'parent-array':
                    $bad_keys = array(
                        "type"              =>  false,
                        "post_type"         =>  false
                    );

                    // Skip our configuration
                    $child_configs = array_diff_key($configuration, $bad_keys);
                    $finalValue  = [$this->parseConfiguration($child_configs, $options)];

                    break;
                case 'post_type':

                    if(isset($configuration['relationship'])) {
                        $options['relationship'] = $configuration['relationship']['value'];
                    }

                    $finalValue = $this->handlePostType($configuration, $options);

                    break;

                //these fields are for reference only so skip them
                 case 'acf-relationship':
                 case 'post_field-relationship':
                    continue 2;
                break;

            }

             

            $config = array(
                $key => $configuration
            );

            //needed for filters to work
            //todo: move out of optional
            $options['value'] = $finalValue;

            // any custom handlers
            $finalValue = \BokkaWP\bdx_apply_filters('bdx-adapter-configuration', $config, $options);

            //filter null values
            if($finalValue)
                $array[$key] = $finalValue;


        }

        return count($array) > 0 ? $array : '';

    }


    protected function handlePostType($configuration, $options)
    {

        $defaults = array(
            'parent_post'          => false, //a
            'key'           => '', //key to these confugrations
            'relationship'  => false //refers to acf field with an array of IDs for relationship.
        );

        $options = array_merge($defaults, $options);

        $posts = get_posts(
            array(
                "post_type" => $configuration['post_type']['value'],
                "posts_per_page" => 500
            )
        );

        // todo: move these keys into a better namespace to avoid conflicts in our config
        // setup an array of keys we want to exclude
        $bad_keys = array(
            "type"              =>  false,
            "post_type"         =>  false,
        );

        //gets child configs
        $child_configs = array_diff_key($configuration, $bad_keys);
        $data = [];

        //let's loop through each of our posts and get the required data
        foreach ($posts as $post) {


            $exclude = get_field('exclude_bdx', $post->ID);
            if($exclude)
                continue;

            //for relationship field
            $options['post'] = $post;

            //for
            $new_options['post_type'] = $post->post_type;

            if ($options['relationship']) {

                $options['value'] = get_field(
                    $options['relationship'],
                    $post->ID
                );

                $relationship = \BokkaWP\bdx_apply_filters(
                    'bdx-adapter-relationship-filter',
                    $configuration,
                    $options
                );

                if ($relationship !== true) {
                    continue;
                }

            }

            $new_options['grandparent_post'] = $options['parent_post'];
            $new_options['parent_post'] = $post;
            $new_options = array_merge($options, $new_options);

            //parse child configurations
            $data[] = $this->parseConfiguration($child_configs, $new_options);
        }

        //remove null values
        $values = $data;

        return $values;
    }
}