<?php

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
add_action('wp_head', 'requireBugHerd');
function requireBugHerd()
{
    if (BOKKA_ENV == "staging") : ?>
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