<?php

namespace BokkaWP\Theme\models;

class OrganismModel extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $organisms = get_field('organism');
        if (is_array($organisms)) {
            $this->data = array_map(array($this, 'mapData'), $organisms);
        } else {
            $this->data = array();
        }
    }

    /**
     * Loops through each organism and it's children to prepare data for view
     * @param $organism
     * @return array
     */
    public function mapData($organism)
    {
        //setup boolean for basic handlebars if statement
        if (isset($organism['type'])) {
            $type = $organism['type'];
            $organism[$type] = true;
        }

        if (!isset($organism['id']) && isset($organism['type'])) {
            $organism['id'] = $organism['type'];
        }

        if (isset($organism['type']) && (
                $organism['type'] === "feature-slider" ||
                $organism['type'] === "cta-w-gallery"
            )) {
            if (isset($organism['item']) && count($organism['item']) > 1) {
                $organism['controls'] = true;
            }
            if (isset($organism['gallery']) && count($organism['gallery']) > 1) {
                $organism['controls'] = true;
            }
        }

        if (isset($organism['type']) && $organism['type'] === 'cta-w-gallery') {
            $organism['gallery'] = array_map('setSizeMedium', $organism['gallery']);
        }

        //get the image from the ID
        if (isset($organism['image'])) {
            $size = isset($organism['image_size']) ? $organism['image_size'] : 'large';
            $organism['image'] = wp_get_attachment_image_src($organism['image'], $size)[0];
        }

        //get the testimonial from the ID
        if (isset($organism['testimonial_id'])) {
            $name = get_post_meta($organism['testimonial_id'], 'name');
            $organism['testimonial'] = get_object_vars(get_post($organism['testimonial_id']));
            $organism['testimonial']['name'] = count($name) > 0 ? $name[0] : false;
            $organism['testimonial']['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $organism['testimonial_id'] ), 'full' )[0];
        }

        //map children through this function
        if (isset($organism['item']) && $organism['item']) {
            $organism['item'] = array_map(array($this, 'mapData'), $organism['item']);
        }

        if (isset($organism['form']) && $organism['form']) {
            $form = gravity_form($organism['form']['id'], false, false, false, null, $ajax = true, 0, false);
            $organism['form'] = $form;
        }

        return $organism;
    }
}
