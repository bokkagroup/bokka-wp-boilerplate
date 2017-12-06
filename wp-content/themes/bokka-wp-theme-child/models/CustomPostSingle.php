<?php

namespace BokkaWP\Theme\models;

class CustomPostSingle extends \BokkaWP\MVC\Model
{
    public function initialize()
    {
        global $post;
        $this->setImages($post, 946);
        $this->setTerms($post);
        $this->setDate($post);
        $this->setContent($post);
        $this->setShareShortcode($post);

        if ($post->post_type == 'testimonial') {
            $this->setTestimonial($post);
        }

        $this->data = $post;
    }

    private function setImages($post, $fallback_id)
    {
        $post->images = array(
            'responsive' => wp_get_attachment_image($post->image, 'full'),
            'full' => wp_get_attachment_image_src($post->image, 'full-brand-window')[0],
            'tablet' => wp_get_attachment_image_src($post->image, 'tablet-brand-window')[0],
            'mobile' => wp_get_attachment_image_src($post->image, 'mobile-brand-window')[0],
            'fallback-full' => wp_get_attachment_image_src($fallback_id, 'full-brand-window')[0],
            'fallback-tablet' => wp_get_attachment_image_src($fallback_id, 'tablet-brand-window')[0]
        );
    }

    private function setDate($post)
    {
        $post->date = get_the_date('F j, Y');
    }

    private function setTerms($post)
    {
        $terms = wp_get_object_terms($post->ID, get_object_taxonomies($post->post_type));
        if ($terms) {
            $post->term = $terms[0]->name;
            $post->term_link = get_term_link($terms[0]);
        }
    }

    private function setContent($post)
    {
        $post->post_content = apply_filters('the_content', $post->post_content);
    }

    private function setTestimonial($post)
    {
        $post->testimonial = true;
        $feat_img_id = get_post_thumbnail_id($post->ID);
        $post->feat_img = wp_get_attachment_image_src($feat_img_id, 'full')[0];
    }

    private function setShareShortcode($post)
    {
        $post->share = do_shortcode('[addtoany]');
    }
}
