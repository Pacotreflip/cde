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
                <button id="modalAreas" class="btn btn-primary" type="submit" v-bind:disabled="asignando" >
                    <span><i class="glyphicon glyphicon-plus-sign"></i> Agregar Áreas</span>
                </button>
            </div>
        </div>
        <form action="{{ route('cierres.store') }}" method="POST" accept-charset="UTF-8" @submit="asignar">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <table class="table table-striped table-hover" id="areas_seleccionadas">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Área</th>
                        <th>Artículos Requeridos</th>
                        <th>Artículos Asignados</th>
                        <th>Artículos Validados</th>
                        <th style="width: 150px; text-align: center">Validar Todas Las Asignaciones Pendientes</th>
                        <th style="width: 30px"></th>
                    </tr>
                </thead>
            <tbody>
                
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
<form id="formulario_busqueda_area" method="post" action="{{ route("cierre.get.areas") }}">
    {{ csrf_field() }}
    <div id="modal_busqueda_area"></div>
</form>
@stop
@section('scripts')
<script>
    $("button#modalAreas").off().on("click", function (e) {
        ruta = "{{ route("cierre.busqueda.areas") }}";
        $.get(ruta, function (data) {
            $("#modal_busqueda_area").html(data);
            $("#modalBusquedaAreas").modal("show");
//            $("input#importe_recibido, input#importe_cobrar").off().on("keyup", function(e){
//                calcula_cambio();
//                e.preventDefault();  
//            });
        });
        e.preventDefault();
    });
    
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
                console.log(data);
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
    
</script>
@stop