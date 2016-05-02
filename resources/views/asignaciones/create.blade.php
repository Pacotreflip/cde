@extends('layout')
@section('content')
<h1>Nueva Asignación de Artículos</h1>
<hr>
<div class="text-right col-md-3 col-md-offset-9">
    @if(!isset($currarea))
    <div class="input-group">
        <input class="form-control input-sm" type="text" id="buscar" placeholder="Buscar...">
          <span class="input-group-btn">
        <button class="btn btn-sm btn-primary disabled" type="submit">Buscar</button>
      </span>
    </div>
    @endif
  <br>
</div>
<div class="section">
<div class="col-md-3">
    @if(isset($currarea))
    <a href="{{route('asignar.create')}}"><h4><strong>TODOS LOS ARTÍCULOS</strong></h4></a>

    @else
    <h4><strong>SELECCIONAR ALMACÉN</strong></h4>
    @endif
    <ul>
        @foreach($areasraiz as $area)
        @if($area->cantidad_almacenada() > 0)
        <li class="area"><a href="{{route('asignar.areacreate', ['id' => $area->id])}}">{{$area->nombre}} </a><i class="fa fa-chevron-circle-right fa-lg"></i>
            <ul class="children">
                @foreach($area->areas_hijas as $hija)
                @if($hija->cantidad_almacenada > 0)
                <li><a href="./{{$hija->id}}">{{$hija->nombre}} </a></li>
                @endif
                @endforeach
            </ul>  
        </li>
        @endif
        @endforeach
    </ul>
</div>
<div class="col-md-9">
    @if(isset($currarea))
    <form action="{{ route('asignaciones.store') }}" method="POST" accept-charset="UTF-8">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        @endif
    <table class="table table-hover" id="tabla">
        <h4><strong>{{isset($currarea) ? $currarea->ruta : 'ARTÍCULOS ALMACENADOS' }}</strong></h4>
        <thead>
            <tr>
                <th>Area</th>
                <th>#Parte</th>
                <th>Descripción</th>
                <th>Unidad</th>
                <th>Almacenados</th>
                @if(isset($currarea))
                <th>Esperados</th>
                <th>Asignados</th>
                <th>Asignar a Destino(s)</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach($articulos as $articulo)
           <?php $areaArt = \Ghi\Equipamiento\Areas\Area::find($articulo->id_area) ?>
            <tr id="{{$articulo->material->id_material}}">
                <td>{{$areaArt->ruta}}</td>
                <td>{{ $articulo->material->numero_parte}}</td>                
                <td><strong>{{ $articulo->material->descripcion }}</strong></td>
                <td>{{ $articulo->material->unidad }}</td>
                <td>{{ $articulo->cantidad_existencia }}</td>
                @if(isset($currarea))
                <td>{{ $articulo->material->cantidad_esperada($articulo->id_area) }}</td>
                <td>{{ $articulo->material->cantidad_asignada($articulo->id_area) }}</td>
                <td><a  id="verDestinos" id_area="{{$articulo->id_area}}" id_material="{{$articulo->material->id_material}}"><i class=" btn btn-primary fa fa-sitemap"></i></a></td>                
                @endif
            </tr>
            @endforeach
        </tbody>    
    </table>
        @if(isset($currarea))
    <a style="float: right" class="btn btn-primary" href="{{route('asignar.create')}}"><i class="fa fa-reply fa-lg"></i> Todos los Artículos</a>
        @endif
    <br>
    <br>

    @if(isset($currarea))
    <div class="form-group">
        <button class="btn btn-primary" type="submit" id="enviar">
            <span><i class="fa fa-check-circle"></i> Asignar Artículos</span>
        </button>
    </div>
    @endif 
    </form>
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
    
    $(document).ready(
        function() {
            asignacionForm.origen = '<?php echo $currarea->id ?>';
            asignacionForm.nombre_area = '<?php echo $currarea->nombre ?>';
            asignacionForm.errors = [];
//            console.log(area.materiales[0].cantidad_existencia);
            
//            console.log(asignacionForm);
    });
    
    function setDestino(destino, id_area, id_material) {
        console.log(1);
        // Obtener un nuevo material
        $.get('/asignar/material/' + id_area + '/' + id_material).success(function(material){
            console.log(2);
            // Obtener el destino
            $.get('/asignar/destino/' + id_material + '/' + $(destino).attr("id_destino")).success(function(destinos) {
                console.log(3);
                //verificar existencia del material
                var materialExistente = $.grep(area.materiales, function(e){ return e.id === id_material; });
                if (materialExistente.length !== 0) {
                    //Si el material existe
                    //Verificar existencia del destino
                    var destinoExistente = $.grep(materialExistente[0].destinos, function(e){ return e.id == $(destino).attr("id_destino"); });
                    if(destinoExistente.length !== 0){
                        //Si el destino existe
                        destinoExistente[0].cantidad = destino.value;
                    } else {
                        //Si el destino no existe
                        destinos[0].cantidad = destino.value;
                        materialExistente[0].destinos.push(destinos[0]);
                    }
                } else {
                    console.log(3);
                    //Si el material no existe
                    destinos[0].cantidad = destino.value;
                    material.destinos.push(destinos[0]);
                    area.materiales.push(material); 
                }
                    
            });
        });
        console.log(4);
        console.log('segunda',area.materiales);
    }

    function setDestinos(id_area, id_material) {
//        $.get('/asignar/material/' + id_area + '/' + id_material).success(function(material){ 
            $.get('/asignar/destinos/' + id_material).success(function(destinos) {
                destinos.forEach(function (destino) {
//                    material.destinos.push(destino);
                    $('#'+ id_material).after(
                            '<tr tipo="trDestino" id="destino'+ id_material + '" class="success">\n\
                                <td  colspan = "6" align="right"><strong>' + destino.path + '</strong> (requiere '+ destino.cantidad +')</td>\n\
                                <td colspan = "2"  align="right"><input id_destino="'+destino.id+'" name="'+destino.path+'" type="text" class="form-control input-xs" placeholder="cantidad a asignar" onchange="setDestino(this,'+id_area+', '+id_material+')" ></td>\n\
                            </tr>'
                    );
                });
//                asignacionForm.materiales.push(material);
//                console.log(asignacionForm);
            });
//        });
    }
    
