Vue.component('areas-tree', {

  data: function() {
    return {
      created: false,
      errors: []
    }
  },

  template: '<div v-el:jstree></div><div class="alert alert-danger" v-if="errors.length"><ul><li v-for="error in errors">{{ error }}</li></ul></div>',

  props: ['whenSelected'],

  ready: function() {
    var that = this;

    var jstreeConf = {
      core : {
        multiple: false,
        data: {
          url: function(node) {
            if (node.id === "#") {
                return '/api/areas/jstree';
            }
            return '/api/areas/' + node.id + '/children/jstree';
          },
          data: function (node) {
            return { "id" : node.id };
          }
        },
        strings : {
          'Loading ...' : 'Cargando ...'
        },
        error: function(object) {
          // that.errors = _.flatten(_.toArray({ error: object.error + ': ' + object.reason }));
          that.$dispatch('globalError', '');
        }
      }
    };

    $(this.$els.jstree).jstree(jstreeConf)
      .on('changed.jstree', function(e, data) {
        this.created = true;
        this.whenSelected(data.node.id);
      }.bind(this));
  }
});
