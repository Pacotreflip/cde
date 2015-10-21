@extends('layout')

@section('content')
  @include('tipos.partials.breadcrumb')

  <h1>Asignación de Requerimientos</h1>
  <hr>

  <div class="row">
    <div class="col-md-2">
      @include('tipos.nav')
    </div>
    
    <div class="col-md-10">
      <h3>Artículos Requeridos
          <a href="{{ route('requerimientos.seleccion', [$tipo]) }}" class="btn btn-success pull-right">
              <i class="fa fa-plus"></i> Agregar Artículos
          </a>
      </h3>
      <hr>

      {!! Form::open(['route' => ['requerimientos.update', $tipo], 'method' => 'PATCH']) !!}
        <div class="form-inline">
          <div class="form-group">
              <select name="action" id="action" class="form-control">
                <option value="" selected="selected">--------</option>
                <option value="delete_selected">Borrar seleccionados</option>
              </select>
          </div>
          <button type="submit" class="btn btn-default">Aplicar</button>
        </div>
        <br>

        <table class="table table-striped table-hover table-condensed">
          <thead>
            <tr>
              <td><input type="checkbox" id="select_all" title="Seleccionar todos"/></td>
              <th>Descripción</th>
              <th>Unidad</th>
              <th>Cantidad Requerida</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tipo->materiales as $material)
              <tr>
                <td><input type="checkbox" name="selected_articulos[]" value="{{ $material->id_material }}"></td>
                <td><a href="{{ route('articulos.edit', [$material]) }}">{{ $material->descripcion }}</a></td>
                <td>{{ $material->unidad }}</td>
                <td>
                  <input type="text" class="form-control input-sm" 
                         name="articulos[{{ $material->id_material }}][cantidad]" 
                         value="{{ $material->pivot->cantidad_requerida }}">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Guardar Cambios">
        </div>
      {!! Form::close() !!}
    </div>
  </div>    
@stop

@section('scripts')
  <script>
    $('#select_all').on('change', function() {
        var checked = $(this).prop('checked');

        $(this).parents('thead')
          .next('tbody')
          .find('input[type=checkbox]')
          .prop('checked', checked);
    });
  </script>
@stop