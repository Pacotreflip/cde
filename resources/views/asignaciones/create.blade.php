@extends('layout')
@section('content')
<h1>Nueva Asignación de Artículos</h1>
<hr>
<div class="errores"></div>
<div class="section">
    <div class="col-md-3">

        <h4><strong>SELECCIONAR ALMACÉN</strong></h4>
<!--        <div class="form-group">
            <label for="inputdefault">FILTRAR <small>(ARTÍCULO)</small></label>
            <input class="form-control" id="filtro" type="text" style="width: 75%">
        </div>        -->
        <ul class="areas">
            @foreach($areas as $area)
            <li class="area">
                <a href="{{route('asignar.areacreate', ['id' => $area->id])}}">{{$area->ruta()}} </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="col-md-9">
        @if(isset($currarea))       
        <table class="table table-hover" id="tabla">
            <h4><strong>{{ $currarea->nombre }}</strong><small> ARTÍCULOS ALMACENADOS</small></h4>
            <thead>
                <tr>
                    <th>#Parte</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Almacenados</th>
                    <th>Asignar a Destino(s)</th>
                </tr>
            </thead>

            <tbody>
                @foreach($articulos as $articulo)
                <tr id="{{$articulo->material->id_material}}">
                    <td>{{ $articulo->material->numero_parte}}</td>                
                    <td><strong>{{ $articulo->material->descripcion }}</strong></td>
                    <td>{{ $articulo->material->unidad }}</td>
                    <td>{{ $articulo->cantidad_existencia }}</td>
                    <td><a  id="verDestinos" id_area="{{$articulo->id_area}}" id_material="{{$articulo->material->id_material}}"><i class=" btn btn-primary fa fa-sitemap"></i></a></td>                
                </tr>
                @endforeach
            </tbody>    
        </table>
        <br>
        <br>
        <div class="form-group">
            <button class="btn btn-primary" type="submit" id="enviar">
                <span><i class="fa fa-check-circle"></i> Asignar Artículos</span>
            </button>
        </div>
        @endif 
    </div>
</div>
<hr>

@stop
@section('scripts')
@if(isset($currarea))
<script> 
    var asignacionForm = {
        origen: '',
        nombre_area:'',
        materiales: [],
        errors: []
    };
    var area = {
        materiales: []
    };
    $(document).ready(function() {
        asignacionForm.origen = '<?php echo $currarea->id ?>';
        asignacionForm.nombre_area = '<?php echo $currarea->nombre ?>';
        asignacionForm.errors = [];
        $.ajaxSetup({
            headers:{
                'X-CSRF-Token': App.csrfToken
            }
        });
    });
    function setDestino(destino, id_area, id_material) {
            // Obtener un  material
        $.get('/asignar/material/' + id_area + '/' + id_material).success(function(material){
            // Obtener el destino
            $.get('/asignar/destino/' + id_material + '/' + $(destino).attr("id_destino")).success(function(destinos) {
                //verificar existencia del material
                var materialExistente = $.grep(area.materiales, function(e){ return e.id === id_material; });
                if (materialExistente.length !== 0) {
                    //Si el material existe
                    //Verificar existencia del destino
                    var destinoExistente = $.grep(materialExistente[0].destinos, function(e){ return e.id == $(destino).attr("id_destino"); });
                    if(destinoExistente.length !== 0){
                        //Si el destino existe
                        if($.trim(destino.value).length === 0) {
                            var destinosNew = $.grep(materialExistente[0].destinos, function(e){ return e.id !== $(destino).attr("id_destino"); });
                            materialExistente[0].destinos = destinosNew;
                        }
                        destinoExistente[0].cantidad = destino.value;
                    } else {
                        //Si el destino no existe
                        if($.trim(destino.value).length !== 0){
                            destinos[0].cantidad = destino.value;
                            materialExistente[0].destinos.push(destinos[0]);
                        }
                    }
                } else {
                    //Si el material no existe
                    destinos[0].cantidad = destino.value;
                    material.destinos.push(destinos[0]);
                    area.materiales.push(material); 
                }
            });
        });
    }
