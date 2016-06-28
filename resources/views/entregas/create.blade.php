@extends('layout')

@section('content')
<h1>Nueva Entrega de Áreas
    <a href="{{ route('entregas.index') }}" class="btn btn-success pull-right"><i class="glyphicon glyphicon-chevron-left"></i> Regresar</a>
</h1>
<hr>
<div class="errores_entrega"></div>
<div id="app">
    <global-errors></global-errors>
    <form action="{{ route('entregas.store') }}" method="POST" accept-charset="UTF-8" id="entrega_store">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        @foreach($areas as $area)
        <input type="hidden" name="id_area[]" value="{{$area->id}}" />
        @endforeach
    <div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" >Fecha de Entrega:</label>
                    <input type="date" style="width: 100%" name="fecha_entrega" class="form-control" value="{{ $fecha_entrega }}" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-md-1 control-label" v-model="observaciones" >Concepto:</label>
                    <textarea style="width: 100%" name="concepto" class="form-control" >{{$concepto}}</textarea>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-md-1 control-label" v-model="observaciones" >Entrega:</label>
                    <input type="text" style="width: 100%" name="entrega" class="form-control" value="{{$entrega}}" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="col-md-1 control-label" v-model="observaciones" >Recibe:</label>
                    <input type="text" style="width: 100%" name="recibe" class="form-control" value="{{$recibe}}" />
                </div>
            </div>
        </div>
        <div class="row  alert alert-info" role="alert">
            <div class="col-md-10" >
                <div >
                    <i class="glyphicon glyphicon-exclamation-sign" style="margin-right: 5px"></i><strong>Atención:</strong> Sólo pueden entregarse áreas que se encuentren cerradas.
                </div>
            </div>
            <div class="col-md-2" style="text-align: right">
                <button id="modalAreas" class="btn btn-primary" type="button" >
                    <span><i class="glyphicon glyphicon-plus-sign"></i> Agregar Áreas</span>
                </button>
            </div>
        </div>
        
            <table class="table table-striped table-bordered" id="areas_seleccionadas">
                <caption>Artículos entregados</caption>
                <thead>
                    <tr>
                        <th style="text-align: center; width: 20px">#</th>
                        <th style="text-align: center">Familia</th>
                        <th style="text-align: center">Descripción</th>
                        <th style="text-align: center">Unidad</th>
                        <th style="text-align: center; width: 150px">Cantidad Entregada</th>
                        <th style="text-align: center">Ubicación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articulos as $articulo)
                    <tr>
                        <td>
                            {{$i++}}
                        </td>
                        <td>
                            {{$articulo->familia()["descripcion"]}}
                            <input type="hidden" name="id_articulo[]" value="{{$articulo->id_material}}" />
                        </td>
                        <td>
                            {{$articulo->descripcion}}
                        </td>
                        <td style="text-align: right">
                            {{$articulo->unidad}}
                        </td>
                        <td style="text-align: right">
                            {{ $articulo->cantidad_cierre($id_areas)}}
                        </td>
                        <td style="text-align: right">
                            {{$articulo->ubicacion_asignada($id_areas)}}
                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
        <div class="form-group" style="overflow: hidden">
                        <div class="row">
                            <label class="col-md-2 control-label" v-model="observaciones" >Observaciones:</label>
                            
                           
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea style="width: 100%" name="observaciones" class="form-control">{{$observaciones}}</textarea>
                            </div>
                           
                            
                        </div>
                    </div>
        
        <hr>

        <div class="form-group" style="text-align: right">
            <button class="btn btn-primary" type="submit" >
                <span><i class="fa fa-check-circle"></i> Generar Entrega</span>
            </button>
        </div>


    </div>
            </form>
    <form id="formulario_recarga_areas" method="post" action="{{ route("entrega.create.areas") }}">
                {{ csrf_field() }}
        <div class="row  ">
            <div class="col-md-6" >
        <table class="table table-striped table-bordered" id="areas_seleccionadas">
            <caption>Áreas Seleccionadas</caption>
                <thead>
                    <tr>
                        <th style="text-align: center; width: 20px">#</th>
                        <th style="text-align: center">Clave</th>
                        <th style="text-align: center">Área</th>
                        <th style="width: 30px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areas as $area)
                    <tr>
                        <td>
                            {{$i2++}}
                        </td>
                        <td>
                            {{$area->clave}}
                        </td>
                        <td>
                            {{$area->ruta()}}
                            <input type="hidden" name="id_area[]" value="{{$area->id}}" />
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
            </div></div>
        </form>
