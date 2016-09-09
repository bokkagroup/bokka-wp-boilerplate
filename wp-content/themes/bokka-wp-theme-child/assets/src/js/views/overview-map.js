var ProductView = require('./map-overview/productItem');
var scrollMonitor = require('../vendor/scrollMonitor');
var InfoBox = require('../vendor/infobox')
var LayoutView = Backbone.View.extend({
    events: {
        'click .product-tab-links a' : 'handleClick'
    },
    initialize : function(){
        var self = this
        
        // Map and marker options/data
        this.options = this.$el.find('.js-model-map').data('options')
        this.locations = []
        this.markerIndex = 0
        this.currentMarkerIndex = null

        this.bounds = new google.maps.LatLngBounds()
        this.tabs = this.$el.find('.tab-body');
        this.current = 0;
        this.scrolled = false

        this.render();
        this.createInfobox();


        // Check if user has scrolled the page
        var bodyWatcher = scrollMonitor.create($('body'));
        bodyWatcher.partiallyExitViewport(function () {
            self.scrolled = true;
        });
        
    },
    styles : require('../config/mapStyles'),
    render : function(){
        var self = this
        self.initializeMap();
	// Tab column heights
        self.setTabHeight();

        $(window).on('resize', function(){
            self.setTabHeight();
        });
    },
    initializeMap: function(){
        var self = this;
        // Instantiate Google map
        var mapSettings = {
            center: {
                lat: 40.4076112,
                lng: -105.0960272
            },
            zoom: 5,
            styles: this.styles,
            disableDoubleClickZoom: true,
            draggable: true,
            scrollwheel: false,
            panControl: false,
            disableDefaultUI: (bokkaBreakpoint.value == 'mobile') ? true : false
        }
        self.map = new google.maps.Map(self.$el.find('.js-model-map').get(0), mapSettings);

        setTimeout(function () {
            if (self.map) {
                self.setFixedPosition();
            }
        }, 500);

        // Map event listeners
        google.maps.event.addListenerOnce(self.map, 'idle', function(){
            self.setCenter(self.map.getCenter(), -350, -105)
        });
        google.maps.event.addListenerOnce(self.map,'projection_changed', function() {
            self.initializeProduct();
            self.setCenter(self.map.getCenter(), -600, -105)
        })
    },
    initializeProduct: function(){
        var self = this
        self.$el.find('.tab-body .item').each(function(){
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
                this.currentMarkerIndex = null
                self.resetInfobox();
                self.bounds.extend(item.marker.getPosition())
                self.map.fitBounds(self.bounds)
                self.setCenter(self.map.getCenter(), -350, -105)
                setTimeout(function(){
                    if (item.isVisible()) {
                        item.marker.setMap(self.map)
                    }
                },500)
            }
        })
    },
    setFixedPosition: function(){
        var self = this
        var elementWatcher = scrollMonitor.create($('.breadcrumb-outer-wrapper'), 30)

        elementWatcher.enterViewport(function() {
            self.$el.find('.map-wrap, .tab-header-wrap, .product-tab-bodies').removeClass('fixed');
            
            // Return map to original state
            if (self.scrolled) {            
                self.resetInfobox();
                self.map.fitBounds(self.bounds);
                self.setCenter(self.map.getCenter(), -350, -105);
            }
        });
        elementWatcher.exitViewport(function() {
            self.$el.find('.map-wrap, .tab-header-wrap, .product-tab-bodies').addClass('fixed');
        });
    },
    setCenter: function(coordinates, offsetx, offsety){
        var self = this
        var scale = Math.pow(2, self.map.getZoom())

        var worldCoordinateCenter = self.map.getProjection().fromLatLngToPoint(coordinates)
        var pixelOffset = new google.maps.Point( (offsetx/scale) || 0, (offsety/scale) || 0 )

        var worldCoordinateNewCenter = new google.maps.Point(
            worldCoordinateCenter.x - pixelOffset.x,
            worldCoordinateCenter.y + pixelOffset.y
        )

        var newCenter = self.map.getProjection().fromPointToLatLng(worldCoordinateNewCenter)

        self.map.panTo(newCenter);

    },

    setTabHeight: function () {
        var self = this;

        if (bokkaBreakpoint.value == 'desktop') {
            var height = this.$el.find('.tab-body').eq(this.current).innerHeight();
            this.$el.find('.product-tab-bodies').height(height);
        } else {
            self.tabs.fadeIn();
        }
    },
    changeTab: function (index) {
        var self = this;

        var nextTab = self.$el.find('.tab-body').eq(index);
        var prevTab = self.$el.find('.tab-body').eq(this.current);

        var nextTabHeader = self.$el.find('.product-tab-header').eq(index);
        var prevTabHeader = self.$el.find('.product-tab-header').eq(this.current);

        this.current = index;

        $(nextTab).fadeIn();
        $(nextTabHeader).fadeIn();
        $(prevTab).fadeOut();
        $(prevTabHeader).fadeOut();
    },
    handleClick: function (event) {
        var self = this;

        event.preventDefault();

        var index = $(event.target).closest('li').index();

        if (index !== this.current) {
            self.resetProduct();
            $(event.target).closest('ul').find('.active').removeClass('active');
            $(event.target).closest('li').addClass('active');
            self.changeTab(index);
            self.setTabHeight();
        }
    },
    createInfobox: function(){
        var self = this;
        // Create infobox instance
        self.infoBoxTemplate = require('../templates/infoBox.handlebars');

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
    openInfoWindow: function (info, marker) {
        var self = this;
        self.infoBox.setContent(self.infoBoxTemplate(info));
        self.infoBox.open(self.map, marker);

        self.currentMarkerIndex = self.index

        // Bind event handler for close buttoninfoBoxelf.ib.addListener('domready', function () {
        self.infoBox.addListener('domready', function () {
            $('.infowindow-close').on('click', function(event) {
                event.preventDefault();
                self.infoBox.close();
            });
        });
    },
    closeInfoWindow: function () {
        var self = this;

        if (self.infoBox && (self.currentMarkerIndex === self.index)) {
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
