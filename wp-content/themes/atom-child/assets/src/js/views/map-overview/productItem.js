module.exports = Backbone.View.extend({
    initialize:function(options){
        var self = this
        this.options = options
        this.mapInfo = this.$el.data('map-info')
        this.parent = this.options.parent
        this.markerIndex = this.options.parent.markerIndex
        this.defaultIcon = '/wp-content/themes/atom-child/assets/build/images/map-pin-purple.png'
        this.activeIcon = '/wp-content/themes/atom-child/assets/build/images/map-pin-green.png'
        self.initializeMarker()
        self.initializeWatcher();

        $(window).on('resize', function(){
            if (bokka.breakpoint.value == 'desktop') {
                self.initializeWatcher();
            } else {
                self.destroyWatcher();
            }
        });
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

            self.parent.bounds.extend(self.marker.getPosition())

            self.parent.map.fitBounds(self.parent.bounds);

            if (bokka.breakpoint.value !== 'mobile') {
                self.marker.addListener('click', function () {
                    var context = self.$el.closest('.item').find('.header .title h4').text().trim();
                    bokka.eventTrack('Neighborhood Overview', 'Click', 'Overview Map-'+context+'-Pin Click');
                    
                    self.parent.map.setZoom(10)
                    
                    if (self.parent.scrolled) {
                        self.parent.setCenter(self.marker.getPosition(), -350, -75)
                    } else {
                        self.parent.setCenter(self.marker.getPosition(), -350, -300)
                    }
                    
                    bokka.events.trigger('changePin', self.marker);
                    
                    // open infobox when pan finished
                    google.maps.event.addListenerOnce(self.parent.map, 'idle', function(){
                        self.parent.openInfoWindow(self.mapInfo, self.marker, self.markerIndex, true);
                    });
                });
                self.marker.addListener('mouseover', function () {
                    bokka.events.trigger('changePin', self.marker);
                });
            }
        }
    },
    isVisible: function(){
        return this.$el.is(":visible");
    },
    initializeWatcher: function() {
        var self = this;

        if (bokka.breakpoint.value == 'desktop') {
            var scrollMonitor = require("../../vendor/scrollMonitor") // if you're not using require, you can use the scrollMonitor global.
            self.elementWatcher = scrollMonitor.create(this.$el, { top: ($('.tab-header-wrap').innerHeight() + 180), bottom: ($(window).height() / 2) });

            self.elementWatcher.fullyEnterViewport(function() {
                if (self.$el.is(':visible')) {
                    if (self.marker) {
                        $('.item.visible').removeClass('visible')
                        self.$el.addClass("visible");
                        self.parent.map.setZoom(10)
                        self.parent.setCenter(self.marker.getPosition(), -350, -75)
                        bokka.events.trigger('changePin', self.marker);
                    }
                }
            });
            self.elementWatcher.exitViewport(function() {
                if (self.marker) {
                    self.parent.closeInfoWindow(self.index);
                }
            });
        }
    },
    destroyWatcher: function () {
        var self = this;

        if (self.elementWatcher) {
            self.elementWatcher.destroy();
        }
    }
});