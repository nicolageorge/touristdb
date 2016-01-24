window.SightsListView = Backbone.View.extend({

    tagName: "tbody",
    
    initialize: function () {
        _.bindAll(this);
        this.collection = new SightsCollection();
        this.collection.on('reset', this.render); // bind the reset event to render
        this.collection.fetch({
            success: this.render
        });
    },

    render:function (eventName) {
        if( this.collection.models.length != 0 ){
            _.each(this.collection.models, function (sight) {
                $( this.el ).append( new SightsListItemView( { model : sight } ).render().el );
            }, this);
        }
        return this;
    }
});

window.SightsListItemView = Backbone.View.extend({

    tagName: "tr",

    initialize: function() {
        this.template = _.template( tpl.get( 'sight-list-item' ) );
        this.model.bind( "change", this.render, this );
        this.model.bind( "destroy", this.close, this );
    },

    render:function (eventName) {
        $( this.el ).html( this.template( {
            name: this.model.get( "name" ),
            loc_name: this.model.get( "loc_name" ),
            cat_name: this.model.get( "cat_name" ),
            subcat_name: this.model.get( "subcat_name" )
        } ) );
        return this;
    }

});