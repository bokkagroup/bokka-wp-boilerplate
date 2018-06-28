
        <?php
        global $wp_query;
        if (is_front_page()) {
            new \CatalystWP\AtomChild\controllers\HomeController();
        } elseif (is_page(181)) {
            new \CatalystWP\AtomChild\controllers\StyleguideController();
        } elseif (is_page('ask-a-question')) {
            new \CatalystWP\AtomChild\controllers\ContactUsController();
        } elseif (is_page(2785)) {
            new \CatalystWP\AtomChild\controllers\TeamController();
        } elseif (is_singular('communities')) {
            new \CatalystWP\AtomChild\controllers\NeighborhoodsController();
        } elseif (is_singular('plans')) {
            global $post;
            new \CatalystWP\AtomChild\controllers\FloorplanController(["post_id" => $post->ID]);
        } elseif (is_post_type_archive('plans')) {
            new \CatalystWP\AtomChild\controllers\FloorplansController();
        } elseif (is_singular('model')) {
            new \CatalystWP\AtomChild\controllers\ModelDetailController();
        } elseif (is_singular('home')) {
            new \CatalystWP\AtomChild\controllers\HomesController();
        } elseif (is_page('our-neighborhoods')) {
            new \CatalystWP\AtomChild\controllers\NeighborhoodsOvController();
        } elseif (is_page('quick-move-in-homes')) {
            new \CatalystWP\AtomChild\controllers\QuickMoveInHomesController();
        } elseif (is_page('model-homes')) {
            new \CatalystWP\AtomChild\controllers\ModelHomesController();
        } elseif (is_page('our-locations')) {
            new \CatalystWP\AtomChild\controllers\OrganismsController();
            new \CatalystWP\AtomChild\controllers\LocationsController();
        } elseif (is_page()) {
            new \CatalystWP\AtomChild\controllers\OrganismsController();
        } elseif (is_post_type_archive('blog-post') ||
            is_tax('blog-post-category') ||
            is_post_type_archive('career') ||
            is_tax('career-category') ||
            is_tax('event-category') ||
            is_post_type_archive('testimonial') ||
            is_tax('testimonial-category')) {
            new \CatalystWP\AtomChild\controllers\CustomPostArchiveController();
        } elseif (is_post_type_archive('event') ||
            is_tax('event-category')) {
            new \CatalystWP\AtomChild\controllers\EventArchiveController();
        } elseif (is_tax()) {
        } elseif (is_archive()) {
        } elseif (is_singular(array('blog-post', 'career', 'testimonial'))) {
            new \CatalystWP\AtomChild\controllers\CustomPostSingleController();
        } elseif (is_singular(array('event'))) {
            global $post;
            new \CatalystWP\AtomChild\controllers\EventSingleController(["post_id" => $post->ID]);
        } elseif (is_single()) {
        } elseif (is_category()) {
        } elseif (is_404()) {
            new \CatalystWP\AtomChild\controllers\PageNotFoundController();
        } else {
        }
        //Determine which controller to use
        ?>

