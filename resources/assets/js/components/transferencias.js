Vue.filter('depth', function (value, depth) {
  return '-'.repeat(depth) + value
});

Vue.component('transferencias-screen', {

  data: function() {
    return {
      transferenciaForm: {
        fecha: '',
        area_origen: '',
        observaciones: '',
        materiales: [],
        errors: []
      },
      inventarios: [],
      areas_destino: [],
      cargandoInventarios: false,
      transfiriendo: false,
    }
  },

  computed: {
    areaOrigenSeleccionada: function() {
      return this.transferenciaForm.area_origen.length > 0;
    },

    tieneInventarios: function() {
      return this.inventarios.length;
    },

    inventariosATransferir: function() {
      return this.inventarios.filter(function(inventario) {
        if (inventario.hasOwnProperty('cantidad')) {
          return inventario.cantidad.length;
        }
        return false;
      });
    }
  },

  ready: function() {
    this.fetchAreasDestino();
  },

  methods: {
    fetchAreasDestino: function() {
      this.$http.get('/api/areas')
          .success(function (areas) {
            this.areas_destino = areas;
          });
    },

    clearFormErrors: function() {
      this.transferenciaForm.errors = [];
    },

    fetchMateriales: function() {
      this.clearFormErrors();
      this.transferenciaForm.materiales = [];
      this.inventarios = [];

      this.cargandoInventarios = true;

      this.$http.get('/api/areas/' + this.transferenciaForm.area_origen)
          .success(function (area) {
            this.cargandoInventarios = false;
            this.inventarios = area.materiales || [];
          })
          .error(function (errors) {
            this.cargandoInventarios = false;
            App.setErrorsOnForm(this.transferenciaForm, errors);
          });
    },

    transferir: function(e) {
      e.preventDefault();

      this.clearFormErrors();
      this.transfiriendo = true;
      this.transferenciaForm.materiales = this.inventariosATransferir;

      this.$http.post('/transferencias', this.transferenciaForm)
          .success(function (response) {
            window.location = response.path;
          })
          .error(function (errors) {
            this.transfiriendo = false;
            App.setErrorsOnForm(this.transferenciaForm, errors);
          });
    }
  }
});