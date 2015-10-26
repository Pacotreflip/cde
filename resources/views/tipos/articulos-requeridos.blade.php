@extends('tipos.layout')

@section('main-content')
  {!! Form::open(['route' => ['requerimientos.update', $tipo], 'method' => 'PATCH']) !!}
    <div class="form-inline">
      <div class="form-group">
          <select name="action" id="action" class="form-control">
            <option value="" selected="selected">--------</option>
            <option value="delete_selected">Borrar seleccionados</option>
          </select>
      </div>
      <button type="submit" class="btn btn-default">Aplicar</button>
      
      <a href="{{ route('requerimientos.seleccion', [$tipo]) }}" class="btn btn-success pull-right">
          <i class="fa fa-plus"></i> Agregar Artículos
      </a>
    </div>
    
    <hr>

    <table class="table table-striped table-hover table-condensed">
      <thead>
        <tr>
          <td><input type="checkbox" id="select_all" title="Seleccionar todos"/></td>
          <td>#</td>
          <th>No. Parte</th>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Cantidad Requerida</th>
          <th>Costo Estimado</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tipo->materiales as $key => $material)
          <tr>
            <td><input type="checkbox" name="selected_articulos[]" value="{{ $material->id_material }}"></td>
            <td>{{ $key + 1 }}</td>
            <td>{{ $material->numero_parte }}</td>
            <td><a href="{{ route('articulos.edit', [$material]) }}">{{ $material->descripcion }}</a></td>
            <td>{{ $material->unidad }}</td>
            <td>
              <input type="text" class="form-control input-sm" 
                     name="articulos[{{ $material->id_material }}][cantidad_requerida]" 
                     value="{{ $material->pivot->cantidad_requerida }}">
            </td>
            <td>
              <input type="text" class="form-control input-sm" 
                     name="articulos[{{ $material->id_material }}][costo_estimado]" 
                     value="{{ $material->pivot->costo_estimado }}">
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Guardar Cambios">
      </div>
    {!! Form::close() !!}
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