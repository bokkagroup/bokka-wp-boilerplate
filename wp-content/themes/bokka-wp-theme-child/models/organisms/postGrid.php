<?php
// get custom posts for post grid
if (isset($organism['type']) && $organism['type'] === "post-grid") {
    global $wp_query;
    $post_type = $organism['post_type'];
    $taxonomies = get_object_taxonomies($post_type);
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type'         => $post_type,
        'posts_per_page'    => 9,
        'paged'             => $paged,
    );

    if (is_tax()) {
        $taxonomy = get_query_var('taxonomy');
        $term = get_query_var($taxonomy);

        $args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $term,
        );
    }

    $custom_posts = new WP_Query($args);

    $pagination = paginate_links(array(
        'current' => max(1, get_query_var('paged')),
        'total' => $custom_posts->max_num_pages
    ));

    $organism['pagination'] = $pagination;

    $custom_posts = $custom_posts->get_posts();
    $posts = array_map(function ($post) {
        $terms = wp_get_object_terms($post->ID, get_object_taxonomies($post->post_type));
        if ($terms) {
            $post->category = $terms[0]->name;
        }

        if ($post->post_type == 'testimonial') {
            $post->testimonial = true;
            $feat_img_id = get_post_thumbnail_id($post->ID);
            $post->feat_img = wp_get_attachment_image_src($feat_img_id, 'full')[0];
        }
        
        $post->images = array(
            'tablet' => wp_get_attachment_image_src($post->thumbnail, 'tablet-brand-window')[0],
            'mobile' => wp_get_attachment_image_src($post->thumbnail, 'mobile-brand-window')[0]
        );

        if (!($post->excerpt)) {
            $content = wp_strip_all_tags($post->post_content, true);
            $post->excerpt = limit_words($content, 20) . '...';
        }
        apply_filters('bokkamvc_filter_before_render', $post);
        return $post;
    }, $custom_posts);

    $organism['custom_posts'] = $posts;
}