//    $('#filtro').off().on('keyup', function(e) {
//        $('.areas').empty();
//        $.ajax({
//            type: 'GET',
//            url: '/asignar/filtrar/' + $('#filtro').val(),
//            dataType: 'JSON',
//            success: function(data) {
//                console.log(data);
////                if(data.length === 0){
////                    $('.areas').html('@foreach($areas as $area)<li class="area"><a href="{{route("asignar.areacreate", ["id" => $area->id])}}">{{$area->ruta()}} </a></li>@endforeach'); 
////                } else {
//                    data.forEach(function(area){
//                        console.log(area.id_area);
//                        $('.areas').append(
//                            '<li class="area"><a href="'+ App.host +'/asignar/inventarios/'+ area.id_area +'">'+ area.ruta +'</a></li>'
//                        );
//                    });
////                }
//            },
//            error: function(xhr, responseText, thrownError) {   
//                $('.areas').html('@foreach($areas as $area)<li class="area"><a href="{{route("asignar.areacreate", ["id" => $area->id])}}">{{$area->ruta()}} </a></li>@endforeach'); 
//
//            }
//        });
//    });
    function setDestinos(id_area, id_material) {
        $.ajax({
            type: 'GET',
            url: '/asignar/destinos/' + id_material,
            dataType: 'JSON',            
            success: function(data) {
                if(data.length === 0) {
                    swal('No existen destinos que esperen recibir éste artículo','','info');
                } else {
                    data.forEach(function (destino) {
                        $('#'+ id_material).after(
                            '<tr tipo="trDestino" id="destino'+ id_material + '" class="success">\n\
                                <td  colspan = "3" align="right"><strong>' + destino.path + '</strong> (Pendientes '+ destino.cantidad +')</td>\n\
                                <td colspan = "2"  align="right"><input id_destino="'+destino.id+'" name="'+destino.path+'" type="text" class="form-control input-xs" placeholder="cantidad a asignar" onchange="setDestino(this,'+id_area+', '+id_material+')" ></td>\n\
                            </tr>'
                        );
                    });
                }                
            },
            error: function(xhr, responseText, thrownError) {                   
            }
        });
    } 
    $(function () {
        function first() {
            if(document.getElementById('destino'+$(this).attr("id_material"))) {
                $('[id=destino'+$(this).attr("id_material")+']').show(); 
            } else {
                setDestinos($(this).attr("id_area"), $(this).attr("id_material"));
            }
        $(this).one("click", second);
        }
        
        function second() {
            $('[id=destino'+$(this).attr("id_material")+']').hide();
            $(this).one("click", first);
        }
        $("[id=verDestinos]").one("click", first);
    });
    $("#enviar").off().on("click", function (e) {
        e.preventDefault();        
        var url = "{{ route('asignaciones.store') }}";
        swal({
            title: "¿Desea continuar con la asignación?",
            text: "¿Esta seguro de que la información es correcta?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            confirmButtonColor: "#ec6c62"
        }, function(isConfirm){
            asignacionForm.materiales = [];
            area.materiales.forEach(function (m){
                if(m.destinos.length !== 0){
                    asignacionForm.materiales.push(m);
                }
            });
        if (isConfirm) {
            $(".errores").empty();
            $.ajax({
                url: url,
                type: "POST",
                data: asignacionForm,
                success: function (response)
                {
                    window.location = response.path;
                },
                error: function(xhr, responseText, thrownError) {
                    var ind1 = xhr.responseText.indexOf('<span class="exception_message">');

                    if(ind1 === -1){
                        var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                        $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                            salida += '<li>'+elem+'</li>';
                        });
                        salida += '</ul></div>';
                        $(".errores").html(salida);
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
                        $(".errores").html(salida);
                    }
                }
            });                    
        }
    });
});
</script>
@endif
@stop