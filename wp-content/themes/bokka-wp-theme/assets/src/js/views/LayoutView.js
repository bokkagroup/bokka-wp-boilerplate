var LayoutView = Backbone.View.extend({
	className: "layout-wrapper",
	tagName: "div",
	events: {
		
	},
	initialize : function(){
		var self = this
		self.render()

		self.$el.find(".row").draggable()
	},
	render : function(){
		var self = this
		var template = require('../templates/layout-options')
		self.$el.append( template( self.model.toJSON() ) )
	}
})

module.exports = LayoutView