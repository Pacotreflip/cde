@extends ('areas-tipo.layout_comparativa')

@section ('main-content')
<form method="post" action="{{ route('comparativa.consulta', $tipo->id) }}">
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
    <div class="col-md-6">
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
                                            <option value="{{$grado_variacion->idgrado_variacion}}"
                                                    @if(in_array($grado_variacion->idgrado_variacion, $filtros_consulta["grados_variacion"]))
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
                                        <select name="casos[]" multiple="true" size="4" id="casos" style="max-width: 200px">
                                            @foreach($filtros["casos"] as $caso)
                                            <option value="{{$caso->idcaso}}"
                                                    @if(in_array($caso->idcaso, $filtros_consulta["casos"]))
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
                                            <option value="{{$error->iderror}}"
                                                    @if(in_array($error->iderror, $filtros_consulta["errores"]))
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
                                <input type="text" name="tipo_cambio_dolar" id="tipo_cambio_dolar" style="text-align: right" class="form-control" value="{{ $tipo_cambio_dolar }}">
                            </td>
                            <td>
                                <input type="text" name="tipo_cambio_euro" id="tipo_cambio_euro" style="text-align: right" class="form-control" value="{{ $tipo_cambio_euro }}">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer" style="text-align: right">
                <button type="button" class="btn btn-default toggle_personalizacion">
                    <span class="glyphicon glyphicon-minus-sign" style="margin-right: 5px"></span>Ocultar
                </button>
                <button type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-zoom-in" style="margin-right: 5px"></span>	Consultar
                </button>
            </div>
        </div>

    </div>
</div>
</form>

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

@if($articulos_esperados == "")
@elseif(count($articulos_esperados) > 0)
<table class="tablesorter" id="table_sort" >
<!--    <caption><span class="glyphicon glyphicon-filter" style="margin-right: 5px"></span>Todos los Artículos</caption>-->
    <thead>
