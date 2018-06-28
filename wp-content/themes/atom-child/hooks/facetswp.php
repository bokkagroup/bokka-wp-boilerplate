<?php

/**
 * Floorplans list
 */
add_filter('facetwp_template_html', function($output, $class) {
    if ($class->template['name'] == 'floorplans') {
        $posts = array();
        $unfiltered = applyFiltersToProducts($class->query->posts);
        foreach ($unfiltered as $post) {
            $neighborhood = get_field('neighborhood', $post->ID);
            $posts['neighborhoods'][$neighborhood]['title']= get_the_title($neighborhood);
            $posts['neighborhoods'][$neighborhood]['city']= get_field('city', $neighborhood);
            $posts['neighborhoods'][$neighborhood]['state']= get_field('state', $neighborhood);
            $posts['neighborhoods'][$neighborhood]['plans'][] = $post;
        }
        sort($posts['neighborhoods']);
        $view = new \CatalystWP\AtomChild\views\FloorplansListView();
        return $view->render($posts);
    }
}, 10, 2);


/**
 * hide count on dropdowns
 */
add_filter( 'facetwp_facet_dropdown_show_counts', '__return_false' );


/**
 * only ever show total of current floorplans
 */
add_filter( 'facetwp_result_count', function( $output, $params ) {
    return $params['total'];
}, 10, 2 );