module.exports = {

  template: require('./templates/areas-jstree.html'),

  data: function () {
    return {
      created: false,
      errors: []
    }
  },

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
    var jstreeConf = this.getConfig();

    $(this.$els.jstree).jstree(jstreeConf)
      .on('changed.jstree', (e, data) => this.treeHasChanged(data));
    
    this.created = true;
  },

  methods: {
    /**
     * Obtiene la instancia de jstree.
     */
    getInstance: function () {
      return $(this.$els.jstree).jstree(true);
    },

    /**
     * Identifica si un nodo es una hoja.
     */
    nodeIsLeaf: function (node) {
      return this.getInstance().is_leaf(node);
    },

    /**
     * Genera la ruta completa de un nodo.
     */
    getNodePath: function (data) {
      return data.instance.get_path(data.node, ' / ');
    },

    /**
     * Identifica si un nodo tiene marcado su checkbox.
     */
    nodeIsChecked: function (node) {
      if (this.plugins.indexOf('checkbox') != -1) {
        return this.getInstance().is_checked(node);
      }
      return false;
    },

    /**
     * Tareas que se ejecutan cuando el arbol cambia 
     * (cuando un nodo es seleccionado/deseleccionado).
     */
    treeHasChanged: function (data) {
      if (this.selectOnlyLeaf && ! this.nodeIsLeaf(data.node)) {
          data.instance.uncheck_node(data.node);
          data.instance.deselect_node(data.node);
          return false;
        }

        if (this.nodeIsChecked(data.node)) {
          this.whenCheck(data);
        }

        this.whenChange(data);
    },

    displayErrors: function (object) {
      console.error('error (' + object.error + '): ' + object.reason);
      // that.errors = _.flatten(_.toArray({ error: object.error + ': ' + object.reason }));
      this.$dispatch('globalError', '');
    },

    getConfig: function () {
      return {
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
          error: this.displayErrors
        },
        checkbox: { three_state: false },
        plugins: this.plugins,
      }
    }
  }
};