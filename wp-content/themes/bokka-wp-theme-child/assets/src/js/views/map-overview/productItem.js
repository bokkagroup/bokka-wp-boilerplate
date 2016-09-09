module.exports = Backbone.View.extend({
    initialize:function(options){
        var self = this
        this.options = options
        this.mapInfo = this.$el.data('map-info')
        this.parent = this.options.parent
        this.index = this.parent.markerIndex
        self.initializeMarker()

        var scrollMonitor = require("../../vendor/scrollMonitor") // if you're not using require, you can use the scrollMonitor global.
        var elementWatcher = scrollMonitor.create(this.$el, { top: ($('.tab-header-wrap').innerHeight() + 180), bottom: ($(window).height() / 2) });

        elementWatcher.fullyEnterViewport(function() {
            if (self.$el.is(':visible')) {
                if (self.marker) {
                    $('.item.visible').removeClass('visible')
                    self.$el.addClass("visible");
                    self.parent.map.setZoom(10)
                    self.parent.setCenter(self.marker.getPosition(), -350, -75)
                    self.parent.openInfoWindow(self.mapInfo, self.marker);
                }
            }
        });
        elementWatcher.exitViewport(function() {
            if (self.marker) {
                self.parent.closeInfoWindow();
            }
        });
    },
    initializeMarker: function(item){
        var self = this
        if (self.mapInfo.position) {
            
            self.marker = new google.maps.Marker({
                position: {lat: self.mapInfo.position.lat, lng: self.mapInfo.position.lng},
                icon: '/wp-content/themes/bokka-wp-theme-child/assets/build/images/map-pin-purple.png'
            });

            if (self.isVisible()) {
                self.marker.setMap(self.parent.map)
            }

            self.parent.bounds.extend(self.marker.getPosition());

            self.parent.map.fitBounds(self.parent.bounds);

            self.marker.addListener('click', function () {
                self.parent.map.setZoom(10)
                self.parent.setCenter(self.marker.getPosition(), -350, -75)
                self.parent.openInfoWindow(self.mapInfo, self.marker);
            });
        }
    },
    isVisible: function(){
        return this.$el.is(":visible");
    },

});
