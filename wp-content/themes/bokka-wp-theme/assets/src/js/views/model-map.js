var LayoutView = Backbone.View.extend({
    events: {

    },
    initialize : function(){
        var self = this
        this.render()
    },

    render : function(){
        map = new google.maps.Map(this.$el.get(0), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 8
        })
    }
})

module.exports = LayoutView
