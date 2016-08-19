@extends ('layout')

@section ('content')
@if(!$datos_secrets)
@elseif(count($datos_secrets) > 0)



<table class="tablesorter" id="table_sort" >
    <thead>
        <tr>
            <th style="text-align: center;"  >#</th>
            <th style="text-align: center;" >Tipo</th>
            <th style="text-align: center;" >Familia</th>
            <th style="text-align: center;" >Área</th>
            <th style="text-align: center;" >Material Secrets</th>
            <th style="text-align: center;" >Material Dreams</th>
            <th style="text-align: center;" >Secrets</th>
            <th style="text-align: center;" >Presupuesto</th>
            <th style="text-align: center;" >Total Dreams</th>
            <th style="text-align: center; width: 280px" colspan="2" >Var vs. Presupuesto </th>
            <th style="text-align: center;" >Comprado</th>
            <th style="text-align: center;" >Cotizado</th>
            <th style="text-align: center;" >Sin Cotizar</th>
            
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="6" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right">{{ number_format($datos_secrets->sum("secrets"),2) }}</th>
            <th style="text-align: right">{{ number_format($datos_secrets->sum("presupuesto"),2) }}</th>
            <th style="text-align: right">{{ number_format($datos_secrets->sum("total_dreams"),2) }}</th>
            
            <th style="text-align: right; width: 140px">{{ number_format($datos_secrets->sum("var_tp"),2) }}</th>
            @if($datos_secrets->sum("presupuesto")>0)
            <th style="text-align: right; width: 140px">{{ number_format($datos_secrets->sum("var_tp")/($datos_secrets->sum("presupuesto"))*100,2) }}</th>
            @else
            <th style="text-align: right; width: 140px">-</th>
            @endif
            
            <th style="text-align: right">{{ number_format($datos_secrets->sum("importe_dolares"),2) }}</th>
            <th style="text-align: right">{{ number_format($datos_secrets->sum("cotizado_para_acumular"),2) }}</th>
            <th style="text-align: right">{{ number_format($datos_secrets->sum("importe_sin_cotizar"),2) }}</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($datos_secrets as $datos_secrets_fila)
        <tr>
            <td style=" ">{{ $i ++ }}</td>
             <td style=" ">{{ $datos_secrets_fila->clasificador }}</td>
             <td style=" ">{{ $datos_secrets_fila->familia }}</td>
             <td style=" ">{{ $datos_secrets_fila->area_reporte }}</td>
             <td style=" ">{{ $datos_secrets_fila->material_secrets }}</td>
             <td style=" "><a href="{{ route('articulos.edit', [$datos_secrets_fila->id_material_dreams]) }}">{{ $datos_secrets_fila->material_dreams }}</a></td>
            <td style="text-align: right">{{ number_format($datos_secrets_fila->secrets,2) }}</td>
            <td style="text-align: right">{{ number_format($datos_secrets_fila->presupuesto,2) }}</td>
            <td style="text-align: right">{{ number_format($datos_secrets_fila->total_dreams,2) }}</td>
            
            <td style="text-align: right">{{ number_format($datos_secrets_fila->var_tp,2) }}</td>
            <td style="text-align: right">
                @if($datos_secrets_fila->var_tp_p == "")
                -
                @else
                {{ number_format($datos_secrets_fila->var_tp_p,2) }}
                @endif
            </td>
            
            <td style="text-align: right">{{ number_format($datos_secrets_fila->importe_dolares,2) }}</td>
            <td style="text-align: right">{{ number_format($datos_secrets_fila->cotizado_para_acumular,2) }}</td>
            <td style="text-align: right">{{ number_format($datos_secrets_fila->importe_sin_cotizar,2) }}</td>
        </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="6" style="text-align: right; ">Sumatorias:</td>
        <td style="text-align: right">{{ number_format($datos_secrets->sum("secrets"),2) }}</td>
        <td style="text-align: right">{{ number_format($datos_secrets->sum("presupuesto"),2) }}</td>
        <td style="text-align: right">{{ number_format($datos_secrets->sum("total_dreams"),2) }}</td>
        
        
        <td style="text-align: right">{{ number_format($datos_secrets->sum("var_tp"),2) }}</td>
        @if($datos_secrets->sum("presupuesto")>0)
            <td style="text-align: right">{{ number_format($datos_secrets->sum("var_tp")/($datos_secrets->sum("presupuesto"))*100,2) }}</td>
            @else
            <td style="text-align: right">-</td>
            @endif
        
        
        <td style="text-align: right">{{ number_format($datos_secrets->sum("importe_dolares"),2) }}</td>
        <td style="text-align: right">{{ number_format($datos_secrets->sum("cotizado_para_acumular"),2) }}</td>
        <td style="text-align: right">{{ number_format($datos_secrets->sum("importe_sin_cotizar"),2) }}</td>
    </tr>
</tfoot>
</table>

@endif
<form id="descargaExcel" method="post" action="{{ route("reportes.materiales_ordenes_compra_xls") }}" >{{ csrf_field() }}</form>

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
$(".tablesorter").tablesorter({
    theme : "blue",
    widgets :["indexFirstColumn","zebra"],
    headers: { 0: { sorter: false},19: { sorter: false},20: { sorter: false},21: { sorter: false},13: { sorter: false},14: { sorter: false},15: { sorter: false},16: { sorter: false}
    ,17: { sorter: false},18: { sorter: false}}
}
        );



});
});

$("button.descarga_excel").off().on("click", function(e){
    $("form#descargaExcel").submit();
});

</script>
@stop