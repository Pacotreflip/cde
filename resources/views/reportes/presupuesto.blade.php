@extends ('layout')

@section ('content')
@if(!$reporte_ffe)
@elseif(count($reporte_ffe) > 0)

<table class="tablesorter" id="table_sort" >
    <thead>
        <tr>
            <th colspan="4" style="text-align: center;"  >&nbsp;</th>
            <th style="text-align: center;" >Secrets</th>
            <th style="text-align: center;" >Presupuesto</th>
            <th style="text-align: center;" >Total Dreams </th>
            <th style="text-align: center;" colspan="2" >Var vs. Presupuesto </th>
            <th style="text-align: center;" >Comprado</th>
            <th style="text-align: center;" >Cotizado</th>
            
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="4" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right; cursor: pointer; text-decoration: underline" onclick="detalle_secrets('','','')">{{ number_format($reporte->sum("secrets"),2) }}</th>
            <th style="text-align: right; cursor: pointer; text-decoration: underline" onclick="detalle_secrets('','','')">{{ number_format($reporte->sum("presupuesto"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte->sum("total_dreams"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte->sum("var_tp"),2) }}</th>
            @if($reporte->sum("presupuesto")>0)
            <th style="text-align: right">{{ number_format($reporte->sum("var_tp")/($reporte->sum("presupuesto"))*100,2) }}</th>
            @else
            <th style="text-align: right">-</th>
            @endif
            <th style="text-align: right; text-decoration: underline" onclick="detalle_dreams('','','')">{{ number_format($reporte->sum("importe_dolares"),2) }}</th>
            <th style="text-align: right; text-decoration: underline" onclick="detalle_dreams('','','')">{{ number_format($reporte->sum("cotizado_para_acumular"),2) }}</th>
        </tr>
        
    </thead>
    <tbody></tbody>
</table>

<table class="tablesorter" id="table_sort" >
    <thead>
        <tr>
            <th style="text-align: center;"  >#</th>
            <th style="text-align: center;" >Tipo</th>
            <th style="text-align: center;" >Familia</th>
            <th style="text-align: center;" >Área</th>
            <th style="text-align: center;" >Secrets</th>
            <th style="text-align: center;" >Presupuesto</th>
            <th style="text-align: center;" >Total Dreams </th>
            <th style="text-align: center;" colspan="2" >Var vs. Presupuesto </th>
            <th style="text-align: center;" >Comprado</th>
            <th style="text-align: center;" >Cotizado</th>
            
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="4" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets('25','','')">{{ number_format($reporte_ffe->sum("secrets"),2) }}</th>
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets('25','','')">{{ number_format($reporte_ffe->sum("presupuesto"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte_ffe->sum("total_dreams"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte_ffe->sum("var_tp"),2) }}</th>
            @if($reporte_ffe->sum("presupuesto")>0)
            <th style="text-align: right">{{ number_format($reporte_ffe->sum("var_tp")/($reporte_ffe->sum("presupuesto"))*100,2) }}</th>
            @else
            <th style="text-align: right">-</th>
            @endif
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams('25','','')">{{ number_format($reporte_ffe->sum("importe_dolares"),2) }}</th>
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams('25','','')">{{ number_format($reporte_ffe->sum("cotizado_para_acumular"),2) }}</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($reporte_ffe as $reporte_ffe_fila)
        <tr>
            <td style=" ">{{ $i ++ }}</td>
             <td style=" ">{{ $reporte_ffe_fila->tipo }}</td>
             <td style=" ">{{ $reporte_ffe_fila->familia }}</td>
             <td style=" ">{{ $reporte_ffe_fila->area_reporte }}</td>
             
            @if($reporte_ffe_fila->secrets > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets_filas('25','{{$reporte_ffe_fila->id_familia}}','{{$reporte_ffe_fila->id_area_reporte}}')">{{ number_format($reporte_ffe_fila->secrets,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ffe_fila->secrets,2) }}</td>
            @endif
            
            @if($reporte_ffe_fila->presupuesto > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets_filas('25','{{$reporte_ffe_fila->id_familia}}','{{$reporte_ffe_fila->id_area_reporte}}')">{{ number_format($reporte_ffe_fila->presupuesto,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ffe_fila->presupuesto,2) }}</td>
            @endif
             
            <td style="text-align: right">{{ number_format($reporte_ffe_fila->total_dreams,2) }}</td>
            <td style="text-align: right">{{ number_format($reporte_ffe_fila->var_tp,2) }}</td>
            <td style="text-align: right">
                @if($reporte_ffe_fila->var_tp_p == "")
                -
                @else
                {{ number_format($reporte_ffe_fila->var_tp_p,2) }}
                @endif
            </td>
            @if($reporte_ffe_fila->importe_dolares  > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams_filas('25','{{$reporte_ffe_fila->id_familia}}','{{$reporte_ffe_fila->id_area_reporte}}')">{{ number_format($reporte_ffe_fila->importe_dolares,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ffe_fila->importe_dolares,2) }}</td>
            @endif
            
            @if($reporte_ffe_fila->cotizado_para_acumular > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams_filas('25','{{$reporte_ffe_fila->id_familia}}','{{$reporte_ffe_fila->id_area_reporte}}')">{{ number_format($reporte_ffe_fila->cotizado_para_acumular,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ffe_fila->cotizado_para_acumular,2) }}</td>
            @endif
        </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="4" style="text-align: right; ">Sumatorias:</td>
        <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets('25','','')">{{ number_format($reporte_ffe->sum("secrets"),2) }}</td>
        <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets('25','','')">{{ number_format($reporte_ffe->sum("presupuesto"),2) }}</td>
        <td style="text-align: right">{{ number_format($reporte_ffe->sum("total_dreams"),2) }}</td>
        <td style="text-align: right">{{ number_format($reporte_ffe->sum("var_tp"),2) }}</td>
        @if($reporte->sum("presupuesto")>0)
            <td style="text-align: right">{{ number_format($reporte_ffe->sum("var_tp")/($reporte_ffe->sum("presupuesto"))*100,2) }}</td>
            @else
            <td style="text-align: right">-</td>
            @endif
        <td style="text-align: right; text-decoration: underline" onclick="detalle_dreams('25','','')">{{ number_format($reporte_ffe->sum("importe_dolares"),2) }}</td>
        <td style="text-align: right; text-decoration: underline" onclick="detalle_dreams('25','','')">{{ number_format($reporte_ffe->sum("cotizado_para_acumular"),2) }}</td>
    </tr>
</tfoot>
</table>

@endif
@if(!$reporte_ose)
@elseif(count($reporte_ose) > 0)


<table class="tablesorter" id="table_sort" >
    <thead>
        <tr>
            <th style="text-align: center;"  >#</th>
            <th style="text-align: center;" >Tipo</th>
            <th style="text-align: center;" >Familia</th>
            <th style="text-align: center;" >Área</th>
            <th style="text-align: center;" >Secrets</th>
            <th style="text-align: center;" >Presupuesto</th>
            <th style="text-align: center;" >Total Dreams </th>
            <th style="text-align: center;" colspan="2" >Var vs. Presupuesto </th>
            <th style="text-align: center;" >Comprado</th>
            <th style="text-align: center;" >Cotizado</th>
            
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="4" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets('24','','')">{{ number_format($reporte_ose->sum("secrets"),2) }}</th>
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets('24','','')">{{ number_format($reporte_ose->sum("presupuesto"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte_ose->sum("total_dreams"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte_ose->sum("var_tp"),2) }}</th>
            @if($reporte_ffe->sum("presupuesto")>0)
            <th style="text-align: right">{{ number_format($reporte_ose->sum("var_tp")/($reporte_ose->sum("presupuesto"))*100,2) }}</th>
            @else
            <th style="text-align: right">-</th>
            @endif
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams('24','','')">{{ number_format($reporte_ose->sum("importe_dolares"),2) }}</th>
            <th style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams('24','','')">{{ number_format($reporte_ose->sum("cotizado_para_acumular"),2) }}</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($reporte_ose as $reporte_ose_fila)
        <tr>
            <td style=" ">{{ $i ++ }}</td>
             <td style=" ">{{ $reporte_ose_fila->tipo }}</td>
             <td style=" ">{{ $reporte_ose_fila->familia }}</td>
             <td style=" ">{{ $reporte_ose_fila->area_reporte }}</td>
             
            @if($reporte_ose_fila->secrets > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets_filas('24','{{$reporte_ose_fila->id_familia}}','{{$reporte_ose_fila->id_area_reporte}}')">{{ number_format($reporte_ose_fila->secrets,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ose_fila->secrets,2) }}</td>
            @endif
            
            @if($reporte_ose_fila->presupuesto > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets_filas('24','{{$reporte_ose_fila->id_familia}}','{{$reporte_ose_fila->id_area_reporte}}')">{{ number_format($reporte_ose_fila->presupuesto,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ose_fila->presupuesto,2) }}</td>
            @endif
             
            <td style="text-align: right">{{ number_format($reporte_ose_fila->total_dreams,2) }}</td>
            <td style="text-align: right">{{ number_format($reporte_ose_fila->var_tp,2) }}</td>
            <td style="text-align: right">
                @if($reporte_ose_fila->var_tp_p == "")
                -
                @else
                {{ number_format($reporte_ose_fila->var_tp_p,2) }}
                @endif
            </td>
            
            @if($reporte_ose_fila->importe_dolares > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams_filas('24','{{$reporte_ose_fila->id_familia}}','{{$reporte_ose_fila->id_area_reporte}}')">{{ number_format($reporte_ose_fila->importe_dolares,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ose_fila->importe_dolares,2) }}</td>
            @endif
            
            @if($reporte_ose_fila->cotizado_para_acumular > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams_filas('24','{{$reporte_ose_fila->id_familia}}','{{$reporte_ose_fila->id_area_reporte}}')">{{ number_format($reporte_ose_fila->cotizado_para_acumular,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_ose_fila->cotizado_para_acumular,2) }}</td>
            @endif
        </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="4" style="text-align: right; ">Sumatorias:</td>
        <td style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_secrets('24','','')">{{ number_format($reporte_ose->sum("secrets"),2) }}</td>
        <td style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_secrets('24','','')">{{ number_format($reporte_ose->sum("presupuesto"),2) }}</td>
        <td style="text-align: right">{{ number_format($reporte_ose->sum("total_dreams"),2) }}</td>
        <td style="text-align: right">{{ number_format($reporte_ose->sum("var_tp"),2) }}</td>
        @if($reporte_ose->sum("presupuesto")>0)
            <td style="text-align: right">{{ number_format($reporte_ose->sum("var_tp")/($reporte_ose->sum("presupuesto"))*100,2) }}</td>
            @else
            <td style="text-align: right">-</td>
            @endif
        <td style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_dreams('24','','')">{{ number_format($reporte_ose->sum("importe_dolares"),2) }}</td>
        <td style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_dreams('24','','')">{{ number_format($reporte_ose->sum("cotizado_para_acumular"),2) }}</td>
    </tr>
</tfoot>
</table>

@endif
@if(!$reporte_null)
@elseif(count($reporte_null) > 0)


<table class="tablesorter" id="table_sort" >
    <thead>
        <tr>
            <th style="text-align: center;"  >#</th>
            <th style="text-align: center;" >Tipo</th>
            <th style="text-align: center;" >Familia</th>
            <th style="text-align: center;" >Área</th>
            <th style="text-align: center;" >Secrets</th>
            <th style="text-align: center;" >Presupuesto</th>
            <th style="text-align: center;" >Total Dreams </th>
            <th style="text-align: center;" colspan="2" >Var vs. Presupuesto </th>
            <th style="text-align: center;" >Comprado</th>
            <th style="text-align: center;" >Cotizado</th>
            
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="4" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_dreams('null','','')">{{ number_format($reporte_null->sum("secrets"),2) }}</th>
            <th style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_dreams('null','','')">{{ number_format($reporte_null->sum("presupuesto"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte_null->sum("total_dreams"),2) }}</th>
            <th style="text-align: right">{{ number_format($reporte_null->sum("var_tp"),2) }}</th>
            @if($reporte_null->sum("presupuesto")>0)
            <th style="text-align: right">{{ number_format($reporte_null->sum("var_tp")/($reporte_null->sum("presupuesto"))*100,2) }}</th>
            @else
            <th style="text-align: right">-</th>
            @endif
            <th style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_dreams('null','','')">{{ number_format($reporte_null->sum("importe_dolares"),2) }}</th>
            <th style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_dreams('null','','')">{{ number_format($reporte_null->sum("cotizado_para_acumular"),2) }}</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($reporte_null as $reporte_null_fila)
        <tr>
            <td style=" ">{{ $i ++ }}</td>
             <td style=" ">{{ $reporte_null_fila->tipo }}</td>
             <td style=" ">{{ $reporte_null_fila->familia }}</td>
             <td style=" ">{{ $reporte_null_fila->area_reporte }}</td>
             
            @if($reporte_null_fila->secrets > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets_filas('null','{{$reporte_null_fila->id_familia}}','{{$reporte_null_fila->id_area_reporte}}')">{{ number_format($reporte_null_fila->secrets,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_null_fila->secrets,2) }}</td>
            @endif
            
            @if($reporte_null_fila->presupuesto > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_secrets_filas('null','{{$reporte_null_fila->id_familia}}','{{$reporte_null_fila->id_area_reporte}}')">{{ number_format($reporte_null_fila->presupuesto,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_null_fila->presupuesto,2) }}</td>
            @endif
             
            <td style="text-align: right">{{ number_format($reporte_null_fila->total_dreams,2) }}</td>
            <td style="text-align: right">{{ number_format($reporte_null_fila->var_tp,2) }}</td>
            <td style="text-align: right">
                @if($reporte_null_fila->var_tp_p == "")
                -
                @else
                {{ number_format($reporte_null_fila->var_tp_p,2) }}
                @endif
            </td>
            
            @if($reporte_null_fila->importe_dolares  > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams_filas('null','{{$reporte_null_fila->id_familia}}','{{$reporte_null_fila->id_area_reporte}}')">{{ number_format($reporte_null_fila->importe_dolares,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_null_fila->importe_dolares,2) }}</td>
            @endif
            
            @if($reporte_null_fila->cotizado_para_acumular > 0)
                <td style="text-align: right; text-decoration: underline; cursor: pointer" onclick="detalle_dreams_filas('null','{{$reporte_null_fila->id_familia}}','{{$reporte_null_fila->id_area_reporte}}')">{{ number_format($reporte_null_fila->cotizado_para_acumular,2) }}</td>
            @else
                <td style="text-align: right;">{{ number_format($reporte_null_fila->cotizado_para_acumular,2) }}</td>
            @endif
            
            
        </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="4" style="text-align: right; ">Sumatorias:</td>
        <td style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_secrets('null','','')">{{ number_format($reporte_null->sum("secrets"),2) }}</td>
        <td style="text-align: right; text-decoration: underline; cursor:pointer" onclick="detalle_secrets('null','','')">{{ number_format($reporte_null->sum("presupuesto"),2) }}</td>
        <td style="text-align: right">{{ number_format($reporte_null->sum("total_dreams"),2) }}</td>
        <td style="text-align: right">{{ number_format($reporte_null->sum("var_tp"),2) }}</td>
        @if($reporte_null->sum("presupuesto")>0)
            <td style="text-align: right">{{ number_format($reporte_null->sum("var_tp")/($reporte_null->sum("presupuesto"))*100,2) }}</td>
            @else
            <td style="text-align: right">-</td>
            @endif
        <td style="text-align: right; text-decoration: underline" onclick="detalle_dreams('null','','')">{{ number_format($reporte_null->sum("importe_dolares"),2) }}</td>
        <td style="text-align: right; text-decoration: underline" onclick="detalle_dreams('null','','')">{{ number_format($reporte_null->sum("cotizado_para_acumular"),2) }}</td>
    </tr>
</tfoot>
</table>

@endif
<form id="detalleDreams" method="post" action="{{ route("reportes.presupuesto_detalle_dreams") }}" >
    {{ csrf_field() }}
    <input type="hidden" name="id_tipo" id="id_tipo" value="" />
    <input type="hidden" name="id_familia" id="id_familia" value="" />
    <input type="hidden" name="id_area_reporte" id="id_area_reporte" value="" />
</form>
<form id="detalleSecrets" method="post" action="{{ route("reportes.presupuesto_detalle_secrets") }}" >
    {{ csrf_field() }}
    <input type="hidden" name="id_tipo" id="id_tipo" value="" />
    <input type="hidden" name="id_familia" id="id_familia" value="" />
    <input type="hidden" name="id_area_reporte" id="id_area_reporte" value="" />
</form>
@stop
@section('scripts')
<script>
function detalle_dreams(tipo, familia, area){
    $("#id_tipo").val(tipo);
    $("#id_familia").val(familia);
    $("#id_area_reporte").val(area);
    $("#detalleDreams").submit();
}
function detalle_dreams_filas(tipo, familia, area){
    $("#id_tipo").val(tipo);
    if(familia === ""){
        familia = "null";
    }
    
    $("#id_familia").val(familia);
    if(area === ""){
        area = "null";
    }
    $("#id_area_reporte").val(area);
    $("#detalleDreams").submit();
}
function detalle_secrets(tipo, familia, area){
    $("#detalleSecrets #id_tipo").val(tipo);
    $("#detalleSecrets #id_familia").val(familia);
    $("#detalleSecrets #id_area_reporte").val(area);
    $("#detalleSecrets").submit();
}
function detalle_secrets_filas(tipo, familia, area){
    $("#detalleSecrets #id_tipo").val(tipo);
    if(familia === ""){
        familia = "null";
    }
    
    $("#detalleSecrets #id_familia").val(familia);
    if(area === ""){
        area = "null";
    }
    $("#detalleSecrets #id_area_reporte").val(area);
    $("#detalleSecrets").submit();
}
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
    headers: { 0: { sorter: false},8: { sorter: false},9: { sorter: false},10: { sorter: false},11: { sorter: false}}
}
        );



});
});

$("button.descarga_excel").off().on("click", function(e){
    $("form#descargaExcel").submit();
});

</script>
@stop