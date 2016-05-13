@extends ('layout')

@section ('content')
<form method="post" id="frm_filtros_comparativa" action="{{ route('reportes.tabla_resultado_comparativa_equipamiento') }}">
    {{ csrf_field() }}
    <div class="row" id="btn_personalizacion" 
         @if($mostrar_personalizar == 1) 
         style=" margin: 5px 0px;display: none"
         
          @endif
         >
        <div class="col-md-6" style="padding: 5px">
        <button type="button" class="btn btn-success toggle_personalizacion"><span class="glyphicon glyphicon-filter" style="margin-right: 5px"></span>Personalizar Consulta</button>
        </div>
    </div>
    <div class="row" id="frm_personalizacion" 
        @if($mostrar_personalizar != 1) 
         style="display: none;"
        @endif
         >
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Personalizar Consulta</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12" style="color: #009900">
                    <span class="glyphicon glyphicon-filter" style="margin-right: 5px"></span>Filtros
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        Grados de Variación
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="grados_variacion[]" multiple="true" size="6" id="grados_variacion">
                                            @foreach($filtros["grados_variacion"] as $grado_variacion)
                                            <option value="{{$grado_variacion->id}}"
                                                    @if(in_array($grado_variacion->id, $filtros_consulta["grados_variacion"]))
                                                    selected = "selected"
                                                    @endif
                                                    >{{$grado_variacion->grado_variacion}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-condensed table-striped table-bordered">

                            <thead>
                                <tr>

                                    <th>
                                        Casos
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td>
                                        <select name="casos[]" multiple="true" size="6" id="casos">
                                            @foreach($filtros["casos"] as $caso)
                                            <option value="{{$caso->id}}"
                                                    @if(in_array($caso->id, $filtros_consulta["casos"]))
                                                    selected = "selected"
                                                    @endif
                                                    >{{$caso->caso}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        Errores
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="errores[]" multiple="true" size="4" id="errores" style="max-width: 250px">
                                            @foreach($filtros["errores"] as $error)
                                            <option value="{{$error->id}}"
                                                    @if(in_array($error->id, $filtros_consulta["errores"]))
                                                    selected = "selected"
                                                    @endif
                                                    >{{$error->error}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-condensed table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        Familia
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="familias[]" multiple="true" size="6" id="familias">
                                            @foreach($filtros["familias"] as $familia)
                                            <option value="{{$familia->id}}"
                                                    @if(in_array($familia->id, $filtros_consulta["familias"]))
                                                    selected = "selected"
                                                    @endif
                                                    >{{$familia->familia}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <table class="table table-condensed table-striped table-bordered">

                            <thead>
                                <tr>

                                    <th>
                                        Clasificador
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td>
                                        <select name="clasificadores[]" multiple="true" size="6" id="clasificadores">
                                            @foreach($filtros["clasificadores"] as $clasificador)
                                            <option value="{{$clasificador->id}}"
                                                    @if(in_array($clasificador->id, $filtros_consulta["clasificadores"]))
                                                    selected = "selected"
                                                    @endif
                                                    >{{$clasificador->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-3">
                        <table class="table table-condensed table-striped table-bordered">

                            <thead>
                                <tr>

                                    <th>
                                        Descripción Material
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td>
                                        <input type="text" name="descripcion" value="{{ $filtros_consulta["descripcion"] }}" />
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th>
                                        Área Tipo
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="areas_tipo[]" multiple="true" size="6" id="areas_tipo">
                                            @foreach($filtros["areas_tipo"] as $area_tipo)
                                            <option value="{{$area_tipo->id}}"
                                                    @if(in_array($area_tipo->id, $filtros_consulta["areas_tipo"]))
                                                    selected = "selected"
                                                    @endif
                                                    >{{$area_tipo->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-condensed table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th>
                                        Áreas
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div id="inputs"></div>
                                        <div id="jstree_demo_div">
<!--                                            <ul>
                                            <li>Root node 1
                                              <ul>
                                                <li id="child_node_1">Child node 1</li>
                                                <li>Child node 2</li>
                                              </ul>
                                            </li>
                                            <li>Root node 2</li>
                                          </ul>-->
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="color: #009900">
                    <span class="glyphicon glyphicon-pencil" style="margin-right: 5px"></span>Presentación de Datos
                    </div>
                </div>
                <table class="table table-condensed table-striped table-bordered">

                    <thead>
                        <tr>
                            <th>
                                Moneda Comparativa
                            </th>
                            <th>
                                Tipo de Cambio Dolar
                            </th>
                            <th>
                                Tipo de Cambio Euro
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="moneda_comparativa" id="moneda_comparativa">
                                    @foreach($monedas as $moneda)
                                    <option value="{{$moneda->id_moneda}}"
                                            @if($moneda->id_moneda == $moneda_comparativa->id_moneda)
                                            selected="selected"
                                            @endif
                                            >{{$moneda->nombre}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="tipo_cambio_dolar" id="tipo_cambio_dolar" style="text-align: right" class="form-control" readonly="readonly" value="{{ $tipo_cambio_dolar }}">
                            </td>
                            <td>
                                <input type="text" name="tipo_cambio_euro" id="tipo_cambio_euro" style="text-align: right" class="form-control" readonly="readonly" value="{{ $tipo_cambio_euro }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer" style="text-align: right">
                <button type="button" class="btn btn-default toggle_personalizacion">
                    <span class="glyphicon glyphicon-minus-sign" style="margin-right: 5px"></span>Ocultar
                </button>
                <button type="button" class="btn btn-primary consulta">
                    <span class="glyphicon glyphicon-zoom-in" style="margin-right: 5px"></span>	Consultar
                </button>
                <button class="btn btn-small btn-success descarga_excel" type="button" >
                    <span class="fa fa-download" style="margin-right: 5px"></span> Descarga en Excel
                </button>
<!--                -->
            </div>
        </div>

    </div>
</div>
</form>
<form id="descargaExcel" method="post" action="{{ route("reportes.comparativa_equipamiento_xls") }}" >{{ csrf_field() }}</form>
<hr>
@if($articulos_esperados == "")
@else
<div class="row">

    <div class="col-md-3 col-md-offset-9 " style="color: #444">
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center">
                        Moneda Comparativa
                    </th>
                    <th style="text-align: center">
                        Tipo de Cambio Dolar
                    </th>
                    <th style="text-align: center">
                        Tipo de Cambio Euro
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center">
                        {{$moneda_comparativa->nombre}}
                    </td>
                    <td style="text-align: center">
                        {{$tipo_cambio_dolar}}                            
                    </td>
                    <td style="text-align: center">
                        {{$tipo_cambio_euro}}
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

@endif
<div id="tabla_resultados">
@include("reportes.partials.tabla")
</div>
@stop
@section('scripts')
<script>
$("button.toggle_personalizacion").off().on("click", function(){
$("#frm_personalizacion").toggle();
$("#btn_personalizacion").toggle();
});
$("#frm_filtros_comparativa").off().on("submit", function(e){    
    
        var postData = $(this).serialize();
        var formURL = $(this).attr("action");

        $.ajax({
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data) 
            {                       
                $("div#tabla_resultados").html(data);
                 $.tablesorter.addWidget({
                        // give the widget a id
                        id: "indexFirstColumn",
                        // format is called when the on init and when a sorting has finished
                        format: function(table) {               
                            // loop all tr elements and set the value for the first column  
                            for(var i=0; i < table.tBodies[0].rows.length; i++) {
                                $("tbody tr:eq(" + i + ") td:first",table).html(i+1);
                            }                                   
                        }
                    });
                $("#table_sort").tablesorter({
                    theme : "blue",
                    widgets :["indexFirstColumn","zebra"],
                    headers: { 0: { sorter: false},1: { sorter: false},2: { sorter: false},3: { sorter: false},4: { sorter: false},5: { sorter: false}, 25: {sorter: false}, 26: {sorter: false} ,  27: {sorter: false}, 28: {sorter: false}, 29: {sorter: false}, 30: {sorter: false},  31: {sorter: false}, 32: {sorter: false}, 33: {sorter: false}
                    , 34: {sorter: false}, 35: {sorter: false}, 36: {sorter: false}, 37: {sorter: false}, 38: {sorter: false}, 39: {sorter: false}, 40: {sorter: false}, 41: {sorter: false}, 42: {sorter: false}, 43: {sorter: false}}
                }
                        );
            },
            error: function(xhr, textStatus, thrownError) 
            {
                //console.log(xhr.responseText);
                var ind1 = xhr.responseText.indexOf('<span class="exception_message">');

                if(ind1 === -1){
                    var salida = '<div class="alert alert-danger" role="alert"><strong>Errores: </strong> <br> <br><ul >';
                    $.each($.parseJSON(xhr.responseText), function (ind, elem) { 
                        salida += '<li>'+elem+'</li>';
                    });
                    salida += '</ul></div>';
                    $("div#errores").html(salida);
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
                    $("div#errores").html(salida);
                }
            }
        });
    
    e.preventDefault();
});
$("button.limpiar_filtros").off().on("click", function(){

});
$(document).ready(function(){
$(function(){
    $.tablesorter.addWidget({
        // give the widget a id
        id: "indexFirstColumn",
        // format is called when the on init and when a sorting has finished
        format: function(table) {               
            // loop all tr elements and set the value for the first column  
            for(var i=0; i < table.tBodies[0].rows.length; i++) {
                $("tbody tr:eq(" + i + ") td:first",table).html(i+1);
            }                                   
        }
    });
$("#table_sort").tablesorter({
    theme : "blue",
    widgets :["indexFirstColumn","zebra"],
    headers: { 0: { sorter: false},1: { sorter: false},2: { sorter: false},3: { sorter: false},4: { sorter: false},5: { sorter: false}, 25: {sorter: false}, 26: {sorter: false} ,  27: {sorter: false}, 28: {sorter: false}, 29: {sorter: false}, 30: {sorter: false},  31: {sorter: false}, 32: {sorter: false}, 33: {sorter: false}
    , 34: {sorter: false}, 35: {sorter: false}, 36: {sorter: false}, 37: {sorter: false}, 38: {sorter: false}, 39: {sorter: false}, 40: {sorter: false}, 41: {sorter: false}, 42: {sorter: false}, 43: {sorter: false}}
}
        );



});
});

$(function () { 
    $('#jstree_demo_div').jstree({
        "core":{
            "data":{
                "url" : '{{ route("areas.areasJs") }}',
                "dataType" : "json"
            }
        },
        "plugins":["checkbox"]
    });
    $('#jstree_demo_div').on("changed.jstree", function (e, data) {
        //console.log("The selected nodes are:");
        $("#inputs").find("input").remove();
        $.each(data.selected, function(a,v){
           // console.log(v);
            
            $("#inputs").append("<input type='hidden' name='areas[]' id='areas"+v+"' value='"+v+"' />");
        });
      });
});
$("button.descarga_excel").off().on("click", function(e){
    $("#descargaExcel").find("input").remove();
    $("#descargaExcel").find("select").remove();
    selects = $("form#frm_filtros_comparativa").find("select");
    $.each(selects, function(){
       select = $(this);
       name = select.attr("name");
       if(select.val() !== null){
        valores = select.val();
        if($.isArray(valores)){
            $.each(valores, function(i,v){

               $("#descargaExcel").append("<input type='hidden' name='"+name+"' value='"+v+"' />");
            });
        }else{
            $("#descargaExcel").append("<input type='hidden' name='"+name+"' value='"+valores+"' />");
        }
       }
    });
    inputs = $("form#frm_filtros_comparativa").find("input");
    $.each(inputs, function(){
       input = $(this);
       name = input.attr("name");
       valor = input.val();
       if(valor !== null){
            
            $("#descargaExcel").append("<input type='hidden' name='"+name+"' value='"+valor+"' />");
       }
    });
    var postData = $("#frm_filtros_comparativa").serialize();
    $("form#descargaExcel").submit();
});
$("button.consulta").off().on("click", function(e){
    $("form#frm_filtros_comparativa").submit();
});

</script>
@stop