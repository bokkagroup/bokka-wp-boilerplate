
require('./helpers/breakpoint')


jQuery( document ).ready(function( $ ) {
    window.$ = jQuery

    if($('.slider').length > 0) {
        var Slider = require('./views/slider.js')
        console.log(Slider);
        //initialize sliders
        $('.slider').each(function () {
            new Slider({el: $(this)})
        })
    }

    if($('.tabs').length > 0) {
        var Tabs = require('./views/tabs.js')
        console.log(Tabs);
        $('.tabs').each(function () {
            new Tabs({el: $(this)})
        })
    }
})


