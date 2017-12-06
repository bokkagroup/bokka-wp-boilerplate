require("!modernizr!./.modernizrrc")
require('./vendor/mlpushmenu.js')
require('./vendor/mousewheel.js')
require('./vendor/fancybox.js')
require('./vendor/tipr.js')

var slick = require('slick-carousel');

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
    require('./utility/eventAggregator')

    //Event Tracking
    require('./helpers/eventTracking.js')
    require('./helpers/UTMStringHandler')

    //Gmaps functionality
    require('./helpers/maps.js')

    //init + helpers for gravity forms
    require('./helpers/gforms');
    require('./helpers/forms');

    //helpers for modals
    require('./helpers/modals')

    require('./helpers/toggleMenu');

    /**
     * Brand window slider
     */
    $('.bw-slider').slick({
        slide: '.slide',
        infinite: true,
        prevArrow: '<a href="#" class="slick-prev"><span class="icon icon-slider-previous"></span></a>',
        nextArrow: '<a href="#" class="slick-next"><span class="icon icon-slider-next"></span></a>'
    });

    $('.qmi-product .product-listing').slick({
        arrows: true,
        slide: '.product-item',
        slidesToShow: 1,
        infinite: true,
        prevArrow: '<a href="#" class="slick-prev"><span class="icon icon-slider-previous"></span></a>',
        nextArrow: '<a href="#" class="slick-next"><span class="icon icon-slider-next"></span></a>',
        mobileFirst: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    arrows: true,
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    arrows: true,
                    slidesToShow: 4
                }
            }
        ]
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

    // Prevent clicks on color block text without a specified destination
    if ($('.circles-w-color-block-text').length > 0) {
        $(".color-block-text .text a[href='#']").on('click', function (event) {
            event.preventDefault();
        });
    }

    if ($('body').hasClass('single-campaigns')) {
        $('.logo').on('click', function(event) {
            event.preventDefault();
        });
    }

    /**
     * Menu Instantiation
     * @type {*|mlPushMenu}
     */

    var windowWidth = $(window).width();

    if ($('.mp-menu').length > 0) {
        var push = new mlPushMenu(document.getElementById('mp-menu'), $('.menu-trigger'))

        $(window).resize(function(){
            if ($(window).width() != windowWidth) {
                windowWidth = $(window).width();
                push._determineNav();
            }
        });
    }

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
                } else if ($(this).hasClass('locations-map')) {
                    var wrapper = $(this).closest('.section');
                    var locationsMapView = require('./views/locations-map.js');
                    new locationsMapView({el: wrapper})
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
    $('a:not([href^=tel]):not([href^=mailto])').each(function() {
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
     * Accordion
     */
    $('.accordion .title').on('click', function (event) {
        $(this).toggleClass('active')
    });

    /**
     * Post Grid Masonry
     */
    // masonry / imagesloaded
    if ($('.grid').length > 0) {
        var Masonry = require('./vendor/masonry.js')
        var imagesLoaded = require('./vendor/imagesloaded.pkgd.js')
        imagesLoaded.makeJQueryPlugin($)

        $('.grid').each(function(i, container) {
            // separate jQuery object for each element
            var $container = $(container);
            var msnry = new Masonry(container, {
                itemSelector: '.item',
                columnWidth: '.grid-sizer',
                gutter: 30
            });
            // check if images are loaded
            $container.imagesLoaded(function() {
                // trigger masonry when element has loaded images
                msnry.layout();
            });
          });
    }
});
