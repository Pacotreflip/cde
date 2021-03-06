@extends('areas-tipo.layout_menu_hr')

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
      
      <button type="button" class="btn btn-primary pull-right descargar_excel" style="margin-left: 5px"><span class="fa fa-table" style="margin-right: 5px"></span>Descarga Excel</button>
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
          <th>Importe Moneda Homologada USD ({{ round($tipo_cambio, 2) }})</th>
          <th>Cantidad Comparativa</th>
          <th>Precio Comparativa</th>
          <th>Moneda Nativa</th>
          <th>Importe Moneda Homologada USD ({{ round($tipo_cambio, 2) }})</th>
          <th>¿Existe para Comparativa?</th>
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
              {{ $requerido->material->precio_estimado }}
            </td>
            <td>
                @if(is_object($requerido->material->moneda))
                 {{ ($requerido->material->moneda->nombre) }}
                @endif
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
              {{ $requerido->material->precio_proyecto_comparativo }}
            </td>
            <td>
                @if(is_object($requerido->material->moneda_proyecto_comparativo))
                 {{ ($requerido->material->moneda_proyecto_comparativo->nombre) }}
                @endif
            </td>
            <td class="text-right">
              {{ round($requerido->getImporteComparativa($tipo_cambio), 2) }}
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
    <form id="descargaExcel" action="{{ route("areas-tipo.articulos_requeridos_xls", $tipo->id) }}"></form>
@stop

@section('scripts')
  <script>
    $("button.descargar_excel").off().on("click", function(e){
        $("form#descargaExcel").submit();
    });
    $('#select_all').on('change', function() {
        var checked = $(this).prop('checked');

        $(this).parents('thead')
          .next('tbody')
          .find('input[type=checkbox]')
          .prop('checked', checked);
    });
  </script>
@stop