<?php

/**
 * Vendor tracking scripts
 */

add_action('wp_footer', 'requireTradeDeskTrackingPixel');
function requireTradeDeskTrackingPixel()
{
    echo '<div class="footer-pixel"><script type="text/javascript"> var __cho__ = {"data":{"boulder_creek_site":"[BOULDER_CREEK_SITE_VALUE]"},"pid":3326}; (function() { var c = document.createElement(\'script\'); c.type = \'text/javascript\'; c.async = true; c.src = document.location.protocol + \'//cc.chango.com/static/o.js\'; var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(c, s); })(); </script>',
        '<script type="text/javascript"> var __cho__ = {"data":{"boulder_creek_search":"[BOULDER_CREEK_SEARCH_VALUE]"},"pid":3326}; (function() { var c = document.createElement(\'script\'); c.type = \'text/javascript\'; c.async = true; c.src = document.location.protocol + \'//cc.chango.com/static/o.js\'; var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(c, s); })(); </script>',
        '<script async src="//16736.tctm.co/t.js"></script>',
        '<iframe width="0" height="0" name="Trade Desk Tracking - Live Boulder Creek Retargeting" frameborder="0" scrolling="no" src="//insight.adsrvr.org/tags/xbmwi3b/h9cefh41/iframe"></iframe></div>';
}

add_action('wp_footer', 'requireResonateTrackingPixel');
function requireResonateTrackingPixel()
{
    ?>
        <div class="footer-pixel"><script language='JavaScript1.1' src='//pixel.mathtag.com/event/js?mt_id=1094385&mt_adid=176173&v1=&v2=&v3=&s1=&s2=&s3='></script></div>
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