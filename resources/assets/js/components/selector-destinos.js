module.exports = {

  template: require('./templates/selector-destinos.html'),

  data: function () {
    return {
      activarJsTree: false,
    }
  },

  props: {
    destinos: {
      type: Array,
      default: () => { return [] }
    },

    material: {
      type: Object,
      required: false
    }
  },

  components: {
    'areas-jstree': require('./areas-jstree.js')
  },

  computed: {
    /**
     * Cantidad total de articulos por todos los destinos.
     */
    cantidadTotal: function () {
      return this.destinos.reduce((prev, cur) => {
        return prev + cur.cantidad;
      }, 0);
    }
  },

  ready: function () {
    $(this.$els.modalbutton).on('click', this.openModal);
  },

  methods: {
    /**
     * Abre la ventana modal del componente.
     */
    openModal: function () {
      $(this.$els.destinosmodal).modal('show');
      this.activarJsTree = true;
    },

    /**
     * Sincroniza el formulario de acuerdo a los destinos seleccionados en el treeview.
     */
    sincronizaDestinos: function (data) {
      var checked = data.instance.get_checked();

      this.destinos = checked.map((id, index, array) => {
        var exists = this.destinos.filter((destino) => {
          return ('id' in destino && destino.id == id);
        });

        if (exists.length) {
          return exists.shift();
        }
        
        return { id: parseInt(id, 10), nombre: '', ruta: data.instance.get_path(data.node, ' / '), cantidad: 1 };
      });

      this.$dispatch('destinos-changed', this.destinos, this.material);

      this.$nextTick(this.applyMask);
    },

    /**
     * Aplica el plugin de masking a los destinos del formulario.
     */
    applyMask: function () {
      $('.decimal').inputmask('decimal', {
          allowMinus: false,
          rightAlign: true,
          removeMaskOnSubmit: true,
          unmaskAsNumber: true,
          digits: 0
      });
    }
  }
};