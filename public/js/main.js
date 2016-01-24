Backbone.View.prototype.close = function () {
    console.log( 'Closing view ' + this );
    if( this.beforeClose ) {
        this.beforeClose();
    }
    this.remove();
    this.unbind();
};

var AppRouter = Backbone.Router.extend({

    initialize: function() {
        /* $('#header').html(new HeaderView().render().el); */
    },

    routes:{
        "" : "list",
        "sights/edit/:id" : "sightEdit",
        "sights/new" : "sightNew"
    },

    sightNew: function(){
        this.before( function(){
            app.showView( "#sights-backgrid", new SightView( { model : new Sight() } ) );
        } );
    },

    sightEdit: function( id ){
        this.before( function(){ 
            app.showView( '#sights-backgrid', new SightView( { model : app.SightsCollection.get( id ) } ) );
        } );
    },

    list: function() {
        this.before( function(){

            var columns = [{
                name: "id", // The key of the model attribute
                label: "ID", // The name to display in the header
                editable: false, // By default every cell in a column is editable, but *ID* shouldn't be
                // Defines a cell type, and ID is displayed as an integer without the ',' separating 1000s.
                cell: "string"
            }, {
                name: "name",
                label: "Name",
                // The cell type can be a reference of a Backgrid.Cell subclass, any Backgrid.Cell subclass instances like *id* above, or a string
                cell: "string", // This is converted to "StringCell" and a corresponding class in the Backgrid package namespace is looked up
                editable: false
            }, {
                name: "loc_name",
                label: "Localitate",
                editable: false,
                cell: "string"
            }, {
                name: "region",
                label: "Judet",
                editable: false,
                cell: "string"
            }, {
                name: "cat_name",
                label: "Categorie",
                editable: false,
                cell: "string"
            }, {
                name: "subcat_name",
                label: "Subcategorie",
                editable: false,
                cell: "string"
            }, {
                name: "actions",
                label: "Actiuni",
                editable: false,
                cell: Backgrid.Cell.extend({
                    title: "Edit",
                    render : function() {
                        var currentHtml = "<a href='#sights/edit/" + this.model.cid + "''>Edit</a>";
                        this.$el.html( currentHtml );

                        return this;
                    }
                })
            }];

            // Initialize a new Grid instance
            var grid = new Backgrid.Grid({
                columns: columns,
                collection: app.SightsCollection
            });

            // Render the grid and attach the root to your HTML document
             $( "#sights-backgrid" ).html( grid.render().el );
        } );
        
    },

    showView: function( selector, view ) {
        if ( this.currentView )
            this.currentView.close();
        
        $( selector ).html( view.render().el );
        this.currentView = view;
        return view;
    },

    before: function( callback ) {
        if ( this.SightsCollection ) {
            if ( callback )
                callback();
        } else {
            this.SightsCollection = new SightsCollection();
            this.SightsCollection.fetch({ 
                success:function () {
                    if ( callback ) 
                        callback();
                } 
            });
        }
    }
    
});

tpl.loadTemplates( [ 'sight-details', 'sight-list-item' ], function() {
    app = new AppRouter();
    Backbone.history.start();
});