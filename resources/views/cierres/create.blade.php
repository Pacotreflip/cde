@extends('layout')

@section('content')
<h1>Nuevo Cierre de Áreas
    <a href="{{ route('cierres.index') }}" class="btn btn-success pull-right"><i class="glyphicon glyphicon-chevron-left"></i> Regresar</a>
</h1>
<hr>
<div id="app">
    <global-errors></global-errors>

    <div>
        <div class="row  alert alert-info" role="alert">
            <div class="col-md-10" >
                <div >
                    <i class="glyphicon glyphicon-exclamation-sign" style="margin-right: 5px"></i><strong>Atención:</strong> Sólo pueden cerrarse áreas que tengan asignados la totalidad de insumos requeridos.
                </div>
            </div>
            <div class="col-md-2" style="text-align: right">
                <button id="modalAreas" class="btn btn-primary" type="button" >
                    <span><i class="glyphicon glyphicon-plus-sign"></i> Agregar Áreas</span>
                </button>
            </div>
        </div>
        <form action="{{ route('cierres.store') }}" method="POST" accept-charset="UTF-8" >
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <table class="table table-striped table-bordered" id="areas_seleccionadas">
                <thead>
                    <tr>
                        <th style="text-align: center">Clave</th>
                        <th style="text-align: center">Área</th>
                        <th style="text-align: center">Artículos Requeridos</th>
                        <th style="text-align: center">Artículos Asignados</th>
                        <th style="text-align: center">Artículos Validados</th>
                        <th style="width: 150px; text-align: center">Validar Todas Las Asignaciones Pendientes</th>
                        <th style="width: 30px"></th>
                    </tr>
                </thead>
            <tbody>
                @foreach($areas as $area)
                <tr>
                    <td>
                        {{$area->clave}}
                    </td>
                    <td>
                        {{$area->nombre}}
                        <input type="hidden" name="id_area[]" value="{{$area->id}}" />
                    </td>
                    <td style="text-align: right">
                        {{$area->cantidad_requerida()}}
                    </td>
                    <td style="text-align: right">
                        {{$area->cantidad_asignada()}}
                    </td>
                    <td style="text-align: right">
                        
                        @if(!($area->cantidad_validada() == $area->cantidad_asignada()))
                            
                            <input type="hidden" name="validacion_completa[]" value="0" />
                            <a href="{{ route('cierre.valida.asignaciones.area', [$area->id]) }}" class="validarArea btn btn-small btn-info">{{ $area->cantidad_validada() }}</a>
                        @else
                            <a href="{{ route('cierre.valida.asignaciones.area', [$area->id]) }}" class="validarArea btn btn-small btn-info">{{ $area->cantidad_validada() }}</a>
                            <input type="hidden" name="validacion_completa[]" value="1" />
                        @endif
                    </td>
                    <td style="text-align: center">
                        @if(!($area->cantidad_validada() == $area->cantidad_asignada()))
                        
                        
                        <a href="{{ route('cierre.validar.todas.asignaciones.area', [$area->id]) }}" class="validarTodaAsignacionArea btn btn-small btn-danger">Validar Todo</a>
                        @endif
                    </td>
                    <td style="text-align: center">
                        <button type="button" class="btn btn-small btn-default elimina_fila">
                            <span class="glyphicon glyphicon-minus-sign" style=""></span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </form>
        <hr>

        <div class="form-group" style="text-align: right">
            <button class="btn btn-primary" type="submit" v-bind:disabled="asignando" @click="confirmaAsignacion">
                <span><i class="fa fa-check-circle"></i> Generar Cierre</span>
            </button>
        </div>

<!--      <pre>
  @{{ $data.asignacionForm.errors | json 4 }}
</pre>-->

    </div>
</div>

    <div id="modal_busqueda_area"></div>
    <div id="modal_validacion_asignaciones"></div>

