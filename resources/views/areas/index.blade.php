@extends('layout')

@section('content')
  <h1>Áreas
    @if (!Auth::user()->hasRole('consulta_provisional'))  
    <a href="{{ route('areas.create', Request::has('area') ? ['dentro_de' => Request::get('area')] : []) }}"
      class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Área</a>
    @endif  
  </h1>
  <hr>

  @include('areas.partials.breadcrumb')

  <table class="table table-striped">
    <tbody>
      @foreach($descendientes as $descendiente)
        <tr>
          <td>
              @if(count($descendiente->almacen) == 1)
              <span class="glyphicon glyphicon-home" style="padding: 5px" title="Relacionado con almacén en SAO ({{$descendiente->almacen->descripcion}})" data-toggle="tooltip" ></span>
              @endif
              @if(count($descendiente->concepto) == 1)
              <span class="glyphicon glyphicon-indent-left" style="padding: 5px" title="Relacionado con concepto en SAO " data-toggle="tooltip"></span>
              @endif
              @if(count($descendiente->areas_hijas)>0)
            <a href="{{ route('areas.index', ['area='.$descendiente->id]) }}">{{ $descendiente->nombre }}</a>
            @else
            <a href="{{ route('areas.edit', [$descendiente]) }}" >{{ $descendiente->nombre }}</a>
            @endif
            
            
            
          </td>
          <td style="text-align: right; width: 150px">
              @if($descendiente->acumulador->cantidad_requerida > 0)
              <small class="text-muted">Estado Asignación de Artículos Esperados ({{number_format($descendiente->acumulador->cantidad_asignada,2,".", ",")}}/{{number_format($descendiente->acumulador->cantidad_requerida,2,".", ",")}}):</small>
              @endif
          </td>
          <td style="width: 100px; text-align: center">
              @if($descendiente->acumulador->cantidad_requerida > 0)
                <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped {{ $descendiente->acumulador->progress_bar_estado_asignacion_class }}" 
                      role="progressbar"
                      aria-valuenow="{{ $descendiente->acumulador->porcentaje_asignacion * 100 }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($descendiente->acumulador->porcentaje_asignacion * 100) }}%;">
                      {{ round($descendiente->acumulador->porcentaje_asignacion * 100) }}%
                    </div>
                  </div>
                @endif
            </td>
            <td style="text-align: right; width: 150px">
                @if($descendiente->acumulador->cantidad_asignada > 0)
              <small class="text-muted">Estado Validación de Artículos Asignados ({{number_format($descendiente->acumulador->cantidad_validada,2,".", ",")}}/{{number_format($descendiente->acumulador->cantidad_asignada,2,".", ",")}}):</small>
              @endif
          </td>
            <td style="width: 100px; text-align: center" >
                @if($descendiente->acumulador->cantidad_asignada > 0)
          
                   
                    <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped {{ $descendiente->acumulador->progress_bar_estado_validacion_class }}" 
                      role="progressbar"
                      aria-valuenow="{{ $descendiente->acumulador->porcentaje_validacion*100 }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($descendiente->acumulador->porcentaje_validacion*100) }}%;">
                      {{ round($descendiente->acumulador->porcentaje_validacion*100) }}%
                    </div>
                   </div>
                   
                   
                   
                @endif
            </td>
            <td style="text-align: right; width: 150px">
                @if($descendiente->acumulador->cantidad_areas_cerrables > 0)
              <small class="text-muted">Estado Cierre de Áreas Validadas Totalmente({{number_format($descendiente->acumulador->cantidad_areas_cerradas,2,".", ",")}}/{{number_format($descendiente->acumulador->cantidad_areas_cerrables,2,".", ",")}}):</small>
              @endif
          </td>
            <td style="width: 100px; text-align: center" >
              @if($descendiente->acumulador->cantidad_areas_cerrables > 0)
          
                   
                   <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped {{ $descendiente->acumulador->progress_bar_estado_cierre_class }}" 
                      role="progressbar"
                      aria-valuenow="{{ $descendiente->acumulador->porcentaje_cierre*100 }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($descendiente->acumulador->porcentaje_cierre*100) }}%;">
                      {{ round($descendiente->acumulador->porcentaje_cierre*100) }}%
                    </div>
                  </div>
                   
                   
                   
                @endif
            </td>
            <td style="text-align: right; width: 150px">
                @if($descendiente->acumulador->cantidad_areas_cerradas > 0)
              <small class="text-muted">Estado Entrega de Áreas Cerradas({{number_format($descendiente->acumulador->cantidad_areas_entregadas,2,".", ",")}}/{{number_format($descendiente->acumulador->cantidad_areas_cerradas,2,".", ",")}}):</small>
              @endif
          </td>
            <td style="width: 100px; text-align: center" >
              @if($descendiente->acumulador->cantidad_areas_cerradas > 0)
          
                   
                   <div class="progress">
                    <div
                      class="progress-bar progress-bar-striped {{ $descendiente->acumulador->progress_bar_estado_entrega_class }}" 
                      role="progressbar"
                      aria-valuenow="{{ $descendiente->acumulador->porcentaje_entrega*100 }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 2.5em; width: {{ round($descendiente->acumulador->porcentaje_entrega*100) }}%;">
                      {{ round($descendiente->acumulador->porcentaje_entrega*100) }}%
                    </div>
                  </div>
                   
                   
                   
                @endif
            </td>
            <td style="width: 100px">
                <div class="btn-toolbar pull-right">
            @if (!Auth::user()->hasRole('consulta_provisional'))    
              <div class="btn-group btn-group-xs">
                <a href="{{ route('areas.edit', [$descendiente]) }}" class="btn btn-primary btn-xs">
                  <span class="fa fa-pencil"></span>
                </a>
              </div>
            @endif
              <div class="btn-group btn-group-xs">
                @if (!Auth::user()->hasRole('consulta_provisional'))  
                <form action="{{ route('areas.down', $descendiente) }}" method="POST" accept-charset="UTF-8" >
                  <input type="hidden" name="_method" value="PATCH">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <button type="button" class="btn btn-warning btn-xs btn_down" >
                    <span class="fa fa-arrow-down"></span>
                  </button>
                  <input type="hidden" name="move_down" value="1">
                </form>
                
                @endif
              </div>

              <div class="btn-group btn-group-xs">
                @if (!Auth::user()->hasRole('consulta_provisional'))  
                <form action="{{ route('areas.up', $descendiente) }}" method="POST" accept-charset="UTF-8">
                  <input type="hidden" name="_method" value="PATCH">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <button type="button" class="btn btn-warning btn-xs btn_up">
                    <span class="fa fa-arrow-up"></span>
                  </button>
                  <input type="hidden" name="move_up" value="1">
                </form>
                @endif
              </div>
            </div>
            </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  @if($area and count($areas_tipo) > 0)
    @include('areas.partials.resumen')
  @endif
@stop
@section('scripts')
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
$("button.btn_down").off().on("click", function (e){
    $frm = $(this).parents("form");
    var postData = $frm.serialize();
    var formURL = $frm.attr("action");
    $.ajax({
        url: formURL,
        type: "patch",
        data: postData,
        success: function (data)
        {
          location.reload();
        }
    });
    e.preventDefault();
});

$("button.btn_up").off().on("click", function (e){
    $frm = $(this).parents("form");
    var postData = $frm.serialize();
    var formURL = $frm.attr("action");
    $.ajax({
        url: formURL,
        type: "patch",
        data: postData,
        success: function (data)
        {
          location.reload();
        }
    });
    e.preventDefault();
});

$(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
@stop
