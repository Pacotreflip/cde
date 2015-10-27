Vue.component('areas-modal-tree', {

  data: function() {
    return {
      created: false
    }
  },

  template: require('./templates/modal-jstree.html'),

  props: ['material', 'whenSelected', 'limit'],

  ready: function() {

    $(this.$els.modalbutton)
      .on('click', this.showModal);

    $(this.$els.jstreemodal).modal()
      .on('shown.bs.modal', this.initializeJsTree);
  },

  methods: {

    /**
     * Muestra la ventana modal.
     */
    showModal: function() {
      $(this.$els.jstreemodal).modal('show');
    },

    /**
     * Inicializacion de JsTree.
     */
    initializeJsTree: function() {
      if (! this.created) {
        this.created = true;

        $(this.$els.jstree).jstree(this.config())
          .on('changed.jstree', this.whenChanged)
          .on('select_node.jstree', this.whenNodeSelected);
      }
    },

    /**
     * Accion cuando el arbol cambia.
     */
    whenChanged: function(e, data) {
      var selected = [];

      data.instance.get_selected(true).forEach(function(node) {
        node.path = data.instance.get_path(node, ' / ');
        selected.push(node);
      });

      this.whenSelected(this.material, selected);
    },

    /**
     * Accion cuando un nodo es seleccionado.
     */
    whenNodeSelected: function(e, data) {
      // if (data.selected.length > this.limit) {
      //   data.instance.uncheck_node(data.node);
      //   data.instance.deselect_node(data.node);
      // }
    },

    /**
     * Configuracion de JsTree.
     */
    config: function() {
      var that = this;

      return {
        core : {
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
          error: function(object) {
            that.$dispatch('globalError', '');
          },
          strings : {
            'Loading ...' : 'Cargando ...'
          }
        },
        checkbox: { three_state: false },
        plugins: ["checkbox"]
      }
    }

  }
});
