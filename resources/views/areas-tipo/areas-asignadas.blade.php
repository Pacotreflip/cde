@extends('areas-tipo.layout')

@section('main-content')
  
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <strong># de Areas Asignadas</strong>
        </div>
        <div class="panel-body">
          <p class="h2 text-center">
            {{ $tipo->conteoAreas() }}
          </p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading text-center">
          <strong>Costo Estimado por Area</strong>
        </div>
        <div class="panel-body">
          <p class="h2 text-center">
            {{ $tipo->costoEstimado() }}
          </p>
        </div>
      </div>
    </div>
  </div>
  <hr>
  
  <h4>Areas Asignadas</h4>
  <ul class="list-group">
    @foreach($tipo->areas as $area)
      <a href="{{ route('areas.edit', $area) }}" class="list-group-item">
        {{ $area->ruta() }}
      </a>
    @endforeach
  </ul>
<form method="post" id="frm_asigna_desasigna_areas" action="{{ route('tipos.actualiza_areas',$tipo) }}">
    {{ csrf_field() }}
<div class="alert alert-info" role="alert">
    <span class="glyphicon glyphicon-info-sign" style="padding-right: 5px"></span>
     Seleccione / deseleccione las áreas que estarán relacionadas con el Área Tipo y posteriormente oprima el botón: 
     <button id="btn-actualiza-areas" class="btn-sm btn-primary" type="submit" >
        <span><i class="glyphicon glyphicon-floppy-disk"></i> Guardar Cambios</span>
    </button>
</div>
  <div id="inputs">
      @foreach($tipo->areas as $area)
      <input type="hidden" name="areas[]" id="areas{{$area->id}}" value="{{$area->id}}">
      @endforeach
  </div>
    <div id="jstree_demo_div">

    </div>
  </form>
@stop
@section('scripts')
<script>
$(document).ready(function(){

$(function () { 
    $('#jstree_demo_div').jstree({
        "core":{
            "data":{
                "url" : '{{ route("areas_tipo.areasJs",$tipo->id) }}',
                "dataType" : "json"
            }
        },
        "plugins":["checkbox"]
    });
    $('#jstree_demo_div').on("changed.jstree", function (e, data) {
        //console.log(data.selected);
        $("#inputs").find("input").remove();
        $.each(data.selected, function(a,v){
            $("#inputs").append("<input type='hidden' name='areas[]' id='areas"+v+"' value='"+v+"' />");
        });
      });
    
    
});

});

</script>
@stop