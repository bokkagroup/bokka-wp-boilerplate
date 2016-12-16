<?php

namespace BokkaWP\Theme\models;

class CustomPostSingle extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $post->images = array(
            'full' => wp_get_attachment_image_src($post->image, 'full-brand-window')[0],
            'tablet' => wp_get_attachment_image_src($post->image, 'tablet-brand-window')[0],
            'mobile' => wp_get_attachment_image_src($post->image, 'mobile-brand-window')[0],
            'fallback-full' => wp_get_attachment_image_src(946, 'full-brand-window')[0],
            'fallback-tablet' => wp_get_attachment_image_src(946, 'tablet-brand-window')[0]
        );

        $terms = wp_get_object_terms($post->ID, get_object_taxonomies($post->post_type));
        if ($terms) {
            $post->term = $terms[0]->name;
            $post->term_link = get_term_link($terms[0]);
        }

        if ($post->post_type == 'testimonial') {
            $post->testimonial = true;
            $feat_img_id = get_post_thumbnail_id($post->ID);
            $post->feat_img = wp_get_attachment_image_src($feat_img_id, 'full')[0];
        }

        $post->post_content = apply_filters('the_content', $post->post_content);
        $post->date = get_the_date('F j, Y');
        $post->share = do_shortcode('[addtoany]');
        $this->data = $post;
    }
}
