//Setup breakpoint value we can query throughout our app
module.exports = {
    refreshValue: function () {
        window.bokka.breakpoint.value = window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/\"/g, '');
    },
    resize: function () {
        jQuery(window).on('resize', function () {
            window.bokka.breakpoint.refreshValue();
        });
    },
    init: function () {
        this.resize();
        this.refreshValue();
    }
};
