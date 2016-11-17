<?php

namespace BokkaWP\Theme\models;

class Organisms extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        $organisms = get_field('organism');
        if (is_array($organisms)) {
            //recursively loop through our array
            $this->data = array_map(array($this, 'mapData'), $organisms);
        } else {
            $this->data = array();
        }
    }

    /**
     * Provides various formatting to our organisms and then recursively calls itself on the organism
     * does things like gets images form their ID, generates Gform data via it's id, so on and so fourth
     * very similar to our viewFilters
     * @param $organism
     * @return array
     */
    public function mapData($organism)
    {

        //setup boolean for basic handlebars if statement
        if (isset($organism['type'])) {
            $type = $organism['type'];

            // Reassign form data to $organism['gform']
            if ($type == 'form-basic' || $type == 'form-w-text') {
                $organism['gform'] = $organism['form'];
            }

            $organism[$type] = true;
        }

        //set a default id
        if (!isset($organism['id']) && isset($organism['type'])) {
            $organism['id'] = $organism['type'];
        }

        //only show controls if there are greater 1 items
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

        //get image sizes for our CTA gallery
        if (isset($organism['type']) && $organism['type'] === 'cta-w-gallery') {
            $organism['gallery'] = array_map('setSizeMedium', $organism['gallery']);
        }

        // get layout type and images for secondary brand window
        if (isset($organism['background_image'])) {
            $organism['images'] = array(
                'full' => wp_get_attachment_image_src($organism['background_image'], 'full-brand-window')[0],
                'full-split' => wp_get_attachment_image_src($organism['background_image'], 'full-split-brand-window')[0],
                'tablet' => wp_get_attachment_image_src($organism['background_image'], 'tablet-brand-window')[0],
                'mobile' => wp_get_attachment_image_src($organism['background_image'], 'mobile-brand-window')[0]
            );
        }

        //get image urls for image fields (id)
        if (isset($organism['image'])) {
            $size = isset($organism['image_size']) ? $organism['image_size'] : 'large';
            $organism['image'] = wp_get_attachment_image_src($organism['image'], $size)[0];
        }

        //get the testimonial from the ID
        if (isset($organism['testimonial_id'])) {
            $name = get_post_meta($organism['testimonial_id'], 'name');
            $organism['testimonial'] = get_object_vars(get_post($organism['testimonial_id']));
            $organism['testimonial']['name'] = count($name) > 0 ? $name[0] : false;
            $organism['testimonial']['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($organism['testimonial_id']), 'full')[0];
        }

        //recursively call this function on child items
        if (isset($organism['item']) && $organism['item']) {
            $organism['item'] = array_map(array($this, 'mapData'), $organism['item']);
        }

        //get gravity forms
        if (isset($organism['gform']) && $organism['gform']) {
            $form = gravity_form($organism['gform']['id'], false, false, false, null, $ajax = true, 0, false);
            $organism['gform'] = $form;
        }

        // get class name and boolean for contact modules
        if (isset($organism['contact_module']) && $organism['contact_module']) {
            $organism['contact_module'] = array_map(function ($module) {
                $class = strtolower(implode('-', explode(' ', $module['title'])));
                $module['class'] = $class;
                $module[$class] = true;
                return $module;
            }, $organism['contact_module']);
        }

        return $organism;
    }
}
