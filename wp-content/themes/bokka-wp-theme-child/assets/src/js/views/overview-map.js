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
        this.center = {
            lat: 40.4076112,
            lng: -105.0960272
        };

        this.bounds = new google.maps.LatLngBounds()
        this.tabs = this.$el.find('.tab-body');
        this.current = self.$el.find('.product-tab-links .tab.active').index();
        this.scrolled = false

        this.render();
        this.createInfobox();
        this.emulateClick();

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
        self.setTabHeight();

        $(window).on('resize', function(){
            self.setTabHeight();
            self.setFixedPosition();
        });
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
        self.map = new google.maps.Map(self.$el.find('.js-model-map').get(0), mapSettings);

        setTimeout(function () {
            if (self.map) {
                self.setFixedPosition();
            }
        }, 500);

        // Map/DOM event listeners
        google.maps.event.addListenerOnce(self.map, 'projection_changed', function() {
            self.initializeProduct();

            setTimeout(function () {
                self.map.fitBounds(self.bounds);

                if (bokka.breakpoint.value !== 'desktop') {
                    self.setCenter(self.map.getCenter());
                } else {
                    self.setCenter(self.map.getCenter(), -350, -105);
                }
            }, 250);
        })
        google.maps.event.addDomListener(window, 'resize', function() {
            google.maps.event.trigger(self.map, 'resize');
            self.map.fitBounds(self.bounds);

            if (bokka.breakpoint.value !== 'desktop') {
                self.setCenter(self.map.getCenter());
            } else {
                self.setCenter(self.map.getCenter(), -350, -105);
            }
        });
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
                self.currentMarkerIndex = null;
                self.bounds.extend(item.marker.getPosition())
                self.map.fitBounds(self.bounds)
                self.setCenter(self.map.getCenter(), -350, -105)
                setTimeout(function(){
                    if (item.isVisible()) {
                        item.marker.setMap(self.map)
                    }
                }, 500);
                self.resetInfobox();
            }
        })
    },
    setFixedPosition: function(){
        var self = this
        
        if (bokka.breakpoint.value == 'desktop') {

            self.elementWatcher = scrollMonitor.create($('.breadcrumb-outer-wrapper, .header'), 30);

            self.elementWatcher.enterViewport(function() {
                self.$el.find('.product-listings-container').removeClass('fixed');
                
                // Return map to original state
                if (self.scrolled) {            
                    self.resetInfobox();
                    self.map.fitBounds(self.bounds);
                    self.setCenter(self.map.getCenter(), -350, -105);
                }
            });
            self.elementWatcher.exitViewport(function() {
                self.$el.find('.product-listings-container').addClass('fixed');
            });
        } else {
            self.destroyWatcher();
            self.$el.find('.product-listings-container').removeClass('fixed');
        }
    },
    destroyWatcher: function () {
        var self = this;

        if (self.elementWatcher) {
            self.elementWatcher.destroy();
        }
    },
    setCenter: function(coordinates, offsetx, offsety){
        var self = this
        var scale = Math.pow(2, self.map.getZoom())
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
    setTabHeight: function () {
        var self = this;

        if (bokka.breakpoint.value == 'desktop') {
            var height = this.$el.find('.tab-body').eq(this.current).innerHeight();
            this.$el.find('.product-tab-bodies').height(height);

            if (this.$el.find('.tab-body').not(':eq(' + this.current + ')')) {
                this.$el.find('.tab-body').not(':eq(' + this.current + ')').hide();
            }
        } else {          
            self.tabs.each(function() {
                $(this).fadeIn();
                $(this).css('height', 'auto');
            });

            $('.product-tab-bodies').css('height', 'auto');
        }
    },
    changeTab: function (index) {
        var self = this;

        var nextTab = self.$el.find('.tab-body').eq(index);
        var prevTab = self.$el.find('.tab-body').eq(self.current);
        var nextTabHeader = self.$el.find('.product-tab-header').eq(index);
        var prevTabHeader = self.$el.find('.product-tab-header').eq(self.current);

        self.current = index;

        $(nextTab).fadeIn();
        $(nextTabHeader).fadeIn();
        $(prevTab).fadeOut();
        $(prevTabHeader).fadeOut();
    },
    emulateClick: function () {
        var self = this;

        if (window.location.href.indexOf('our-neighborhoods') > -1) {
            self.$el.find('.tab.our-neighborhoods a').trigger('click');
        }
        if (window.location.href.indexOf('model-homes') > -1) {
            self.$el.find('.tab.model-homes a').trigger('click');
        }
        if (window.location.href.indexOf('quick-move-in-homes') > -1) {
            self.$el.find('.tab.quick-move-in-homes a').trigger('click');
        }
    },
    handleClick: function (event) {
        var self = this;

        event.preventDefault();

        var index = $(event.target).closest('li').index();

        if (index !== this.current) {
            window.scrollTo(0, 0);
            $(event.target).closest('ul').find('.active').removeClass('active');
            $(event.target).closest('li').addClass('active');
            self.changeTab(index);
            self.setTabHeight();
            
            setTimeout(function() {
                self.resetProduct();
            }, 250);
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
