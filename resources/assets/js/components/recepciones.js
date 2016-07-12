Vue.component('recepcion-screen', {

  data:function () {
    return {
      recepcionForm: {
        proveedor: '',
        orden_compra: '',
        fecha_recepcion: '',
        numero_remision_factura: '',
        orden_embarque: '',
        numero_pedimento: '',
        persona_recibio: '',
        observaciones: '',
        opcion_recepcion: 1,
        materiales: [],
        errors: []
      },
      compra: {
        proveedor: {},
        materiales: []
      },
      recibiendo: false,
      cargando: false
    }
  },

  events: {
    'destinos-changed': function (destinos, material) {}
  },

  components: {
    'selector-destinos': require('./selector-destinos.js')
  },

  computed: {
    articulosARecibir () {
      return this.compra.materiales.filter(function(material) {
        return material.destinos.length;
      });
    }
  },

  methods: {
    /**
     * Obtiene los materiales de una orden de compra.
     */
    fetchMateriales: function (e) {
      this.recepcionForm.errors = [];
      this.cargando = true;
      this.compra = { proveedor: {}, materiales: [] };

      this.$http.get('/api/ordenes-compra/' + this.recepcionForm.orden_compra)
          .success(function (response) {
        this.cargando = false;
        this.recepcionForm.proveedor = response.proveedor.id_empresa;

        response.materiales.forEach(function (material) {
          material.destinos = [];
        });

        this.compra = response;

            this.$nextTick(() => { $('[data-toggle="tooltip"]').tooltip() });
          })
          .error(function (errors) {
        this.cargando = false;
      });
    },
    
    setDestino: function (destino, material) {
      var flag = false;
      if (!(material.destinos.length)) {
        if(destino.cantidad.trim()) {
          material.destinos.push(destino);
        }
      } else {
        material.destinos.forEach(function (d) {
            if (d.id == destino.id) {
             if(destino.cantidad.trim()){
               d = destino;    
             } else {
                material.destinos.$remove(d);
             }
             flag = true;
            }
        });
        if (flag == false) {
         if(destino.cantidad.trim()) {
            material.destinos.push(destino);
          }  
        }          
      } 
    },
    
    fetchDestinos: function (material) {
        console.log(material);
      if(material.areas_destino.length) {
        material.areas_destino = [];
        material.destinos = [];
      } else {
        this.$http.get('/api/areas/' + material.id + '/destinos').success(function (destinos) {
          material.areas_destino = destinos;
        }).error(function (error) {
        });
      }   
    },

    /**
     * Calcula la cantidad a recibir de un material de acuerdo
     * a la cantidad definida en sus destinos.
     */
    cantidadARecibir: function (material) {
      return material.destinos.reduce((prev, cur) => {
        return parseInt(prev) + parseInt(cur.cantidad);
      }, 0);
    },

    /**
     * Indica si un material es inconsistente para recepcion.
     */
    esInconsistente: function (material) {
      return this.cantidadARecibir(material) != material.cantidad_adquirida;
    },

    /**
     * Confirma si se reciben los articulos.
     */
    confirmaRecepcion: function (e) {
      e.preventDefault();

      swal({
        title: "¿Desea continuar?",
        text: "¿Esta seguro de que la información es correcta?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        confirmButtonColor: "#ec6c62"
      }, () => this.recibir() );
    },

    /**
     * Recibe los articulos.
     */
    recibir: function () {
      this.recibiendo = true;
      this.recepcionForm.errors = [];
      this.recepcionForm.materiales = this.articulosARecibir;

      this.$http.post('/recepciones', this.recepcionForm)
          .success(function (response) {
        window.location = response.path;
          })
          .error(function (errors) {
        this.recibiendo = false;
        App.setErrorsOnForm(this.recepcionForm, errors);
      });
    }
  }
});