//    function removeDestinos(id) {
//        $('[id=destino'+id+']').remove();
//        var i = 0;
//        asignacionForm.materiales.forEach(function (material) {
//            if(material) {
//                delete asignacionForm.materiales[i];
//                console.log(asignacionForm.materiales);
//            }
//            i++;
//        });   
//    }

    $(function () {
                        var cont = 0;

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
//            removeDestinos($(this).attr("id_material"));
            $(this).one("click", first);
        }
        $("[id=verDestinos]").one("click", first);
    });
   
    $("#enviar").off().on("click", function (e) {
        console.log(5);
        e.preventDefault();        
        console.log(6);
        var url = $(this).closest('form').attr("action");
    swal({
        title: "¿Desea continuar con la asignación?",
        text: "¿Esta seguro de que la información es correcta?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        confirmButtonColor: "#ec6c62"
    }, function(isConfirm){
        area.materiales.forEach(function (m){
            asignacionForm.materiales.push(m);
            console.log(asignacionForm);
        });
        if (isConfirm) {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-Token': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                url: url,
                type: "POST",
                data: asignacionForm,
                success: function (response)
                {
                    window.location = response.path;
                },
                error: function (errors)
                {
                    console.log(errors);
                    App.setErrorsOnForm(this.asignacionForm, errors);
                    console.log(asignacionForm);
                }
            });                    
        }
    });
});

</script>
@endif
<script>
$(document).ready(
        function(){$('ul li > ul').slideUp();       
});
$('ul li.area').click(function(e) {$(this).children('ul.children').slideToggle(300);});

var $rows = $('#tabla tbody tr');
$('#buscar').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    if (val){ $('[tipo=trDestino]').hide();}
    else {$('[tipo=trDestino]').show();}  
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
</script>
@stop