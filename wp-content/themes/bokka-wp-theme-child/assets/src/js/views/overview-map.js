
 var LayoutView = Backbone.View.extend({
    events: {
        "click .map-view": "handleClick"
    },
    initialize : function(){
        var self = this

        this.options = this.$el.find('.js-model-map').data('options')
        this.markers = this.$el.find('.js-model-map').data('mappins').markers
        this.googleMarkers = [];
        this.render()
    },
    styles : [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-100"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"gamma":"0.75"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":"-37"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f9f9f9"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"saturation":"-100"},{"lightness":"-37"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"saturation":"-100"},{"lightness":"100"},{"weight":"2"}]},{"featureType":"landscape.natural","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"saturation":"-100"},{"lightness":"0"}]},{"featureType":"poi.attraction","elementType":"geometry","stylers":[{"lightness":"-4"},{"saturation":"-100"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"},{"visibility":"on"},{"saturation":"-95"},{"lightness":"62"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road","elementType":"labels","stylers":[{"saturation":"-100"},{"gamma":"1.00"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"gamma":"0.50"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":"-100"},{"gamma":"0.50"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"},{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":"-13"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"0"},{"gamma":"1.09"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"},{"saturation":"-100"},{"lightness":"47"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"lightness":"-12"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"},{"lightness":"77"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"-5"},{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"saturation":"-100"},{"lightness":"-15"}]},{"featureType":"transit.station.airport","elementType":"geometry","stylers":[{"lightness":"47"},{"saturation":"-100"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"water","elementType":"geometry","stylers":[{"saturation":"53"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-42"},{"saturation":"17"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":"61"}]}],
    zoom: 14,
    render : function(){
        var self = this
        window.overviewMap = self.map = new google.maps.Map(self.$el.find('.js-model-map').get(0), {
            center: {
                lat: self.options.center.lat,
                lng: self.options.center.lng
            },
            zoom: this.zoom,
            styles: this.styles,
            disableDoubleClickZoom: true,
            draggable: false,
            scrollwheel: false,
            panControl: false,
            disableDefaultUI: (bokkaBreakpoint.value == 'mobile') ? true : false
        })

        google.maps.event.addListenerOnce(self.map, 'projection_changed', function() {
            self.setCenter()
        })
        google.maps.event.addListenerOnce(self.map, 'idle', function() {
            self.setCenter();
        })
        google.maps.event.addDomListener(window, 'resize', function() {
            self.setCenter()
        })

        var boxOptions = {
            content: null
            ,disableAutoPan: true
            ,maxWidth: 310
            ,zIndex: null
            ,boxStyle: {
                width: "310px",
            }
            ,isHidden: false
            ,pane: "floatPane"
            ,enableEventPropagation: false
        };
        var InfoBox = require('../vendor/infobox')

        self.ib = new InfoBox();
        self.ib.setOptions(boxOptions);

        var bounds = new google.maps.LatLngBounds();

        _.each(self.markers, function(item){

            var marker = self.googleMarkers[item.id] = new google.maps.Marker({
                position: item.position,
                map: self.map,
                clickable: (bokkaBreakpoint.value == 'mobile') ? false : true,
                data: {
                    title: item.title,
                    description: item.description
                },
                icon: '/wp-content/themes/bokka-wp-theme-child/assets/build/images/map-pin-purple.png'
            });

            bounds.extend(marker.position);

            // Event handler when clicking map pin on map
            marker.addListener('click', function() {
                self.openInfoWindow(item.id);
            });
        });

        self.map.fitBounds(bounds);

        google.maps.event.addListener(self.map, 'bounds_changed', function() {
            if (!self.initBounds) {
                self.options.bounds = self.map.getBounds();
                self.options.boundsCenter = {
                    lat: (self.options.bounds.H.H + self.options.bounds.H.j) / 2,
                    lng: (self.options.bounds.j.H + self.options.bounds.j.j) / 2,
                }
                self.setCenter();
                self.initBounds = true;
            }
        });

    },

    initBounds: false,
    currentFocus: false,

    // Event handler for the "View on map" button
    handleClick: function (event) {
        event.preventDefault();
    
        var self = this;
        var id = $(event.currentTarget).data('marker-id');

        self.openInfoWindow(id);
    },

    // Open the infowindow, call focusMarker
    openInfoWindow: function (markerId) {
        var self = this;
        var marker = self.googleMarkers[markerId];
        var template = _.template($('#info-window-template').html());

        self.focusMarker(marker);
        self.ib.setOptions({content: template(marker.data)});
        self.ib.open(self.map, self.googleMarkers[markerId]);

        // Bind event handler for close button
        self.ib.addListener('domready', function () {
            $('.infowindow-close').on('click', function(event) {
                event.preventDefault();

                self.ib.close();
            });
        });
    },

    // Focus and zoom map, call setCenter for marker location
    focusMarker: function (marker) {
        var self = this;
        var latlng = marker.position;

        self.map.setZoom(self.zoom);
        self.currentFocus = latlng;
        self.setCenter();
    },

    // Center map (Initial page load = center map on bounds of all markers. Infowindow open = center/pan map per that marker position)
    setCenter: function () {
        var self = this;
        var latlng = (typeof self.currentFocus !== 'undefined' && self.currentFocus instanceof google.maps.LatLng) ? self.currentFocus : new google.maps.LatLng(self.options.boundsCenter);
        
        // TODO: Clean this up
        var offsetx = 1;
        var offsety = 1;
        var offsetxFactor;
        var offsetyFactor;

        if (bokkaBreakpoint.value == 'mobile') {
            offsetxFactor = offsetx = 1;
        } else if (bokkaBreakpoint.value == 'tablet') {
            offsetx = -(self.$el.innerWidth() / offsetxFactor);
        } else if (bokkaBreakpoint.value == 'desktop') {
            offsetxFactor = offsetyFactor = 4;
            offsetx = -(self.$el.innerWidth() / offsetxFactor);
        }

        offsety = self.currentFocus ? (self.$el.innerHeight() / offsetyFactor) : 0;

        var point1 = self.map.getProjection().fromLatLngToPoint(latlng);
        var point2 = new google.maps.Point(
            ( (typeof(offsetx) == 'number' ? offsetx : 0) / Math.pow(2, self.map.getZoom()) ) || 0,
            ( (typeof(offsety) == 'number' ? offsety : 0) / Math.pow(2, self.map.getZoom()) ) || 0
        );
        var newPoint = self.map.getProjection().fromPointToLatLng(new google.maps.Point(
            point1.x - point2.x,
            point1.y + point2.y
        ));

        if (self.currentFocus)
            self.map.panTo(newPoint);
        else
            self.map.setCenter(newPoint);
    }

})

module.exports = LayoutView
