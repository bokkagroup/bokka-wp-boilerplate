module.exports = Backbone.View.extend({
    initialize: function () {
        var self = this;
        setTimeout(function(){
        self.setHeight();
            $(window).on('resize', function(){
                self.setHeight();
            });
        }, 250);
    },
    setHeight: function () {
        this.$el.find('.masonry-item').each(function(){
            $(this).height(($(this).width() / 4) * 3)
        })
    }
});