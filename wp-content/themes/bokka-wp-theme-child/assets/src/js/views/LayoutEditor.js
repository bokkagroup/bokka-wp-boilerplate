var View = Backbone.View.extend({
	el : "#bgbbCanvas",
	initialize : function(){
		var self = this
		self.collection = window.bgbbApp.collections.layouts
		self.views = []
  		self.render()
	},
	addOne : function( model ){
		var self = this
		var LayoutView = require( './LayoutView' )
		var layoutView = new LayoutView({ model: model })
		self.views.push( layoutView )
		self.$el.append( layoutView.el )
	},
	render: function(){
		var self = this
		self.$el.html( $('<div id="bgbb-layouts"></div>'))
		_.each( self.collection.models, function( model ){
  			self.addOne( model )
  		})
	}
})

module.exports = View