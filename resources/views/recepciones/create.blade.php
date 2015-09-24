@extends('layout')

@section('content')
  <h1>Nueva Recepción</h1>
  <hr>
  
  @include('partials.errors')

  <form action="{{ route('recepciones.store') }}" method="POST" accept-charset="UTF-8">
    <input name="_token" type="hidden" value="{{ csrf_token() }}">
    
    <div class="row">
      <div class="col-sm-6">
        <!-- Proveedor Form Input -->
        <div class="form-group">
          {!! Form::label('proveedor', 'Proveedor:') !!}
          {!! Form::select('proveedor', $proveedores, null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-sm-3">
        <!-- Orden Compra Form Input -->
        <div class="form-group">
          {!! Form::label('orden_compra', 'Folio Orden de Compra:') !!}
          {!! Form::select('orden_compra', $ordenes, null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-sm-3">
        <!-- Fecha Recepcion Form Input -->
        <div class="form-group">
          {!! Form::label('fecha_recepcion', 'Fecha de Recepción:') !!}
          {!! Form::date('fecha_recepcion', date('Y-m-d'), ['class' => 'form-control']) !!}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <!-- Persona que Recibe Form Input -->
        <div class="form-group">
          {!! Form::label('persona_recibe', 'Persona que Recibe:') !!}
          {!! Form::text('persona_recibe', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Observaciones Form Input -->
        <div class="form-group">
          {!! Form::label('observaciones', 'Observaciones:') !!}
          {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 4]) !!}
        </div>
      </div>
      <div class="col-sm-6">
        <!-- Referencia Documento Form Input -->
        <div class="form-group">
          {!! Form::label('referencia_documento', 'Referencia de Documento:') !!}
          {!! Form::text('referencia_documento', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Orden Embarque Form Input -->
        <div class="form-group">
          {!! Form::label('orden_embarque', 'Orden de Embarque:') !!}
          {!! Form::text('orden_embarque', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Numero Pedido Form Input -->
        <div class="form-group">
          {!! Form::label('numero_pedido', 'Numero de Pedido:') !!}
          {!! Form::text('numero_pedido', null, ['class' => 'form-control']) !!}
        </div>
      </div>
    </div>    
    
    <section id="articulos">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">Articulos a Recibir</h4>
        </div>
        <div class="panel-body form-inline">
          <!-- Nuevo Articulo Form Input -->
          <div class="form-group">
            <input class="form-control" name="articulo" id="typeahead" type="text" placeholder="Articulo"
              v-el="nuevoArticulo"
              v-model="articulo.descripcion">
          </div>

          <!-- Cantidad Form Input -->
          <div class="form-group" v-class="has-error: hasCantidadError">
            <input class="form-control" name="cantidad" type="text" placeholder="Cantidad"
              v-el="nuevaCantidad"
              v-model="articulo.cantidad">
          </div>

          <!-- Precio Form Input -->
          <div class="form-group" v-class="has-error: hasPrecioError">
            <input class="form-control" name="precio" type="text" placeholder="Precio"
              v-el="nuevoPrecio"
              v-model="articulo.precio">
          </div>

          <button class="btn btn-sm btn-success" v-on="click: addArticulo"><i class="fa fa-plus"></i> Agregar Articulo</button>

          <hr>

          <table class="table table-condensed table-striped">
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
              <tr v-repeat="articulo in articulos">
                <td>@{{ articulo.numero_parte }}</td>
                <td>
                  @{{ articulo.descripcion }}
                  <input type="hidden" name="articulos[@{{ articulo.id }}]" value="@{{ articulo.id }}">
                </td>
                <td>
                  <input type="text" name="articulos[@{{ articulo.id }}][cantidad]" value="@{{ articulo.cantidad }}"
                    v-model="articulo.cantidad">
                </td>
                <td>
                  <input type="text" name="articulos[@{{ articulo.id }}][precio]" value="@{{ articulo.precio }}"
                    v-model="articulo.precio">
                </td>
                <td>
                  <button class="btn btn-xs btn-danger" v-on="click: deleteArticulo(articulo, $index)">
                    <i class="fa fa-times"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          
          <pre>
            @{{ $data.articulos | json 4 }}
          </pre>

        </div>
      </div>
    </section>


    <hr>
    <div class="form-group">
      <input class="btn btn-primary" type="submit" value="Guardar">
    </div>
  </form>
@stop

@section('scripts')
  <script src="https://cdn.jsdelivr.net/typeahead.js/0.11.1/typeahead.jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.14/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.1.16/vue-resource.min.js"></script>
  <script>
  new Vue({
    el: '#articulos',

    data: {
      articulo: {
        id: null,
        numero_parte: '',
        descripcion: '',
        cantidad: '',
        precio: ''
      },
      articulos: [],
      hasCantidadError: false,
      hasPrecioError: false,
    },

    ready: function() {
      $('#typeahead')
        .typeahead({
          minLength: 2,
          highlight: true,
        }, 
        {
          source: function (query, process, async) {
            $.getJSON('/api/materiales', {buscar: query}, function (materiales) {
              return async(materiales);
            });
          },
          display: 'descripcion',
          templates: {
            suggestion: function(material) {
              return '<div>' +
                        '<h5>' + material.descripcion + '</h5>' +
                        '<h6><em>' + material.numero_parte + '</em></h6>' +
                      '</div>';
            },
            notFound: function(context) {
              return '<div class="tt-notfound">' +
                      'No se encontro: ' + context.query +
                     '</div>';
            }
          }
        })
        .on('typeahead:select', function(ev, suggestion) {
          this.articulo.id = suggestion.id_material;
          this.articulo.numero_parte = suggestion.numero_parte;
          this.articulo.descripcion = suggestion.descripcion;
        }.bind(this));

    },

    computed: {
      formIsValid: function() {
        if(!this.articulo.cantidad) {
          this.hasCantidadError = true;
        } else {
          this.hasCantidadError = false;
        }

        if(!this.articulo.precio) {
          this.hasPrecioError = true;
        } else {
          this.hasPrecioError = false;
        }

        return ! this.hasCantidadError && ! this.hasPrecioError;
      }
    },

    methods: {

      addArticulo: function(e) {
        e.preventDefault();
        
        if(this.formIsValid) {
          this.articulos.push(this.articulo);
          this.articulo = { id: null, numero_parte: '', descripcion: '', cantidad: '', precio: '' };
          this.$$.nuevoArticulo.focus();
        }
      },

      deleteArticulo: function(articulo, index) {
        this.articulos.$remove(index);
      },

      search: function () {
        console.log('searching for: ' + this.articulo.descripcion);
        this.$http.get('/api/materiales?buscar='+this.articulo.descripcion).success(function(materiales) {
          this.$set('articulos', materiales);
        }).error(function(error) {
          console.error(error);
        });
      }

    }
  });
  </script>
@stop