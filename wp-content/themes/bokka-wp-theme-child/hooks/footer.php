<?php

/**
 * Vendor tracking scripts
 */

add_action('wp_footer', 'requireReachLocalTracking');
function requireReachLocalTracking()
{
    ?>
        <script type="text/javascript">var rl_siteid = "a11cbb79-00af-4a1a-855a-771ac2b55312";</script><script type="text/javascript" src="//cdn.rlets.com/capture_static/mms/mms.js" async="async"></script>
    <?php
}

add_action('wp_footer', 'requireResonateTrackingPixel');
function requireResonateTrackingPixel()
{
    ?>
        <div class="footer-pixel"><script language='JavaScript1.1' src='//pixel.mathtag.com/event/js?mt_id=1094385&mt_adid=176173&v1=&v2=&v3=&s1=&s2=&s3='></script></div>
    <?php
}

add_action('wp_footer', 'requireGoogleTagManager');
function requireGoogleTagManager()
{
    ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-MG7X76');</script>
        <!-- End Google Tag Manager -->
    <?php
}

add_action('wp_footer', 'requireGoogleTagManagerFallback');
function requireGoogleTagManagerFallback()
{
    ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MG7X76"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    <?php
}

add_action('wp_footer', 'requireHatchbuckTrackingPixel');
function requireHatchbuckTrackingPixel()
{
    ?>
        <script>(function (){var url = window.location; var oImg = document.createElement("img");oImg.setAttribute('src','https://app.hatchbuck.com/TrackWebPage?ACID=1988&URL=' + url);})(); </script>
    <?php
}

add_action('wp_footer', 'requireGooglAnalyticsTracking');
function requireGooglAnalyticsTracking()
{
    ?>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-5740821-1', 'auto');
            ga('send', 'pageview');
        </script>
    <?php
}
