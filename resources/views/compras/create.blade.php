@extends('layout')

@section('content')
  <h1>Nueva Compra</h1>
  <hr>

  {!! Form::open() !!}
    <!-- Proveedor Form Input -->
    <div class="form-group">
      {!! Form::label('proveedor', 'Proveedor:') !!}
      {!! Form::select('proveedor', $proveedores, null, ['class' => 'form-control']) !!}
    </div>

    <div class="row">
      <div class="col-sm-6">
        <!-- Fecha Form Input -->
        <div class="form-group">
          {!! Form::label('fecha', 'Fecha:') !!}
          {!! Form::text('fecha', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Orden Compra Form Input -->
        <div class="form-group">
          {!! Form::label('orden_compra', 'Orden Compra:') !!}
          {!! Form::select('orden_compra', $ordenes, null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-sm-6">
          <!-- Fecha Entrega Form Input -->
          <div class="form-group">
            {!! Form::label('fecha_entrega', 'Fecha Entrega:') !!}
            {!! Form::text('fecha_entrega', null, ['class' => 'form-control']) !!}
          </div>

          <!-- Documento Form Input -->
          <div class="form-group">
            {!! Form::label('documento', 'Documento:') !!}
            {!! Form::text('documento', null, ['class' => 'form-control']) !!}
          </div>
      </div>
    </div>

    <!-- Observaciones Form Input -->
    <div class="form-group">
      {!! Form::label('observaciones', 'Observaciones:') !!}
      {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>
    
    <br>

    <section id="articulos">
      <h3>Articulos</h3>
      <hr>
      <div class="form-inline">
        <!-- Articulo Form Input -->
        <div class="form-group">
            <label for="articulo">Articulo:</label>
            <select name="articulo" class="form-control" v-model="articuloForm.articulo">
              @foreach($materiales as $key => $material)
                <option value="{{ $key }}">{{ $material }}</option>
              @endforeach
            </select>
        </div>

        <!-- Cantidad Form Input -->
        <div class="form-group">
          <label for="cantidad">Cantidad:</label>
          <input class="form-control" name="cantidad" type="text" v-model="articuloForm.cantidad">
        </div>

        <div class="form-group">
            <input class="btn btn-success" value="Agregar" type="button" v-on="click: addArticulo">
        </div>
      </div>
      <pre>
        @{{ $data | json }}
      </pre>
      <hr>
      <table class="table">
        <thead>
          <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-repeat="articulo in articulos">
            <td>
              @{{ articulo.descripcion }}
              <input type="hidden" name="" v-model="articulo.descripcion">
            </td>
            <td>
              <input type="text" name="" v-model="articulo.cantidad">
            </td>
            <td><a href="" v-on="click: removeArticulo(articulo, $event)"><i class="fa fa-times"></i></a></td>
          </tr>
        </tbody>
      </table>
    </section>

    <div class="form-group">
      {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    </div>
  {!! Form::close() !!}
@stop

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.14/vue.js"></script>

  <script>
    new Vue({
      el: '#articulos',
      data: {
        articuloForm: {
          articulo: '',
          cantidad: ''
        },
        articulos: [
          {
            descripcion: 'test',
            cantidad: 5
          }
        ]
      },
      methods: {
        removeArticulo: function(articulo, e) {
          e.preventDefault();
          this.articulos.$remove(articulo);
        },

        addArticulo: function(e) {
          e.preventDefault();

          this.articulos.push({
            descripcion: this.articuloForm.articulo,
            cantidad: this.articuloForm.cantidad,
          });
        },

        clearForm: function() {
          this.articuloForm.articulo = '';
          this.articuloForm.cantidad = '';
        }
      }
    });
  </script>
@stop