module.exports = Backbone.View.extend({
    initialize:function(options){
        var self = this
        this.options = options
        this.mapInfo = this.$el.data('map-info')
        this.parent = this.options.parent
        this.markerIndex = this.options.parent.markerIndex
        this.defaultIcon = '/wp-content/themes/bokka-wp-theme-child/assets/build/images/map-pin-purple.png'
        this.activeIcon = '/wp-content/themes/bokka-wp-theme-child/assets/build/images/map-pin-green.png'
        self.initializeMarker()
    },
    initializeMarker: function(item){
        var self = this
        if (self.mapInfo.position) {
            
            self.marker = new google.maps.Marker({
                position: {lat: self.mapInfo.position.lat, lng: self.mapInfo.position.lng},
                icon: self.defaultIcon
            });

            if (self.isVisible()) {
                self.marker.setMap(self.parent.map)
            }

            bokka.events.on('resetPinIcon', function() {
                self.marker.setIcon(self.defaultIcon);
            });

            bokka.events.on('changePin', function(marker) {
                if (this === marker) {
                    this.setIcon(self.activeIcon)
                    this.setZIndex(1)
                } else {
                    this.setIcon(self.defaultIcon)
                    this.setZIndex(0)
                }
            }, self.marker);

            self.parent.bounds.extend(self.marker.getPosition());

            self.parent.map.fitBounds(self.parent.bounds);

            self.marker.addListener('click', function () {
                bokka.events.trigger('changePin', self.marker);
                self.parent.openInfoWindow(self.mapInfo, self.marker, self.markerIndex, true);
            });

            if (bokka.breakpoint.value !== 'mobile') {
                self.marker.addListener('mouseover', function () {
                    bokka.events.trigger('changePin', self.marker);
                    self.parent.openInfoWindow(self.mapInfo, self.marker, self.markerIndex, true);
                });
            }
        }
    },
    isVisible: function(){
        return this.$el.is(":visible");
    }
});
