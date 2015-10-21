<div class="col-sm-6">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">Artículos a Recibir</h4>
    </div>
    <form>
    <div class="panel-body">
      <div class="form-group">
        <busqueda-materiales when-selected="@{{ setMaterial }}"></busqueda-materiales>
      </div>

      <div class="row">
        <div class="col-sm-4">
          <div class="form-group" v-class="has-error: validation.nuevoMaterial.cantidad.dirty && validation.nuevoMaterial.cantidad.invalid">
            <input class="form-control input-sm" name="cantidad" type="text" placeholder="Cantidad"
              v-model="nuevoMaterial.cantidad"
              v-validate="required, numeric">
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group" v-class="has-error: validation.nuevoMaterial.precio.dirty && validation.nuevoMaterial.precio.invalid">
            <input class="form-control input-sm" name="precio" type="text" placeholder="Precio"
              v-model="nuevoMaterial.precio"
              v-validate="required, numeric">
          </div>
        </div>
        
        <div class="col-sm-4">
          <button class="btn btn-sm btn-success"
            v-on="click: addMaterial"
            v-attr="disabled: !formIsValid">Agregar
          </button>
        </div>

        <p class="form-errors">
          <p
            class="text-danger"
            v-if="validation.nuevoMaterial.cantidad.dirty && validation.nuevoMaterial.cantidad.invalid">El campo cantidad es inválido</p>
          <p
            class="text-danger"
            v-if="validation.nuevoMaterial.precio.dirty && validation.nuevoMaterial.precio.invalid">El campo precio es inválido</p>
        </p>
      </div>
    </form>  
      <div class="alert alert-info" v-if="sinMateriales">
        <p>Agregue los articulos que va a recibir</p>
      </div>

      <table class="table table-condensed table-striped" v-if="recepcionForm.materiales.length">
        <thead>
          <tr>
            <th>No. Parte</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-repeat="material in recepcionForm.materiales">
            <td>@{{ material.numero_parte }}</td>
            <td>
              @{{ material.descripcion }}
              <input type="hidden" name="materiales[@{{ material.id }}]" value="@{{ material.id }}">
            </td>
            <td>
              @{{ material.cantidad }}
              <input type="hidden" name="materiales[@{{ material.id }}][cantidad]" value="@{{ material.cantidad }}"
                v-model="material.cantidad">
            </td>
            <td>
              @{{ material.precio }}
              <input type="hidden" name="materiales[@{{ material.id }}][precio]" value="@{{ material.precio }}"
                v-model="material.precio">
            </td>
            <td>
              <button class="btn btn-xs btn-danger" v-on="click: removeMaterial(material, $index, $event)">
                <i class="fa fa-times"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script id="busqueda-materiales-template" type="x-template">
  <input class="form-control input-sm" type="text" placeholder="Escriba numero de parte o nombre del articulo"
    v-el="material"
    v-model="material.descripcion">
</script>

<script src="https://cdn.jsdelivr.net/typeahead.js/0.11.1/typeahead.jquery.min.js"></script>

Vue.component('busqueda-materiales', {
  template: document.querySelector('#busqueda-materiales-template'),

  data: function () {
    return {
      material: {
        id: null,
        numero_parte: '',
        descripcion: ''
      }
    }
  },
  
  props: {
    whenSelected: Function,
  },

  ready: function () {
    this.$on('material-agregado', this.clear);

    $(this.$$.material)
      .typeahead({
        minLength: 2,
        highlight: true,
        hint: false
      }, 
      {
        source: function (query, process, async) {
          $.getJSON('/api/materiales', {buscar: query}, function (materiales) {
            return async(materiales);
          });
        },
        display: 'descripcion',
        templates: {
          suggestion: function (material) {
            return '<div>' +
                      '<h5>' + material.descripcion + '</h5>' +
                      '<h6><em>' + material.numero_parte + '</em></h6>' +
                    '</div>';
          },
          notFound: function (context) {
            return '<div class="tt-notfound">' +
                    'No se encontro: ' + context.query +
                   '</div>';
          }
        }
      })
      .on('typeahead:select', function (ev, suggestion) {
        this.material.id = suggestion.id_material;
        this.material.numero_parte = suggestion.numero_parte;
        this.material.descripcion = suggestion.descripcion;
        this.whenSelected(this.material);
      }.bind(this));
  },
  methods: {
    clear: function () {
      this.material = { id: null, numero_parte: '', descripcion: '' };
      $(this.$$.material).typeahead('val', '');
      this.$$.material.focus();
    }
  }
});