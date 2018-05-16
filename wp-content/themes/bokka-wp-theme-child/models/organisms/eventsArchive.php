<?php

if (isset($organism['type']) && $organism['type'] === "events-archive") {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $now = date('Y-m-d');
    $args = array(
        'post_type'         => 'event',
        'posts_per_page'    => 9,
        'paged'             => $paged,
        'orderby'           => 'meta_value',
        'meta_key'          => 'start_date_time',
        'order'             => 'ASC',
        'meta_query'        => array(
            array(
                'key'       => 'end_date_time',
                'value'     => $now,
                'compare'   => '>=',
                'type'      => 'DATE'
            )
        )
    );
    $posts_query = new \WP_Query($args);

    $pagination = paginate_links(array(
        'current' => max(1, $paged),
        'total' => $posts_query->max_num_pages
    ));
    $organism['pagination'] = $pagination;

    $posts = $posts_query->get_posts();
    $posts = array_map(function ($post) {
        $post->date = getEventDate($post, 'M j, Y');

        $summary = get_field('event_summary', $post->ID);
        if ($summary) {
            $post->excerpt = $summary;
        }

        $post->images = array(
            'tablet' => wp_get_attachment_image_src($post->thumbnail, 'tablet-brand-window')[0],
            'mobile' => wp_get_attachment_image_src($post->thumbnail, 'mobile-brand-window')[0]
        );

        $neighborhood_id = get_field('neighborhood', $post->ID);

        if (isset($neighborhood_id[0])) {
            $neighborhood = get_post($neighborhood_id[0]);
            $post->neighborhood = $neighborhood;
        }

        if (!($post->excerpt)) {
            $content = wp_strip_all_tags($post->post_content, true);
            $post->excerpt = limit_words($content, 20) . '...';
        }

        apply_filters('bokkamvc_filter_before_render', $post);
        return $post;
    }, $posts);

    $organism['posts'] = $posts;
}
