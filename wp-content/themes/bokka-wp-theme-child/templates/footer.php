<?php
/**
 * The head for our theme.
 *
 * This is the template closes our html our footer, scripts are injected into here.
 *
 * @package bokka_wp_theme
 */
?>

    <?php new BokkaWP\Theme\controllers\FooterController(); ?>
    </div><!--/site-wrapper-->
    <div id="email-signup-modal" class="modal-content">
        <div class="email-signup">
            <div class="title">Be first to know with our monthly newsletter</div>
            <div class="text">
                <div class="body">
                    <p>At Boulder Creek Neighborhoods, we treat your inbox and your time with respect. Which means we will only send you emails when we have something important and relevant to share, such as a new model previews, exclusive event invitations and important updates about the community and homes.</p>
                    <br>
                    <p>You can expect about 1 email per month, and can easily opt out at any time.</p>

                </div>
            </div>
            <div class="image">
                <img src="/wp-content/themes/bokka-wp-theme-child/assets/build/images/email-preview.jpg" alt="">
            </div>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
