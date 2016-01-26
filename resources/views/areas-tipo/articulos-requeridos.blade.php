@extends('areas-tipo.layout')

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
      <a href="{{ route('requerimientos.area.seleccion', [$tipo]) }}" class="btn btn-success pull-right" style="margin-right: 5px">
        <i class="fa fa-plus"></i> Agregar Artículos desde Área Tipo Existente
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
          <th>Precio Estimado</th>
          <th>Moneda Nativa</th>
          <th>Moneda Homologada USD ({{ round($tipo_cambio, 2) }})</th>
          <th>Cantidad Comparativa</th>
          <th>Precio Comparativa</th>
          <th>Moneda Nativa</th>
          <th>Existe para Comparativa?</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tipo->materialesRequeridos->sortBy('material.descripcion') as $key => $requerido)
          <tr>
            <td><input type="checkbox" name="selected_articulos[{{ $requerido->id }}]" value="{{ $requerido->id }}"></td>
            <td>{{ $key + 1 }}</td>
            <td>{{ $requerido->material->numero_parte }}</td>
            <td><a href="{{ route('articulos.edit', [$requerido->id_material]) }}">{{ $requerido->material->descripcion }}</a></td>
            <td>{{ $requerido->material->unidad }}</td>
            <td>
              <input
                type="text" class="form-control input-sm" 
                name="articulos[{{ $requerido->id }}][cantidad_requerida]" 
                value="{{ $requerido->cantidad_requerida }}">
            </td>
            <td>
              <input
                type="text" class="form-control input-sm" 
                name="articulos[{{ $requerido->id }}][precio_estimado]" 
                value="{{ $requerido->precio_estimado }}">
            </td>
            <td>
              {!! Form::select('articulos['.$requerido->id.'][id_moneda]', $monedas, $requerido->id_moneda, ['placeholder' => 'Elija una moneda...']) !!}
            </td>
            <td class="text-right">
              {{ round($requerido->getImporteEstimado($tipo_cambio), 2) }}
            </td>
            <td>
              <input
                type="text" class="form-control input-sm"
                name="articulos[{{ $requerido->id }}][cantidad_comparativa]"
                value="{{ $requerido->cantidad_comparativa }}"
              >
            </td>
            <td>
              <input
                type="text" class="form-control input-sm"
                name="articulos[{{ $requerido->id }}][precio_comparativa]"
                value="{{ $requerido->precio_comparativa }}"
              >
            </td>
            <td>
              {!! Form::select('articulos['.$requerido->id.'][id_moneda_comparativa]', $monedas, $requerido->id_moneda_comparativa, ['placeholder' => 'Elija una moneda...']) !!}
            </td>
            <td class="text-center">
              {!! Form::checkbox('articulos['.$requerido->id.'][existe_para_comparativa]', 1, $requerido->existe_para_comparativa) !!}
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