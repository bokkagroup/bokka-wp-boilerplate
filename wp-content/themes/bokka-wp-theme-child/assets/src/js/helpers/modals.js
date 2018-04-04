var CookieJS = require('../vendor/cookies')
var gformHelpers = require('./gforms');

//fancybox for the masonry gallery
$(".fancybox-masonry").fancybox({
    openEffect  : 'none',
    closeEffect : 'none',
    autoSize: true,
    maxWidth: '85%',
    maxHeight: '90%',
    helpers: {
        overlay: {
            locked: true
        }
    },
    tpl : {
        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon icon-exit-circle" href="javascript:;"></a>',
    }
});
$(".fancybox-class").fancybox();

/** typical modal opens **/
$(".modal-trigger, .fancy-trigger").fancybox({
    autoSize: false,
    height: 'auto',
    maxWidth: '85%',
    maxHeight: '90%',
    helpers: {
        overlay: {
            locked: true
        }
    },
    tpl : {
        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon icon-exit-circle" href="javascript:;"></a>',
    },
    beforeShow : function () {
        var wrapclass = $(this.element).data('wrapclass');
        var width = $(this.element).data('width');
        var neighborhoodName = $(this.element).data('neighborhood');

        if (wrapclass) {
            $(this.skin).addClass(wrapclass);
        }

        if (width) {
            this.maxWidth = width;
        }

        if (neighborhoodName) {
            gformHelpers.setNeighborhoodNameSelect(neighborhoodName);
        }
    }
});

/** Video modals **/
$(".video-modal-trigger").fancybox({
    openEffect: 'none',
    closeEffect: 'none',
    autoSize: false,
    maxWidth: '85%',
    maxHeight: '90%',
    helpers: {
        overlay: {
            locked: false
        }
    },
    tpl: {
        closeBtn: '<a title="Close" class="fancybox-item fancybox-close icon icon-exit-circle" href="javascript:;"></a>',
    },
});


/** Update fancybox when gravity form is loaded for proper sizing **/
$(document).on('gform_confirmation_loaded', function(event, formId) {
    $.fancybox.update();
})
