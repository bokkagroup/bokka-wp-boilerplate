<?php
/**
 * The head for our theme.
 *
 * This is the template closes our html our footer, scripts are injected into here.
 *
 * @package bokka_wp_theme
 */

global $post;
new CatalystWP\AtomChild\controllers\CampaignFooterController(["post_id" => $post->ID]); ?>
</div><!--/site-wrapper-->
<?php wp_footer(); ?>
</body>
</html>
