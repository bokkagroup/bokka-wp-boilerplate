<?php
get_template_part('templates/head');
get_template_part('templates/header-campaigns');
do_action('bwt\before_content'); ?>

    <div id="content" class="content">
        <?php
        global $wp_query;
        new \BokkaWP\Theme\controllers\CampaignController();
        ?>
    </div><!--/content-->

<?php do_action('bwt\after_content');
get_template_part('templates/footer-campaigns');
