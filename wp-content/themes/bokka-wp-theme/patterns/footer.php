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
    <?php wp_footer(); ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-5740821-5', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>
