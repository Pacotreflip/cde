Vue.component('recepcion-screen', {
  data: function () {
    return {
      recepcionForm: {
        proveedor: '',
        orden_compra: '',
        fecha_recepcion: '',
        area_almacenamiento: '',
        numero_remision_factura: '',
        orden_embarque: '',
        numero_pedimento: '',
        persona_recibio: '',
        observaciones: '',
        materiales: [],
        errors: []
      },
      compra: {
        proveedor: {},
        materiales: []
      },
      // nuevoMaterial: {
      //   id: null,
      //   numero_parte: '',
      //   descripcion: '',
      //   cantidad: '',
      //   precio: ''
      // },
      recibiendo: false,
      cargando: false
    }
  },

  // validator: {
  //   validates: {
  //     numeric: function (val) {
  //       return /^\d+(\.\d+)?$/.test(val)
  //     }
  //   }
  // },

  computed: {
    // formIsValid: function () {
    //   return this.dirty && this.valid && this.nuevoMaterial.id;
    // },
    // sinMateriales: function () {
    //   return !this.recepcionForm.materiales.length;
    // },

    articulosARecibir: function () {
      return this.compra.materiales.filter(function(material) {
        return material.cantidad_recibir.length;
      });
    }
  },

  methods: {
    // setMaterial: function (material) {
    //   this.nuevoMaterial.id = material.id;
    //   this.nuevoMaterial.numero_parte = material.numero_parte;
    //   this.nuevoMaterial.descripcion = material.descripcion;
    // },

    // addMaterial: function (e) {
    //   e.preventDefault();
      
    //   if(this.formIsValid) {
    //     this.recepcionForm.materiales.push(this.nuevoMaterial);
    //     this.$broadcast('material-agregado', this);
    //     this.nuevoMaterial = { id: null, numero_parte: '', descripcion: '', cantidad: '', precio: '' };
    //   }
    // },

    // removeMaterial: function (material, index, e) {
    //   e.preventDefault();
    //   this.recepcionForm.materiales.$remove(index);
    // },

    fetchMateriales: function (e) {
      this.errors = [];
      this.cargando = true;
      this.compra = { proveedor: {}, materiales: [] };

      this.$http.get('/api/ordenes-compra/' + this.recepcionForm.orden_compra)
          .success(function (response) {
            this.cargando = false;
            this.compra = response;
            this.recepcionForm.proveedor = response.proveedor.id_empresa;
          })
          .error(function (errors) {
            this.cargando = false;
          })
    },

    recibir: function (e) {
      e.preventDefault();

      swal({
        title: "Desea continuar?", 
        text: "Esta seguro de que la información es correcta?", 
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        confirmButtonColor: "#ec6c62"
      }, () => {
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
      });
    }
  }
});