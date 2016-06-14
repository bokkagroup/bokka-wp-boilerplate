require('./vendor/mlpushmenu.js')
require('./vendor/mousewheel.js')
require('./vendor/fancybox.js')
require('./vendor/tipr.js')
jQuery( document ).ready(function( $ ) {
    window.$ = jQuery

    //activate fancybox
    $(".fancybox-class").fancybox();
    $(".modal-trigger").fancybox({

        autoSize: false,
        maxWidth: '85%',
        maxHeight: '90%',
        helpers: {
            overlay: {
                locked: false
            }
        },
    });

    //Setup breakpoint value we can query throughout our app
    window.bokka_breakpoint = {
        refreshValue : function () {
            window.bokka_breakpoint.value = window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/\"/g, '');
        }
    }
    window.bokka_breakpoint.refreshValue();


    //initialize sliders
    $('.slider').each(function(){
        var Slider = require('./views/slider.js')
        var slider = new Slider({el:$(this)})
    })

    $('.js-page-jump').on('click', function(event){
        event.preventDefault()
        var selector = $(this).attr('href')
        $('html, body').animate({
            scrollTop: $(selector).offset().top
        }, 250)
    })

    $('.tabs').each(function(){
        var Tabs = require('./views/tabs.js')
        var Tabs = new Tabs({el:$(this)})
    })

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

    /**
     * Global Helpers
     */
    //Event Tracking
    require('./helpers/eventTracking.js')
    require('./helpers/maps.js')

    /**
     * Modals
     */
    //require('./helpers/modals.js')

    /**
     * Menu Instantiation
     * @type {*|mlPushMenu}
     */
    var push = new mlPushMenu(document.getElementById('mp-menu'), $('.menu-trigger'))
    $(window).on('resize', function(){
        push._determineNav()
    });

    /**
     * Map instatiation
     */
    if($('.google-map').length > 0){
        loadMapsAPI(function() {
            $('.google-map-wrapper').each(function () {
                if ($(this).hasClass('google-map-wrapper')) {
                    var modelMapView = require('./views/model-map.js')
                    new modelMapView({el: $(this)})
                }
            })
        })
    }
});
