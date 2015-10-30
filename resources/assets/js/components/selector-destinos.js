
Vue.component('selector-destinos', {

  template: require('./templates/selector-destinos.html'),

  data: function () {

    return {
      destinos: [
        // { id: 1, nombre: 'Nivel 1', ruta: 'Nivel Raiz / Nivel 1', cantidad: 1},
        // { id: 2, nombre: 'Nivel 2', ruta: 'Nivel Raiz / Nivel 2', cantidad: 1},
        // { id: 3, nombre: 'Nivel 3', ruta: 'Nivel Raiz / Nivel 3', cantidad: 1}
      ],

      activarJsTree: false,
    }
  },


  computed: {
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

    openModal: function () {
      $(this.$els.destinosmodal).modal('show');
      this.activarJsTree = true;
    },

    sincronizaDestinos: function (data) {
      var checked = data.instance.get_checked();

      this.destinos = checked.map((id) => {
        var exists = this.destinos.filter((destino) => {
          return ('id' in destino && destino.id == id);
        });

        if (exists.length) {
          return exists.shift();
        }
        
        return { id: parseInt(id, 10), nombre: '', ruta: data.instance.get_path(data.node, ' / '), cantidad: 1 };
      });

      this.$nextTick(this.applyMask);
    },

    applyMask: function() {
      $('.decimal').inputmask('decimal', {
          allowMinus: false,
          rightAlign: true,
          removeMaskOnSubmit: true,
          unmaskAsNumber: true,
          min: 1,
          digits: 2
      });
    }
  }
});