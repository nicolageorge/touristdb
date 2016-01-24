window.Sight = Backbone.Model.extend({
    urlRoot:"index.php/sights/",
    defaults:{
        "id" : null,
        "name" : "",
        "description" : "",
        "subcat_id": "",
        "cat_id": "",
        "subcat_name": "",
        "cat_name" : ""
    },
    initialize: function () {
        Backbone.Model.prototype.initialize.apply(this, arguments);
        this.on( "change", function( model, options ) {
            if ( options && options.save === false ) 
                return;
            model.save();
        });
    }
});

window.SightsCollection = Backbone.Collection.extend({
    model : Sight,
    url : "index.php/sights/"
});