<!--               <tr>
<th colspan="20" scope="col">OS&amp;E</th>
</tr>-->
<tr>
            <td scope="col" colspan="4" style="text-align: center; border:1px #FFF solid; background-color: #fff">&nbsp;</td>
            <th colspan="5" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Este Proyecto</th>
            <th colspan="5" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Proyecto Comparativo</th>
            <th colspan="4" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Variaciones</th>
            <th colspan="2" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Control</th>
        </tr>
        
        <tr>
            <th style="text-align: center;  border-top:3px #C1C1C1 solid;  border-left:3px #C1C1C1 solid">#</th>
            <td style="text-align: center;border-top:3px #C1C1C1 solid">Clasificador</td>
            <td style="text-align: center; border-top:3px #C1C1C1 solid">Descripción</td>
            <td style="text-align: center; border-top:3px #C1C1C1 solid">Unidad</td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Cantidad </td>
        <!--    <td style="text-align: center">Precio Unitario </td>-->
            <td style="text-align: center; ">Precio Unitario  </td>
            <td style="text-align: center; ">Moneda</td>
            <td style="text-align: center; ">Precio Unitario ({{$moneda_comparativa->nombre}})</td>
            <td style="text-align: center;  border-right:3px #C1C1C1 solid">Importe ({{$moneda_comparativa->nombre}})</td>
            <td style="text-align: center;  ">Cantidad </td>
        <!--    <td style="text-align: center">Precio Unitario </td>-->
            <td style="text-align: center; ">Precio Unitario </td>
            <td style="text-align: center; ">Moneda </td>
            <td style="text-align: center; ">Precio Unitario ({{$moneda_comparativa->nombre}}) </td>
            <td style="text-align: center;  border-right:3px #C1C1C1 solid">
                Importe ({{$moneda_comparativa->nombre}})
            </td>
            <td style="text-align: center; ">Sobrecosto</td>
            <td style="text-align: center; ">Ahorro</td>
            <td style="text-align: center; ">%Variación</td>
            <td style="text-align: center;  border-right:3px #C1C1C1 solid">Grado de Variación</td>
            <td style="text-align: center; ">Caso</td>
            <td style="text-align: center;  border-right:3px #C1C1C1 solid">Errores</td>
        </tr>
        <tr style="background-color: #C1C1C1">
            <td style="text-align: right; border-left:3px #C1C1C1 solid; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right;  border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right;  border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right;  border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right;  border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right;  border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right;  border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td  style="text-align: right;border-bottom: 3px #C1C1C1 solid">Sumatorias:</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulos_esperados[count($articulos_esperados)-1]->costo_total_proyecto_f}}</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulos_esperados[count($articulos_esperados)-1]->costo_total_proyecto_comparativa_f}}</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulos_esperados[count($articulos_esperados)-1]->sobrecosto_total_f}}</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulos_esperados[count($articulos_esperados)-1]->ahorro_total_f}}</td>
            <td  colspan="2" style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
            <td  style="text-align: right; border-right:3px #C1C1C1 solid">&nbsp;</td>
        </tr>
    </thead>
    <tbody>
        @foreach($articulos_esperados as $articulo_esperado)
        <tr>
            <td style=" border-left:3px #C1C1C1 solid">{{ $i ++ }}</td>
            <td>{{ $articulo_esperado->clasificador }}</td>   
            <td><a href="{{ route("articulos.edit", $articulo_esperado->id_material) }}">{{ $articulo_esperado->descripcion }}</a></td>
            <td>{{ $articulo_esperado->unidad }}</td>
            <td style="text-align: right;border-left:3px #C1C1C1 solid">{{ $articulo_esperado->cantidad_requerida }}</td>
        <!--    <td>&nbsp;</td>-->
            <td style="text-align: right">{{ $articulo_esperado->importe_estimado_f }}</td>
            <td>{{ $articulo_esperado->moneda_requerida }}</td>
            <td style="text-align: right;">{{ $articulo_esperado->precio_requerido_moneda_comparativa_f }}</td>
            <td style="text-align: right;border-right:3px #C1C1C1 solid">{{ $articulo_esperado->importe_requerido_moneda_comparativa_f }}</td>
            <td style="text-align: right">{{ $articulo_esperado->cantidad_comparativa }}</td>
        <!--    <td>&nbsp;</td>-->
            <td style="text-align: right">{{ $articulo_esperado->importe_comparativa_f }}</td>
            <td>{{ $articulo_esperado->moneda_comparativa }}</td>
            <td style="text-align: right; ">{{ $articulo_esperado->precio_comparativa_moneda_comparativa_f }}</td>
            <td style="text-align: right; border-right:3px #C1C1C1 solid">{{ $articulo_esperado->importe_comparativa_moneda_comparativa_f }}</td>
            <td style="text-align: right">{{ $articulo_esperado->sobrecosto_f }}</td>
            <td style="text-align: right">{{ $articulo_esperado->ahorro_f }}</td>
            <td style="text-align: right">{{ $articulo_esperado->indice_variacion_f }}</td>
            <td style=" border-right:3px #C1C1C1 solid" ><span class="label label-info" style="background-color: #{{ $articulo_esperado->estilo_grado_variacion }}">{{ $articulo_esperado->grado_variacion }}</label></td>
            <td>{{ $articulo_esperado->caso }}</td>
            <td 
                @if($articulo_esperado->error_concat != "")
                style="background-color: #f00; color:#FFF;border-right:3px #C1C1C1 solid"
                @else
                style="border-right:3px #C1C1C1 solid"
                @endif
                >
                {{$articulo_esperado->error_concat}}
        </td>
<!--        <td style=" border-right:3px #C1C1C1 solid">&nbsp;</td>-->
    </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="8" style="text-align: right; border-left:3px #C1C1C1 solid; border-bottom: 3px #C1C1C1 solid">Sumatorias:</td>
        <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulo_esperado->costo_total_proyecto_f}}</td>
        <td colspan="4" style="text-align: right; border-bottom: 3px #C1C1C1 solid">&nbsp;</td>
        <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulo_esperado->costo_total_proyecto_comparativa_f}}</td>
        <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulo_esperado->sobrecosto_total_f}}</td>
        <td  style="text-align: right; border-bottom: 3px #C1C1C1 solid">{{ $articulo_esperado->ahorro_total_f}}</td>
        <td colspan="4" style="text-align: right; border-bottom: 3px #C1C1C1 solid; border-right:3px #C1C1C1 solid;">&nbsp;</td>
    </tr>
</tfoot>
</table>
@else
<div class="alert alert-danger">
    <ul>
          <li>No se encontraron artículos con los filtros indicados</li>
    </ul>
  </div>
@endif

@stop
@section('scripts')
<script>
$("button.toggle_personalizacion").off().on("click", function(){
$("#frm_personalizacion").toggle();
$("#btn_personalizacion").toggle();
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
</script>
@stop