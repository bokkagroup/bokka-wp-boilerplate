/**
 * Swap out image source on window resize
 */
if ($('.responsive-img').length > 0) {
    $('.responsive-img').each(function(index, value) {
        var $this = $(this);
        var $parent = $this.closest('.image');
        var mobileSrc = $this.attr('src');
        var tabletSrc = $this.data('src-tablet');
        var desktopSrc = $this.data('src-desktop');

        function swapSrc() {
            if (bokka.breakpoint.value === 'tablet') {
                if (!Modernizr.objectfit) {
                    $parent
                        .addClass('no-objectfit')
                        .css('background-image', 'url("' + tabletSrc + '");');
                } else {
                    $this.attr('src', tabletSrc).show();
                    $parent.addClass('objectfit');
                }
            } else if (bokka.breakpoint.value === 'desktop') {
                if (!Modernizr.objectfit) {
                    $parent
                        .addClass('no-objectfit')
                        .css('background-image', 'url("' + desktopSrc + '");');
                } else {
                    $this.attr('src', desktopSrc).show();
                    $parent.addClass('objectfit');
                }
            } else {
                if (!Modernizr.objectfit) {
                    $parent
                        .addClass('no-objectfit')
                        .css('background-image', 'url("' + mobileSrc + '");');
                } else {
                    $this.attr('src', mobileSrc).show();
                    $parent.addClass('objectfit');
                }
            }
        }

        swapSrc();

        $(window).on('resize', function () {
            swapSrc();
        });
    });
}
