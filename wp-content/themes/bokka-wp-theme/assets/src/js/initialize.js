
require('./vendor/mlpushmenu.js')
require('./vendor/mousewheel.js')
require('./vendor/fancybox.js')
require('./vendor/tipr.js')
jQuery( document ).ready(function( $ ) {
    window.$ = jQuery

    $(".fancybox").fancybox();
    $(".modal-trigger").fancybox({
        autoSize:false,
        autoHeight: true,
        maxWidth: "600px",
        maxHeight: '90%',
        helpers: {
            overlay: {
                locked: false
            }
        },

    });
    window.bokka_breakpoint = {
        refreshValue : function () {
            window.bokka_breakpoint.value = window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/\"/g, '');
        }
    }
    window.bokka_breakpoint.refreshValue();

    $('.slider').each(function(){
        var Slider = require('./views/slider.js')
        var slider = new Slider({el:$(this)})
    })

    $('.tabs').each(function(){
        var Tabs = require('./views/tabs.js')
        var Tabs = new Tabs({el:$(this)})
    })

    $('.alert .close').on('click', function(event){
        event.preventDefault()
        $(this).closest('.alert').fadeOut()
    })



    $(".menu-trigger").on('click', function ( event ) {
        event.preventDefault()
        $(this).toggleClass('open')
    });

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

    $('.tooltip').tipr();

    /**
     * Map instatiation
     */
    if($('.google-map').length > 0){
        loadMapsAPI(function() {
            $('.google-map').each(function () {
                if ($(this).hasClass('js-model-map')) {
                    var modelMapView = require('./views/model-map.js')
                    new modelMapView({el: $(this)})
                }
            })
        })
    }



});
