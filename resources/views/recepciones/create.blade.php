@extends('layout')

@section('content')
  <h1>Nueva Recepción</h1>
  <hr>
  
  @include('partials.errors')

  <div id="app">
    <recepcion-screen inline-template>
      <form action="{{ route('recepciones.store') }}" method="POST" accept-charset="UTF-8">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <div id="form-errors">
          <div class="alert alert-danger" v-if="recepcionForm.errors.length">
            <ul>
              <li v-repeat="error in recepcionForm.errors">@{{ error }}</li>
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- Proveedor Form Input -->
            <div class="form-group">
              {!! Form::label('proveedor', 'Proveedor:') !!}
              {!! Form::select('proveedor', $proveedores, null, ['class' => 'form-control', 'required', 'v-model' => 'recepcionForm.proveedor']) !!}
              </select>
            </div>

            <!-- Orden Compra Form Input -->
            <div class="form-group">
              {!! Form::label('orden_compra', 'Folio Orden de Compra:') !!}
              {!! Form::select('orden_compra', $ordenes, null, ['class' => 'form-control', 'required', 'v-model' => 'recepcionForm.orden_compra']) !!}
            </div>

            <!-- Fecha Recepcion Form Input -->
            <div class="form-group">
              {!! Form::label('fecha_recepcion', 'Fecha de Recepción:') !!}
              {!! Form::date('fecha_recepcion', date('Y-m-d'), ['class' => 'form-control', 'required', 'v-model' => 'recepcionForm.fecha_recepcion']) !!}
            </div>

            <div class="row">
              <div class="col-sm-6">
                <!-- Persona que Recibe Form Input -->
                <div class="form-group">
                  {!! Form::label('persona_recibe', 'Persona que Recibe:') !!}
                  {!! Form::text('persona_recibe', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.persona_recibe']) !!}
                </div>

                <!-- Observaciones Form Input -->
                <div class="form-group">
                  {!! Form::label('observaciones', 'Observaciones:') !!}
                  {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 4, 'v-model' => 'recepcionForm.observaciones']) !!}
                </div>
              </div>
              <div class="col-sm-6">
                <!-- Referencia Documento Form Input -->
                <div class="form-group">
                  {!! Form::label('referencia_documento', 'Referencia de Documento:') !!}
                  {!! Form::text('referencia_documento', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.referencia_documento']) !!}
                </div>
                <!-- Orden Embarque Form Input -->
                <div class="form-group">
                  {!! Form::label('orden_embarque', 'Orden de Embarque:') !!}
                  {!! Form::text('orden_embarque', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.orden_embarque']) !!}
                </div>
                <!-- Numero Pedido Form Input -->
                <div class="form-group">
                  {!! Form::label('numero_pedido', 'Numero de Pedido:') !!}
                  {!! Form::text('numero_pedido', null, ['class' => 'form-control', 'v-model' => 'recepcionForm.numero_pedido']) !!}
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">Artículos a Recibir</h4>
              </div>

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
                        <button class="btn btn-xs btn-danger" v-on="click: removeMaterial(material, $index)">
                          <i class="fa fa-times"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div> 
        
        <hr>
        <div class="form-group">
          <button class="btn btn-primary" type="submit" v-on="click: recibir" v-attr="disabled: recibiendo">
            <span v-if="! recibiendo">Recibir Artículos</span>
            <span v-if="recibiendo"><i class="fa fa-spinner fa-spin"></i> Recibiendo Articulos</span>
          </button>
        </div>
      </form>
    </recepcion-screen>
  </div>
  
  <script id="busqueda-materiales-template" type="x-template">
    <input class="form-control input-sm" type="text" placeholder="Escriba numero de parte o nombre del articulo"
      v-el="material"
      v-model="material.descripcion">
  </script>

@stop

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
  <script src="https://cdn.jsdelivr.net/typeahead.js/0.11.1/typeahead.jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.14/vue.js"></script>
  <script src="http://cdn.jsdelivr.net/vue.validator/1.4.3/vue-validator.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.16/vue-resource.min.js"></script>
  <script>
    window.App = { csrfToken: '{{ csrf_token() }}' };
    Vue.http.headers.common['X-CSRF-TOKEN'] = App.csrfToken;

    var validator = window['vue-validator'];
    var resource = window['vue-resource'];

    Vue.use(validator);

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
  
    Vue.component('recepcion-screen', {

      data: function () {
        return {
          recepcionForm: {
            proveedor: '',
            orden_compra: '',
            fecha_recepcion: '',
            referencia_documento: '',
            orden_embarque: '',
            numero_pedido: '',
            persona_recibe: '',
            observaciones: '',
            materiales: [],
            errors: []
          },
          nuevoMaterial: {
            id: null,
            numero_parte: '',
            descripcion: '',
            cantidad: '',
            precio: ''
          },
          recibiendo: false
        }
      },

      validator: {
        validates: {
          numeric: function (val) {
            return /^\d+(\.\d+)?$/.test(val)
          }
        }
      },

      computed: {
        formIsValid: function () {
          return this.dirty && this.valid && this.nuevoMaterial.id;
        },
        sinMateriales: function () {
          return !this.recepcionForm.materiales.length;
        }
      },

      methods: {
        setMaterial: function (material) {
          this.nuevoMaterial.id = material.id;
          this.nuevoMaterial.numero_parte = material.numero_parte;
          this.nuevoMaterial.descripcion = material.descripcion;
        },

        addMaterial: function (e) {
          e.preventDefault();
          
          if(this.formIsValid) {
            this.recepcionForm.materiales.push(this.nuevoMaterial);
            this.nuevoMaterial = { id: null, numero_parte: '', descripcion: '', cantidad: '', precio: '' };
            this.$broadcast('material-agregado', this);
          }
        },

        removeMaterial: function (material, index) {
          this.recepcionForm.materiales.$remove(index);
        },

        recibir: function (e) {
          e.preventDefault();

          this.recibiendo = true;

          this.$http.post('/recepcion-articulos', this.recepcionForm)
              .success(function(response) {
                window.location = response.path;
              })
              .error(function(errors) {
                this.recibiendo = false;
                this.recepcionForm.errors = _.flatten(_.toArray(errors));
              });
        },

        search: function () {
          console.log('searching for: ' + this.nuevoMaterial.descripcion);
          this.$http.get('/api/materiales?buscar='+this.nuevoMaterial.descripcion).success(function (materiales) {
            this.$set('nuevoMaterial.materiales', materiales);
          }).error(function (error) {
            console.error(error);
          });
        }
      }
    });

    new Vue({ el: '#app' });
  </script>
@stop