@stop
@section('scripts')
<script>
    $("button.elimina_fila").off().on("click", function(e){
        $(this).parents("tr").remove();
        e.preventDefault();
    });
    $("button#modalAreas").off().on("click", function (e) {
        ruta = "{{ route("cierre.busqueda.areas") }}";
        $.get(ruta, function (data) {
            $("#modal_busqueda_area").html(data);
            $("#modalBusquedaAreas").modal("show");
            preparaFormularioBusquedaArea();
            $("#btn_carga_areas").off().on("click", function(e){
                e.preventDefault();
                $("form#formulario_carga_areas").submit();
            });
            //preparaFormularioCargaArea();
        });
        e.preventDefault();
    });
    $("a.validarArea").off().on("click", function(e){
        var formURL = $(this).attr("href");
        $.ajax({
            url : formURL,
            type: "get",
            success:function(data) 
            {                       
                $("#modal_validacion_asignaciones").html(data);
                $("#modalValidacionAsignaciones").modal("show");
                preparaFormularioValidacionAsignaciones();
            },
            error: function(xhr, textStatus, thrownError) 
            {
                console.log(xhr.responseText);
                var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                    salida += '<li>'+elem+'</li>';
                });
                salida += '</ul></div>';
                $("div.errores_cobro_credito").html(salida);
            }
        });
        e.preventDefault();
    });
    $("a.validarTodaAsignacionArea").off().on("click", function(e){
    var formURL = $(this).attr("href");
    swal({
        title: "¿Desea continuar con la validación?", 
        text: "¿Esta seguro de validar todas las asignaciones del área?", 
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        confirmButtonColor: "#ec6c62"
      }, function(){
          $.ajax({
            url : formURL,
            type: "POST",
            success:function(data) 
            {                       
                location.reload();
            },
            error: function(xhr, textStatus, thrownError) 
            {
                console.log(xhr.responseText);
                var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                    salida += '<li>'+elem+'</li>';
                });
                salida += '</ul></div>';
                $("div.errores_cobro_credito").html(salida);
            }
        });
      });
    
        
        
        
        e.preventDefault();
    });
    function preparaFormularioValidacionAsignaciones(){
    //formulario_valida_asignaciones
        $("#formulario_valida_asignaciones").submit(function(e){
            var postData = $(this).serialize();
            var formURL = $(this).attr("action");
            $.ajax({
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data) 
                {                       
                   location.reload();
                },
                error: function(xhr, textStatus, thrownError) 
                {
                    console.log(xhr.responseText);
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';

                    $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                        salida += '<li>'+elem+'</li>';
                    });
                    salida += '</ul></div>';
                    $("div.errores_cobro_credito").html(salida);
                }
            });
           e.preventDefault();
        });
    }
    function preparaFormularioBusquedaArea(){
        $("#formulario_busqueda_area").submit(function(e){
            var postData = $(this).serialize();
            var formURL = $(this).attr("action");
            $.ajax({
                url : formURL,
                type: "POST",
                dataType:"json",
                data : postData,
                success:function(data) 
                {                       
                    if(data.length>0){
                        $("div#areas_encontradas table tbody").find("tr.no_template").remove();
                        $("div#areas_encontradas").css("display", "block");
                        $.each( data, function( key, val ) {
                            var newTR = $("div#areas_encontradas table tbody tr.template").clone().removeClass('template').addClass("no_template").css("display", "");
                            if(val.cerrable == 1){
                                chk = $('<input />', { type: 'checkbox', id: 'cb'+val.id, value: val.id, name:"id_area[]", class:"chk_id_area" });
                                newTR.find(".id_area").append(chk);
                            }
                            newTR.find(".clave").html(val.clave);
                            newTR.find(".area").html(val.ruta);
                            newTR.find(".articulos_requeridos").html(val.articulos_requeridos);
                            newTR.find(".articulos_asignados").html(val.articulos_asignados);
                            newTR.find(".articulos_validados").html(val.articulos_validados);
                            newTR.appendTo("div#areas_encontradas table tbody");

                        });
                    }
                },
                error: function(xhr, textStatus, thrownError) 
                {
                    console.log(xhr.responseText);
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';

                    $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                        salida += '<li>'+elem+'</li>';
                    });
                    salida += '</ul></div>';
                    $("div.errores_cobro_credito").html(salida);
                }
            });
           e.preventDefault();
        });
    }
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@stop