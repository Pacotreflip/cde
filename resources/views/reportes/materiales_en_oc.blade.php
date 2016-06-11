@extends ('layout')

@section ('content')
@if($materiales_oc == "")
@elseif(count($materiales_oc) > 0)
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
            <th style="text-align: center;"  >#</th>
            <th style="text-align: center;" >OC</th>
            <th style="text-align: center;" >Fechas Entrega</th>
            <th style="text-align: center;" >Familia</th>
            <th style="text-align: center;" >Descripción</th>
            <th style="text-align: center;" >Unidad Compra</th>
            <th style="text-align: center;" >Cantidad Compra </th>
            <th style="text-align: center;" >Precio Unitario Compra </th>
            <th style="text-align: center;" >Moneda Compra</th>
            <th style="text-align: center;" >Precio Unitario Compra({{$moneda_comparativa->nombre}})</th>
            <th style="text-align: center;" >Importe Compra ({{$moneda_comparativa->nombre}})</th>
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="6" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right">{{ number_format($materiales_oc->sum("cantidad_compra"),2) }}</th>
            <th colspan="3" style="text-align: right; ">&nbsp;</th>
            <th style="text-align: right">{{ number_format($materiales_oc->sum("importe_compra_moneda_comparativa"),2) }}</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($materiales_oc as $material_oc)
        <tr>
            <td style=" ">{{ $i ++ }}</td>
            @if(strpos($material_oc->ordenes_compra,",")>0)
            <td style=" "><a href="{{ route("compras.index_x_material", $material_oc->id_material) }}">{{ $material_oc->ordenes_compra }}</a></td>
            @else
            <td style=" "><a href="{{ route("compras.show", $material_oc->id_orden_compra) }}">{{ $material_oc->ordenes_compra }}</a></td>
            @endif
            <td>{{ $material_oc->fechas_entrega }}</td>  
            <td>{{ $material_oc->familia }}</td>  
            <td><a href="{{ route("articulos.edit", $material_oc->id_material) }}">{{ $material_oc->material }}</a></td>   
            <td>{{ $material_oc->unidad }}</td>   
            <td style="text-align: right;">{{ $material_oc->cantidad_compra }}</td>   
            <td style="text-align: right;">{{ number_format($material_oc->precio_compra,2) }}</td>
            <td>{{ $material_oc->moneda_compra }}</td>
            <td style="text-align: right;">{{ number_format($material_oc->precio_compra_moneda_comparativa,2) }}</td>
            <td style="text-align: right">{{ number_format($material_oc->importe_compra_moneda_comparativa,2) }}</td>
        </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="6" style="text-align: right; ">Sumatorias:</td>
        <td style="text-align: right">{{ number_format($materiales_oc->sum("cantidad_compra"),2) }}</td>
        <td colspan="3" style="text-align: right; ">&nbsp;</td>
        <td style="text-align: right">{{ number_format($materiales_oc->sum("importe_compra_moneda_comparativa"),2) }}</td>
    </tr>
</tfoot>
</table>
<form id="descargaExcel" method="post" action="{{ route("reportes.materiales_ordenes_compra_xls") }}" >{{ csrf_field() }}</form>

@else
<div class="alert alert-danger">
    <ul>
          <li>No se encontraron artículos</li>
    </ul>
  </div>
@endif
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