@extends ('layout')

@section ('content')
<div  style="text-align: right">
    <button class="btn btn-small btn-success descarga_excel" type="button" >
        <span class="fa fa-download" style="margin-right: 5px"></span> Descarga en Excel
    </button>
</div>
<table class="tablesorter" id="table_sort" >
<!--    <caption><span class="glyphicon glyphicon-filter" style="margin-right: 5px"></span>Todos los Artículos</caption>-->
    <thead>
<!--               <tr>
<th colspan="20" scope="col">OS&amp;E</th>
</tr>-->
        <tr>
            <td scope="col" colspan="2" style="text-align: center; border:1px #FFF solid; background-color: #fff">&nbsp;</td>
            <th colspan="15" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Este Proyecto</th>
            <th colspan="9" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Proyecto Comparativo</th>
        </tr>
        
        <tr>
            <th style="text-align: center;  border-top:3px #C1C1C1 solid;  border-left:3px #C1C1C1 solid">#</th>
            <td style="text-align: center;border-top:3px #C1C1C1 solid">Tipo</td>

            <td style="text-align: center; border-left:3px #C1C1C1 solid">Cantidad </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Variación Cantidad </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Número Módulos </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Variación Número Módulos </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">PAX </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Variación PAX </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Manual </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Manual x Módulo </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Manual x PAX </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Calculado </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Calculado x Módulo </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Calculado x PAX </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Importe Compras </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Importe Compras x Módulo </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Importe Compras x PAX </td>
            
            
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Cantidad </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Número Módulos </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">PAX </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Manual </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Manual x Módulo </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Manual x PAX </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Calculado </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Calculado x Módulo </td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Presupuesto Calculado x PAX </td>
            
        </tr>
        
    </thead>
    <tbody>
        @foreach($reporte as $partida)
        <tr>
            <td style=" border-left:3px #C1C1C1 solid">{{ $i ++ }}</td>
            <td>{{ $partida->tipo }}</td>
            
            <td style="text-align: right">{{ $partida->cantidad }}</td>
            <td style="text-align: right">{{ number_format($partida->variacion_cantidad,2) }}</td>
            <td style="text-align: right">{{ $partida->numero_modulos }}</td>
            <td style="text-align: right">{{ number_format($partida->variacion_modulos,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->pax,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->variacion_pax,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_manual_x_cantidad,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_manual_x_cantidad_s_modulos,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_manual_x_cantidad_s_pax,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_calculado_x_cantidad,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_calculado_x_cantidad_s_modulos,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_calculado_x_cantidad_s_pax,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_compras_emitidas_x_cantidad,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_compras_emitidas_x_cantidad_s_modulos,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_compras_emitidas_x_cantidad_s_pax,2) }}</td>
            
            <td style="text-align: right">{{ $partida->cantidad_comparativa }}</td>
            <td style="text-align: right">{{ $partida->numero_modulos_comparativa }}</td>
            <td style="text-align: right">{{ number_format($partida->pax_comparativa,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_manual_x_cantidad_comparativa,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_manual_x_cantidad_comparativa_s_nm,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_manual_x_cantidad_comparativa_s_pax,2) }}</td>
            
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_calculado_x_cantidad_comparativa,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_calculado_x_cantidad_comparativa_s_nm,2) }}</td>
            <td style="text-align: right">{{ number_format($partida->importe_presupuesto_calculado_x_cantidad_comparativa_s_pax,2) }}</td>

    </tr>
    @endforeach

</tbody>

</table>
<form id="descargaExcel" method="post" action="{{ route("reportes.comparativa_xls") }}" >{{ csrf_field() }}</form>
@stop
@section('scripts')
<script>

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
    headers: { 0: { sorter: false},1: { sorter: false},2: { sorter: false},3: { sorter: false}}
}
        );



});
});


$("button.descarga_excel").off().on("click", function(e){
    $("form#descargaExcel").submit();
});
</script>
@stop