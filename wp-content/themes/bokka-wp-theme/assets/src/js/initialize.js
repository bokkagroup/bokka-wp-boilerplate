require('./vendor/mlpushmenu.js');
$(document).ready(function() {

    $('.slider').each(function(){
        var Slider = require('./views/slider.js')
        var slider = new Slider({el:$(this)})
    })

    var breakpoint = {
        refreshValue : function () {
            breakpoint.value = window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/\"/g, '');
        }
    }
    breakpoint.refreshValue();

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

    var push = new mlPushMenu(document.getElementById('mp-menu'), $('.menu-trigger'))
    $(window).on('resize', function(){
        push._determineNav()
    })
});