</div>

<div id="modal_busqueda_area"></div>
<div id="modal_validacion_asignaciones"></div>

@stop
@section('scripts')
<script>
    $("button.elimina_fila").off().on("click", function (e) {
        $(this).parents("tr").remove();
        $("#formulario_recarga_areas").submit();
        e.preventDefault();
    });
    $("button#modalAreas").off().on("click", function (e) {
        ruta = "{{ route("entrega.busqueda.areas") }}";
        var postData = $("#entrega_store").serialize();
        $.ajax({
            url: ruta,
            type: "post",
            data: postData,
            success: function (data)
            {
               $("#modal_busqueda_area").html(data);
                $("#modalBusquedaAreas").modal("show");
                preparaFormularioBusquedaArea();
                $("#btn_carga_areas").off().on("click", function (e) {
                    e.preventDefault();
                    $("form#formulario_carga_areas").submit();
                });
            }
        });
        e.preventDefault();
    });
    $("a.validarArea").off().on("click", function (e) {
        var formURL = $(this).attr("href");
        $.ajax({
            url: formURL,
            type: "get",
            success: function (data)
            {
                $("#modal_validacion_asignaciones").html(data);
                $("#modalValidacionAsignaciones").modal("show");
                preparaFormularioValidacionAsignaciones();
            },
            error: function (xhr, textStatus, thrownError)
            {
                console.log(xhr.responseText);
                var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                $.each($.parseJSON(xhr.responseText), function (ind, elem) {
                    salida += '<li>' + elem + '</li>';
                });
                salida += '</ul></div>';
                $("div.errores_cobro_credito").html(salida);
            }
        });
        e.preventDefault();
    });
    $("a.validarTodaAsignacionArea").off().on("click", function (e) {
        var formURL = $(this).attr("href");
        swal({
            title: "¿Desea continuar con la validación?",
            text: "¿Esta seguro de validar todas las asignaciones del área?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            confirmButtonColor: "#ec6c62"
        }, function () {
            $.ajax({
                url: formURL,
                type: "POST",
                success: function (data)
                {
                    location.reload();
                },
                error: function (xhr, textStatus, thrownError)
                {
                    console.log(xhr.responseText);
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                    $.each($.parseJSON(xhr.responseText), function (ind, elem) {
                        salida += '<li>' + elem + '</li>';
                    });
                    salida += '</ul></div>';
                    $("div.errores_cobro_credito").html(salida);
                }
            });
        });




        e.preventDefault();
    });
    function preparaFormularioValidacionAsignaciones() {
        //formulario_valida_asignaciones
        $("#formulario_valida_asignaciones").submit(function (e) {
            var postData = $(this).serialize();
            var formURL = $(this).attr("action");
            $.ajax({
                url: formURL,
                type: "POST",
                data: postData,
                success: function (data)
                {
                    location.reload();
                },
                error: function (xhr, textStatus, thrownError)
                {
                    console.log(xhr.responseText);
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';

                    $.each($.parseJSON(xhr.responseText), function (ind, elem) {
                        salida += '<li>' + elem + '</li>';
                    });
                    salida += '</ul></div>';
                    $("div.errores_cobro_credito").html(salida);
                }
            });
            e.preventDefault();
        });
    }
    function preparaFormularioBusquedaArea() {
        $("#formulario_busqueda_area").submit(function (e) {
            var postData = $(this).serialize();
            var formURL = $(this).attr("action");
            $.ajax({
                url: formURL,
                type: "POST",
                dataType: "json",
                data: postData,
                success: function (data)
                {
                    if (data.length > 0) {
                        $("div#areas_encontradas table tbody").find("tr.no_template").remove();
                        $("div#areas_encontradas").css("display", "block");
                        $("div#error_areas_encontradas").css("display", "none");
                        $.each(data, function (key, val) {
                            var newTR = $("div#areas_encontradas table tbody tr.template").clone().removeClass('template').addClass("no_template").css("display", "");
                            console.log(val.cerrada +"-"+ val.entregada);
                            if(val.cerrada == 0 && val.entregada == 0){
                                
                                chk = "<span class = 'fa fa-unlock' data-toggle='tooltip' title='Él área no ha pasado por el proceso de cierre'></span>";
                                newTR.find(".id_area").html(chk);
                                
                            }else if(val.cerrada == 0 && val.entregada == 1){
                                chk = "<span class = 'fa fa-unlock' data-toggle='tooltip' title='Él área no ha pasado por el proceso de cierre'></span>";
                                newTR.find(".id_area").html(chk);
                            }
                            else if(val.cerrada == 1 && val.entregada == 0){
                                if(val.checked == 0){
                                    chk = $('<input />', {type: 'checkbox', id: 'cb' + val.id, value: val.id, name: "id_area[]", class: "chk_id_area"});
                                    newTR.find(".id_area").append(chk);
                                }else if(val.checked == 1){
                                    chk = "<span class = 'glyphicon glyphicon-check' ></span>";
                                    newTR.find(".id_area").html(chk);
                                }
                            }
                            else if(val.cerrada == 1 && val.entregada == 1){
                                chk = "<span class = 'glyphicon glyphicon-ok-circle' data-toggle='tooltip' title='Entrega "+val.entrega+" ["+val.fecha_entrega+"]'></span>";
                                newTR.find(".id_area").html(chk);
                            }
                            
                            newTR.find(".clave").html(val.clave);
                            newTR.find(".area").html(val.ruta);
                            newTR.find(".articulos_requeridos").html(val.articulos_requeridos);
                            newTR.find(".articulos_asignados").html(val.articulos_asignados);
                            newTR.find(".articulos_validados").html(val.articulos_validados);
                            newTR.appendTo("div#areas_encontradas table tbody");

                        });
                        $('[data-toggle="tooltip"]').tooltip(); 
                    }else{
                        $("div#error_areas_encontradas").css("display", "block");
                        $("div#areas_encontradas").css("display", "none");
                        $("div#areas_encontradas table tbody").find("tr.no_template").remove();
                    }
                },
                error: function (xhr, textStatus, thrownError)
                {
                    console.log(xhr.responseText);
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';

                    $.each($.parseJSON(xhr.responseText), function (ind, elem) {
                        salida += '<li>' + elem + '</li>';
                    });
                    salida += '</ul></div>';
                    $("div.errores_cobro_credito").html(salida);
                }
            });
            e.preventDefault();
        });
    }
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    $("#entrega_store").submit(function (e) {
            var postData = $(this).serialize();
            var formURL = $(this).attr("action");
            $.ajax({
                url: formURL,
                type: "POST",
                data: postData,
                success: function (data)
                {
                    window.location = data.path;
                },
                error: function (xhr, textStatus, thrownError)
                {
                    var ind1 = xhr.responseText.indexOf('<span class="exception_message">');

                if(ind1 === -1){
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                    $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                        salida += '<li>'+elem+'</li>';
                    });
                    salida += '</ul></div>';
                    $(".errores_entrega").html(salida);
                }else{
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                    var ind1 = xhr.responseText.indexOf('<span class="exception_message">');
                    var cad1 = xhr.responseText.substring(ind1);
                    var ind2 = cad1.indexOf('</span>');
                    var cad2 = cad1.substring(32,ind2);
                    if(cad2 !== ""){
                        salida += '<li><p><strong>¡ERROR GRAVE!: </strong></p><p>'+cad2+'</p></li>';
                    }else{
                        salida += '<li>Un error grave ocurrió. Por favor intente otra vez.</li>';
                    }
                    salida += '</ul></div>';
                    $(".errores_entrega").html(salida);
                }
                }
            });
            e.preventDefault();
        });
</script>
@stop