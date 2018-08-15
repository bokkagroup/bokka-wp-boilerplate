<?php

namespace CatalystWP\AtomChild\models;

class Organisms extends \CatalystWP\Nucleus\Model
{
    public function __construct($options = [])
    {
        parent::__construct($options);
        $post_id = isset($options['post_id']) ? $options['post_id'] : get_the_ID();
        $this->getFields($post_id);
    }

    public function getFields($id)
    {
        $organisms = get_field('organism', $id);
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
            if ($type == 'form-basic' || $type == 'form-w-text' || $type == 'masonry-gallery') {
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
                $organism['type'] === "cta-w-gallery" ||
                $organism['type'] === "slider-gallery" ||
                $organism['type'] === "text-block-w-image"
            )) {
            if (isset($organism['item']) && count($organism['item']) > 1) {
                $organism['controls'] = true;
            }
            if (isset($organism['gallery']) && count($organism['gallery']) > 1) {
                $organism['controls'] = true;
            }
        }

        // get community (including product type data) info for form
        if (isset($organism['type']) && $organism['type'] === 'lp-intro-w-form') {
            if (isset($this->community) && $this->community) {
                $organism['community'] = $this->community[0];
                $types = get_post_meta($organism['community']->ID, 'types');

                if ($types) {
                    $organism['community_types'] = explode(',', $types[0]);
                }
            }
        }

        //get image sizes for our CTA gallery
        if (isset($organism['type']) && $organism['type'] === 'cta-w-gallery') {
            $organism['gallery'] = array_map('setSizeMedium', $organism['gallery']);
        }

        //get image data for masonry gallery
        if (isset($organism['type']) && $organism['type'] === 'masonry-gallery') {
            $organism['gallery_items'] = prepare_masonry_gallery_data($organism['gallery_items']);
        }

        //get QMI data for product organism
        if ((isset($organism['type']) && $organism['type'] === 'qmi-product') && !empty($organism['communities'])) {
            $args = array(
                'posts_per_page' => 500,
                'post_type' => 'home',
                'meta_query' => array(
                    array(
                        'key' => 'neighborhood',
                        'value' => $organism['communities'],
                        'compare' => 'IN'
                    )
                ),
                'orderby' => 'title',
                'order' => 'ASC'
            );
            $posts = new \WP_Query($args);

            $qmi_product = applyFiltersToProducts($posts->posts);

            //set neighborhood name
            $qmi_product = array_map(function ($post) {
                $neighborhood = get_post(get_post_meta($post->ID, 'neighborhood')[0]);
                $post->neighborhood = $neighborhood->post_title;
                return $post;
            }, $qmi_product);

            $organism['qmi_product'] = $qmi_product;
        }

        // get layout type and images for secondary brand window
        if (isset($organism['background_image'])) {
            $organism['images'] = array(
                'responsive' => wp_get_attachment_image($organism['background_image'], 'full'),
                'full' => wp_get_attachment_image_src($organism['background_image'], 'full-brand-window')[0],
                'full-split' => wp_get_attachment_image_src($organism['background_image'], 'full-split-brand-window')[0],
                'tablet' => wp_get_attachment_image_src($organism['background_image'], 'tablet-brand-window')[0],
                'mobile' => wp_get_attachment_image_src($organism['background_image'], 'mobile-brand-window')[0]
            );
        }

        // get image caption
        if (isset($organism['image']) && isset($organism['type']) && $organism['type'] === 'text-block-w-image') {
            $image_post = get_post($organism['image']);
            $organism['caption'] = $image_post->post_excerpt;
        }

        //get image urls for image fields (id)
        if (isset($organism['image'])) {
            $image_id = $organism['image'];
            $size = isset($organism['image_size']) ? $organism['image_size'] : 'large';
            $organism['image'] = wp_get_attachment_image_src($image_id, $size)[0];
        }

        if (isset($organism['inset_image'])) {
            $organism['inset_image'] = wp_get_attachment_image_src($organism['inset_image'], 'full')[0];
        }

        //get testimonials from array of IDs
        if (isset($organism['testimonial_ids']) && isset($organism['type']) && $organism['type'] === 'testimonial') {
            $testimonials = array_map(function($testimonial_id) {
                $name = get_post_meta($testimonial_id, 'name');
                $excerpt = get_post_meta($testimonial_id, 'excerpt');
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($testimonial_id), 'full')[0];
                $testimonial = array(
                    'name' => count($name) > 0 ? $name[0] : false,
                    'excerpt' => count($excerpt) > 0 ? $excerpt[0] : false,
                    'image' => $image
                );
                return $testimonial;
            }, $organism['testimonial_ids']);
            $organism['testimonials'] = $testimonials;
        }

        //recursively call this function on child items
        if (isset($organism['item']) && $organism['item']) {
            /*TODO: There is an issue with fields being added to organisms that don't accept those organisms
            I think this is an issue with ACF
            for now I'm unsetting those fields so they dont show up inappropriately
            */
            if ($organism['type'] === "feature-slider") {
                $organism['item'] = $organism['item'] = array_map(function ($item) {
                    if (isset($item['description'])) {
                        unset($item['description']);
                    }
                    return $item;
                }, $organism['item']);
            }
            /*TODO: There is an issue with fields being added to organisms that don't accept those organisms
            I think this is an issue with ACF
            for now I'm unsetting those fields so they dont show up inappropriately
            */
            if ($organism['type'] === "cards") {
                $organism['item'] = $organism['item'] = array_map(function ($item) {
                    if (isset($item['sub_title'])) {
                        unset($item['sub_title']);
                    }
                    return $item;
                }, $organism['item']);
            }


            $organism['item'] = array_map(array($this, 'mapData'), $organism['item']);
        }

        //get gravity forms
        if (isset($organism['gform']) && $organism['gform']) {
            $form = gravity_form($organism['gform']['id'], false, false, false, null, $ajax = true, 0, false);
            $organism['gform'] = $form;
        }

        if (isset($organism['form']) && $organism['form']) {
            $form = gravity_form($organism['form']['id'], false, false, false, null, $ajax = true, 0, false);
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

        // setup data for circles-w-color-block-text
        if (isset($organism['type']) && $organism['type'] == 'circles-w-color-block-text') {
            $this->count = 1;
            $organism['item'] = array_map(function ($item) {
                $item['id'] = 'color-block-' . $this->count;
                $this->count++;
                return $item;
            }, $organism['item']);
        }

        // image alignment options
        if (isset($organism['image_alignment']) && $organism['image_alignment']) {
            if ($organism['image_alignment'] === 'right') {
                $organism['align_image_right'] = true;
            } else {
                $organism['align_image_left'] = true;
            }
        }

        require('organisms/eventsArchive.php');
        require('organisms/postGrid.php');
        require('organisms/postCategories.php');

        return $organism;
    }
}
