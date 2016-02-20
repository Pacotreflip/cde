Vue.component('asignacion-screen', {
  
  data: function () {
    return {
      asignacionForm: {
        origen: '',
        nombre_area:'',
        materiales: [],
        errors: [],
      },
      area: {
        materiales: []
      },
      ruta_area: '',
      cargando: false,
      asignando: false,
    }
  },

  components: {
    'areas-tree': require('./areas-jstree.js')
  },
  
  computed: {
    articulosAAsignar () {
      return this.area.materiales.filter(function(material) {
        return material.destinos.length;
      });
    }
  },

  methods: {
    /**
     * Asigna los destinos seleccionados para un material.
     */
    setDestinos: function (material, nodes) {
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
    fetchMateriales: function (data) {
      var id_area = data.node.id;
      this.asignacionForm.origen = id_area;
      this.cargando = true;
      this.compra = {materiales: [] };
      
      this.asignacionForm.materiales = [];

      this.$http.get('/api/areas/' + id_area)
        .success(function (area) {
          this.ruta_area = area.ruta;
          this.asignacionForm.nombre_area = area.nombre;
          this.cargando = false;

          area.materiales.forEach(function (material) {
            material.destinos = [];
          });
          this.area.materiales = area.materiales 
         this.asignacionForm.materiales = area.materiales || [];
        })
        .error(function (errors) {
          this.cargandoInventarios = false;
          App.setErrorsOnForm(this.transferenciaForm, errors);
        });
    },
    
    /**
     * Confirma si se reciben los articulos.
     */
    confirmaAsignacion: function (e) {
      e.preventDefault();

      swal({
        title: "¿Desea continuar con la asignación?", 
        text: "¿Esta seguro de que la información es correcta?", 
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        confirmButtonColor: "#ec6c62"
      }, () => this.asignar() );
    },

    /**
     * Envia un request para generar la asignacion de materiales.
     */
    asignar: function () {
      this.asignando = true;
      this.asignacionForm.errors = [];
      this.asignacionForm.materiales = this.articulosAAsignar;

      this.$http.post('/asignaciones', this.asignacionForm)
          .success(function (response) {
            window.location = response.path;
          })
          .error(function (errors) {
            this.asignando = false;
            App.setErrorsOnForm(this.asignacionForm, errors);
          });
    }
  }
});