require("!modernizr!./.modernizrrc")
require('./vendor/mlpushmenu.js')
require('./vendor/mousewheel.js')
require('./vendor/fancybox.js')
require('./vendor/tipr.js')


jQuery( document ).ready(function($) {
    window.$ = jQuery

    if($('.tabs').length > 0) {
        var Tabs = require('./views/tabs.js')
        $('.tabs').each(function () {
            new Tabs({el: $(this)})
        })
    }

    /**
     * Global Helpers
     */
    //Event Tracking
    require('./helpers/eventTracking.js')
    require('./helpers/maps.js')
    require('./helpers/UTMStringHandler')
    require('./helpers/responsiveImages')
    require('./helpers/forms')

    $(".fancybox-masonry").fancybox({
        openEffect	: 'none',
        closeEffect	: 'none',
        autoSize: false,
        maxWidth: '85%',
        maxHeight: '90%',
        helpers: {
            overlay: {
                locked: false
            }
        },
        tpl : {
            closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon icon-exit-circle" href="javascript:;"></a>',
        }
    });


    //activate fancybox
    $(".fancybox-class").fancybox();
    $(".modal-trigger").fancybox({
        autoSize: false,
        height: 'auto',
        maxWidth: '85%',
        maxHeight: '90%',
        helpers: {
            overlay: {
                locked: false
            }
        },
        tpl : {
            closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon icon-exit-circle" href="javascript:;"></a>',
        }
    });

    $('.js-page-jump').on('click', function(event){
        event.preventDefault()
        var selector = $(this).attr('href')
        $('html, body').animate({
            scrollTop: $(selector).offset().top
        }, 250)
    })

    // Add "What's this?" link to VIP signup Gravity Form label
    $('.vip-list-signup .ginput_container > ul > li label').append('&nbsp;<span><a href="#email-signup-modal" data-modal="email-signup" class="modal-trigger">what\'s this?</a></span>');

    //Initialize tooltips
    $('.tooltip').tipr();

    //alerts functionality (need to move this somewhere)
    $('.alert .close').on('click', function(event){
        event.preventDefault()
        $(this).closest('.alert').fadeOut()
    })


    //Toggle menu stuff (need to move into menu)
    $(".menu-trigger").on('click', function ( event ) {
        event.preventDefault()
        $(this).toggleClass('open')
    });

    //Neat animation stuff
    $('.brand-window, .intro-text').each(function (parent_index) {
        $(this).find('.image, .title,.body,.button, h1').each(function (index) {
            $(this).css({opacity: 0, top: -25});
            $(this).delay(( 150 * index)).animate({opacity: 1, top: 0}, 500);
        });
    });

    // Toggle overview map filters
    $('.filter-toggle').on('click', function(event) {
        event.preventDefault();
        $(this).siblings('.filters-wrap').toggleClass('open');
    });


    /**
     * Menu Instantiation
     * @type {*|mlPushMenu}
     */
    var push = new mlPushMenu(document.getElementById('mp-menu'), $('.menu-trigger'))
    $(window).on('resize', function(){
        push._determineNav();
    });

    /**
     * Map instatiation
     */

    if($('.google-map').length > 0){

        loadMapsAPI(function() {

            $('.google-map-wrapper').each(function () {
                if ($(this).hasClass('coming-soon')) {
                    var comingSoonMapView = require('./views/coming-soon-map.js');
                    new comingSoonMapView({el: $(this)});
                } else if ($(this).hasClass('overview-map')) {
                    var wrapper = $(this).closest('.section');
                    var overviewMapView = require('./views/overview-map.js');
                    new overviewMapView({el: wrapper})
                    Backbone.history.start({pushState: true});
                } else if ($(this).hasClass('google-map-wrapper')) {
                    var modelMapView = require('./views/model-map.js');
                    new modelMapView({el: $(this)});
                }
            });
        });
    }

    /**
     * Open external links in new tab
     */
    $('a').each(function() {
        if (!$(this).hasClass('fancybox-masonry')) {
            var a = new RegExp('/' + window.location.host + '/');
            if(!a.test(this.href)) {
                $(this).click(function(event) {
                    event.preventDefault();

                    window.open(this.href, '_blank');
                });
            }
        }
    });

    /**
     * Init Masonry Gallery
     */
    if ($('.masonry-gallery').length > 0) {
        var MasonView = require('./views/masonry-gallery');
        $('.masonry-gallery').each(function(){
            new MasonView({el: $(this)});
        });
    }

    /**
     * Accordion
     */
    $('.accordion .title').on('click', function (event) {
        $(this).toggleClass('active')
    });
});
