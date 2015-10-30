Vue.component('asignacion-screen', {
  
  data: function() {
    return {
      asignacionForm: {
        origen: '',
        materiales: [],
        errors: [],
      },
      cargando: false,
      asignando: false,
    }
  },

  methods: {
    /**
     * Asigna los destinos seleccionados para un material.
     */
    setDestinos: function(material, nodes) {
      material.destinos = this.transformDestinos(nodes);
    },

    /**
     * Transforma los nodos de treejs a destinos.
     */
    transformDestinos: function (nodes) {
      var destinos = [];

      nodes.forEach(function (destino) {
        destinos.push({ id: destino.id, text: destino.text, cantidad: '', path: destino.path });
      });

      return destinos;
    },

    /**
     * Obtiene los materiales de un area.
     */
    fetchMateriales: function (area) {
      this.asignacionForm.origen = area;
      this.cargando = true;
      this.asignacionForm.materiales = [];

      this.$http.get('/api/areas/' + area)
        .success(function (area) {
          this.cargando = false;

          area.materiales.forEach(function (material) {
            material.destinos = [];
          });

          this.asignacionForm.materiales = area.materiales || [];
        })
        .error(function (errors) {
          this.cargandoInventarios = false;
          App.setErrorsOnForm(this.transferenciaForm, errors);
        });
    },

    /**
     * Envia un request para generar la asignacion de materiales.
     */
    asignar: function (e) {
      e.preventDefault();

      this.asignacionForm.errors = [];
      this.asignando = true;

      this.$http.post('/asignaciones', this.asignacionForm)
          .success(function (response) {
            this.asignando = false;
            console.log(response);
          })
          .error(function (errors) {
            this.asignando = false;
            App.setErrorsOnForm(this.asignacionForm, errors);
          });
    }
  }
});