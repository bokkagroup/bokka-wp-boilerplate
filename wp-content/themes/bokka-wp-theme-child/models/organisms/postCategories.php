<?php
// get categories associated with custom post type
if (isset($organism['type']) && $organism['type'] === "post-grid") {
    $post_type = $organism['post_type'];
    $taxonomies = get_object_taxonomies($post_type);
    if ($taxonomies) {
        $categories = get_terms(array(
            'taxonomy' => $taxonomies
        ));
        
        foreach ($categories as $category) {
            // get permalink
            $term_link = get_term_link($category);
            if (is_wp_error($term_link)) {
                continue;
            }
            $category->term_link = $term_link;

            // check if we're viewing taxonomy archive that matches category
            if (is_tax()) {
                $taxonomy = get_query_var('taxonomy');
                $term = get_query_var($taxonomy);
                if ($category->slug == $term) {
                    $category->current = true;
                }
            }
        }
        $organism['categories'] = $categories;
    }
}
