 var LayoutView = Backbone.View.extend({
    events: {

    },
    initialize : function(){
        var self = this
        this.options = this.$el.find('.js-model-map').data('options')
        this.markers = this.$el.find('.js-model-map').data('mappins').markers

        this.render()
    },
    styles : [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-100"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"gamma":"0.75"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":"-37"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f9f9f9"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"saturation":"-100"},{"lightness":"-37"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"saturation":"-100"},{"lightness":"100"},{"weight":"2"}]},{"featureType":"landscape.natural","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"saturation":"-100"},{"lightness":"0"}]},{"featureType":"poi.attraction","elementType":"geometry","stylers":[{"lightness":"-4"},{"saturation":"-100"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"},{"visibility":"on"},{"saturation":"-95"},{"lightness":"62"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road","elementType":"labels","stylers":[{"saturation":"-100"},{"gamma":"1.00"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"gamma":"0.50"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":"-100"},{"gamma":"0.50"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"},{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":"-13"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"0"},{"gamma":"1.09"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"},{"saturation":"-100"},{"lightness":"47"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"lightness":"-12"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"},{"lightness":"77"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"-5"},{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"saturation":"-100"},{"lightness":"-15"}]},{"featureType":"transit.station.airport","elementType":"geometry","stylers":[{"lightness":"47"},{"saturation":"-100"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"water","elementType":"geometry","stylers":[{"saturation":"53"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-42"},{"saturation":"17"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":"61"}]}],
    render : function(){
        var self = this
        self.map = new google.maps.Map(self.$el.find('.js-model-map').get(0), {
            center: {
                lat: self.options.center.lat,
                lng: self.options.center.lng
            },
            zoom: self.options.zoom || 14,
            styles: this.styles,
            disableDoubleClickZoom: true,
            draggable: false,
            scrollwheel: false,
            panControl: false,
            disableDefaultUI: false
        })

        google.maps.event.addListenerOnce(self.map,"projection_changed", function() {
            self.setCenter()
        })
        google.maps.event.addDomListener(window, 'resize', function() {
            self.setCenter()
        })



        _.each(self.markers, function(item){
            var marker = new google.maps.Marker({
                position: item.position,
                map: self.map,
                icon: '/wp-content/themes/bokka-wp-theme/assets/build/images/map-pin-purple.png'
            });

            if(item.link) {
                var infowindow = new google.maps.InfoWindow({
                    content: '<a href="'+item.link+'" target="_blank">Click here to get directions</a>'
                });
            }

            marker.addListener('click', function() {
                infowindow.open(self.map, marker)

            })

        })
    },
    setCenter: function(){
        var self = this
        var offset
        if(bokka_breakpoint.value == "desktop"){
            offset = {x:200}
        } else {
            offset = {y: (self.$el.height() / 3 )}
        }
        var point1 = self.map.getProjection().fromLatLngToPoint(
            new google.maps.LatLng(self.options.center)

        );
        var point2 = new google.maps.Point(
            ( (typeof(offset.x) == 'number' ? offset.x : 0) / Math.pow(2, self.map.getZoom()) ) || 0,
            ( (typeof(offset.y) == 'number' ? offset.y : 0) / Math.pow(2, self.map.getZoom()) ) || 0
        );
        self.map.setCenter(self.map.getProjection().fromPointToLatLng(new google.maps.Point(
            point1.x - point2.x,
            point1.y + point2.y
        )));

    }
})

module.exports = LayoutView
