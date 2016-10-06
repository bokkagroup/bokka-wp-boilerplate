window.bokka = require('./helpers/bokka');

jQuery( document ).ready(function( $ ) {
    window.$ = jQuery

    // Initialize bokka helper functions
    bokka.init();

    if($('.slider').length > 0) {
        var Slider = require('./views/slider.js')
        //initialize sliders
        $('.slider').each(function () {
            new Slider({el: $(this)})
        })
    }

    if($('.tabs').length > 0) {
        var Tabs = require('./views/tabs.js')
        $('.tabs').each(function () {
            new Tabs({el: $(this)})
        })
    }
});
