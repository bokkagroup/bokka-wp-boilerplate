var ProductView = require('./map-w-locations/productItem');
var InfoBox = require('../vendor/infobox');

var LayoutView = Backbone.View.extend({
    initialize : function(){
        var self = this
        // Map and marker options/data
        this.options = this.$el.find('.js-locations-map').data('options')
        this.locations = []
        this.markerIndex = 0
        this.currentMarkerIndex = null
        this.center = {
            lat: 40.4076112,
            lng: -105.0960272
        };

        this.bounds = new google.maps.LatLngBounds()

        this.render();
        this.createInfobox();
    },
    styles : require('../config/mapStyles'),
    render : function(){
        var self = this

        self.initializeMap();
    },
    initializeMap: function(){
        var self = this;

        // Instantiate Google map
        var mapSettings = {
            center: self.center,
            zoom: 10,
            styles: this.styles,
            disableDoubleClickZoom: true,
            draggable: true,
            scrollwheel: false,
            panControl: false,
            disableDefaultUI: (bokka.breakpoint.value == 'mobile') ? true : false
        }
        self.map = new google.maps.Map(self.$el.find('.js-locations-map').get(0), mapSettings);

        // Map/DOM event listeners
        google.maps.event.addListenerOnce(self.map, 'projection_changed', function() {
            self.initializeProduct();

            setTimeout(function () {
                self.map.fitBounds(self.bounds);
                if (bokka.breakpoint.value !== 'desktop') {
                    self.map.setCenter(self.map.getCenter());
                } else {
                    self.map.setCenter(self.map.getCenter(), 0, 0);
                }
                self.map.setZoom(self.map.getZoom() - 6);
            }, 250);
        })
        google.maps.event.addDomListener(window, 'resize', function() {
            google.maps.event.trigger(self.map, 'resize');
            self.map.fitBounds(self.bounds);
            self.map.setZoom(self.map.getZoom() - 1);

            if (bokka.breakpoint.value !== 'desktop') {
                self.map.setCenter(self.map.getCenter());
            } else {
                self.map.setCenter(self.map.getCenter(), 0, 0);
            }
        });
    },
    initializeProduct: function(){
        var self = this
        self.$el.find('.location-info').each(function(){
            var index = self.locations.push(new ProductView({el: $(this), parent: self}))
            self.markerIndex++;
        })
    },
    resetProduct: function(){
        var self = this

        self.bounds = new google.maps.LatLngBounds()

        _.each(self.locations, function(item){
            if(item.marker) {
                item.marker.setMap(null)
                self.currentMarkerIndex = null;
                self.bounds.extend(item.marker.getPosition())
                self.map.fitBounds(self.bounds)
                self.map.setZoom(self.map.getZoom() - 1)
                self.map.setCenter(self.map.getCenter(), 0, 0)
                setTimeout(function(){
                    if (item.isVisible()) {
                        item.marker.setMap(self.map)
                    }
                }, 500);
                self.resetInfobox();
            }
        })
    },
    setCenter: function(coordinates, offsetx, offsety){
        var self = this;
        var scale = Math.pow(2, self.map.getZoom());
        offsetx = (typeof offsetx !== 'undefined') ? offsetx : 0;
        offsety = (typeof offsety !== 'undefined') ? offsety : 0;

        var worldCoordinateCenter = self.map.getProjection().fromLatLngToPoint(coordinates)
        var pixelOffset = new google.maps.Point( (offsetx/scale) || 0, (offsety/scale) || 0 )

        var worldCoordinateNewCenter = new google.maps.Point(
            worldCoordinateCenter.x - pixelOffset.x,
            worldCoordinateCenter.y + pixelOffset.y
        )

        var newCenter = self.map.getProjection().fromPointToLatLng(worldCoordinateNewCenter)

        self.map.panTo(newCenter);
    },

    createInfobox: function(){
        var self = this;
        // Create infobox instance
        self.infoBoxTemplate = require('../templates/infoBoxSmall.handlebars');

        var boxOptions = {
            content: null
            ,disableAutoPan: true
            ,maxWidth: 310
            ,zIndex: null
            ,boxStyle: {
                width: '310px',
            }
            ,isHidden: false
            ,pane: 'floatPane'
            ,enableEventPropagation: false
        };

        self.infoBox = new InfoBox();
        self.infoBox.setOptions(boxOptions);
    },
    openInfoWindow: function (info, marker, markerIndex, force) {
        var self = this;
        var windowTop = (window.pageYOffset || document.scrollTop) - (document.clientTop || 0);

        if (((self.currentMarkerIndex !== markerIndex) && windowTop > 0) || force) {
            self.infoBox.setContent(self.infoBoxTemplate(info));
            self.infoBox.open(self.map, marker);

            self.currentMarkerIndex = markerIndex;

            // Bind event handler for close buttoninfoBoxelf.ib.addListener('domready', function () {
            self.infoBox.addListener('domready', function () {
                $('.infowindow-close').on('click', function (event) {
                    event.preventDefault();
                    self.infoBox.close();
                });
            });
        }
    },
    closeInfoWindow: function (markerIndex) {
        var self = this;

        if (self.infoBox && (self.currentMarkerIndex === markerIndex)) {
            self.infoBox.setContent('');
            self.infoBox.close();
        }
    },
    resetInfobox: function () {
        var self = this;
        self.infoBox.setContent('');
        self.infoBox.close();
    },
});

module.exports = LayoutView;
