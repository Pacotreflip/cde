Vue.component('areas-tree', {

  data: function () {
    return {
      created: false,
      errors: []
    }
  },

  template: '<div v-el:jstree></div><div class="alert alert-danger" v-if="errors.length"><ul><li v-for="error in errors">{{ error }}</li></ul></div>',

  props: {
    multiple: {
      type: Boolean,
      default: false
    },

    plugins: {
      type: Array,
      default: () => { return [] }
    },

    whenChange: {
      type: Function,
      default: (data) => {
        return data;
      }
    },

    whenCheck: {
      type: Function,
      default: () => { return [] }
    },

    selectOnlyLeaf: {
      type: Boolean,
      default: false
    }
  },

  ready: function () {
    var that = this;

    var jstreeConf = {
      core : {
        multiple: this.multiple,
        data: {
          url: function (node) {
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
        error: (object) => {
          // that.errors = _.flatten(_.toArray({ error: object.error + ': ' + object.reason }));
          this.$dispatch('globalError', '');
        }
      },
      checkbox: { three_state: false },
      plugins: this.plugins,
    };

    $(this.$els.jstree).jstree(jstreeConf)
      // .on('changed.jstree', (e, data) => { this.whenSelected(data); })
      .on('changed.jstree', (e, data) => {
        if (this.selectOnlyLeaf && ! data.instance.is_leaf(data.node)) {
          data.instance.uncheck_node(data.node);
          data.instance.deselect_node(data.node);
          return false;
        }

        if (this.isChecked(data)) {
          this.whenCheck(data);
        }

        this.whenChange(data);
      });
    
    this.created = true;
  },

  methods: {
    getNodePath: function (data) {
      return data.instance.get_path(data.node, ' / ');
    },

    isChecked: function (data) {
      return data.instance.is_checked(data.node);
    }
  }
});
