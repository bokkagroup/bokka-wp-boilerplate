<?php

/**
 * Vendor tracking scripts
 */

// Disabling Reach Local tracking pre-launch due to footer form issues
// add_action('wp_head', 'requireReachLocalTracking');
function requireReachLocalTracking()
{
    ?>
        <script type="text/javascript">var rl_siteid = "a11cbb79-00af-4a1a-855a-771ac2b55312";</script><script type="text/javascript" src="//cdn.rlets.com/capture_static/mms/mms.js" async="async"></script>
    <?php
}

add_action('wp_head', 'requireStrategusTracking');
function requireStrategusTracking()
{
    ?>
        <script type="text/javascript" src="//nexus.ensighten.com/milestds/bouldercreekneighborhoods/Bootstrap.js"></script>
    <?php
}

add_action('wp_head', 'requireGoogleTagManager');
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

/**
 * Development and feedback tools
 */

add_action('wp_head', 'requireLiveReload');
function requireLiveReload()
{
    if (BOKKA_ENV === "local") {
        echo '<script src="http://localhost:35729/livereload.js?snipver=1"></script>';
    }
}

// Disabling BugHerd pre-launch, we can re-enable specifically for staging post-launch
// add_action('wp_head', 'requireBugHerd');
function requireBugHerd()
{
    if (BOKKA_ENV !== "local") : ?>
        <!-- BugHerd feedback -->
        <script type='text/javascript'>
            (function (d, t) {
                var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
                bh.type = 'text/javascript';
                bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=cxvx4zwdaisujxj5behrng';
                s.parentNode.insertBefore(bh, s);
            })(document, 'script');
        </script>
        <!-- BugHerd feedback -->
        <?php
    endif;
}