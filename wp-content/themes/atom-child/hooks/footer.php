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


add_action('wp_footer', 'requireLivePersonTag');
function requireLivePersonTag()
{
    ?>
       <!-- BEGIN LivePerson Monitor. -->
        <script type="text/javascript">window.lpTag=window.lpTag||{},"undefined"==typeof window.lpTag._tagCount?(window.lpTag={site:'17360176'||"",section:lpTag.section||"",tagletSection:lpTag.tagletSection||null,autoStart:lpTag.autoStart!==!1,ovr:lpTag.ovr||{},_v:"1.8.0",_tagCount:1,protocol:"https:",events:{bind:function(t,e,i){lpTag.defer(function(){lpTag.events.bind(t,e,i)},0)},trigger:function(t,e,i){lpTag.defer(function(){lpTag.events.trigger(t,e,i)},1)}},defer:function(t,e){0==e?(this._defB=this._defB||[],this._defB.push(t)):1==e?(this._defT=this._defT||[],this._defT.push(t)):(this._defL=this._defL||[],this._defL.push(t))},load:function(t,e,i){var n=this;setTimeout(function(){n._load(t,e,i)},0)},_load:function(t,e,i){var n=t;t||(n=this.protocol+"//"+(this.ovr&&this.ovr.domain?this.ovr.domain:"lptag.liveperson.net")+"/tag/tag.js?site="+this.site);var a=document.createElement("script");a.setAttribute("charset",e?e:"UTF-8"),i&&a.setAttribute("id",i),a.setAttribute("src",n),document.getElementsByTagName("head").item(0).appendChild(a)},init:function(){this._timing=this._timing||{},this._timing.start=(new Date).getTime();var t=this;window.attachEvent?window.attachEvent("onload",function(){t._domReady("domReady")}):(window.addEventListener("DOMContentLoaded",function(){t._domReady("contReady")},!1),window.addEventListener("load",function(){t._domReady("domReady")},!1)),"undefined"==typeof window._lptStop&&this.load()},start:function(){this.autoStart=!0},_domReady:function(t){this.isDom||(this.isDom=!0,this.events.trigger("LPT","DOM_READY",{t:t})),this._timing[t]=(new Date).getTime()},vars:lpTag.vars||[],dbs:lpTag.dbs||[],ctn:lpTag.ctn||[],sdes:lpTag.sdes||[],hooks:lpTag.hooks||[],ev:lpTag.ev||[]},lpTag.init()):window.lpTag._tagCount+=1;</script>
        <!-- END LivePerson Monitor. -->
    <?php
}
