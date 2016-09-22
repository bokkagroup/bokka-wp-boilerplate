 var LayoutView = Backbone.View.extend({
    events: {

    },
    initialize : function(){
        var self = this

        this.options = this.$el.find('.js-model-map').data('options')
        this.markers = this.$el.find('.js-model-map').data('mappins').markers

        this.render()
    },
    styles : require('../config/mapStyles'),
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
                icon: '/wp-content/themes/bokka-wp-theme-child/assets/build/images/map-pin-purple.png'
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
        if(bokka.breakpoint.value == "desktop"){
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
