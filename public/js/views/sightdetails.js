window.SightView = Backbone.View.extend({

    tagName: "div", // Not required since 'div' is the default if no el or tagName specified

    initialize: function() {
        this.template = _.template( tpl.get( 'sight-details' ) );
        this.model.bind( "change", this.render, this );
    },

    render: function( eventName ){
        $( this.el ).html( this.template( this.model.toJSON() ) );
        return this;
    },

    events: {
        "change input" : "change",
        "click .save" : "saveSight",
        "click .delete" : "deleteSight"
    },

    change: function(event) {
        var target = event.target;
        
        console.log( 'changing ' + target.id + ' from: ' + target.defaultValue + ' to: ' + target.value );
        
        var change = {};
        change[target.name] = target.value;
        this.model.set( change );
    },

    destroy: function() {
        // COMPLETELY UNBIND THE VIEW
        this.undelegateEvents();

        this.$el.removeData().unbind(); 

        // Remove view from DOM
        this.remove();  

        Backbone.View.prototype.remove.call(this);
    },

    saveSight:function () {
        this.model.set({
            name : $( '#name' ).val(),
            description : $( '#description' ).val()
        });
        if (this.model.isNew()) {
            var self = this;
            app.sightsCollection.create( this.model, {
                success:function() {
                    app.navigate( 'sights/edit/' + self.model.id, false );
                }
            });
        } else {
            this.model.save();
        }

        return false;
    },

    deleteSight:function () {
        this.model.destroy({
            success:function () {
                alert( 'Sight deleted successfully' );
                window.history.back();
            }
        });
        return false;
    }

});