@extends ('layout')
@section ('styles')
<style>
.venntooltip {
  position: absolute;
  text-align: center;
  width: 128px;
  height: 25px;
  background: #333;
  color: #ddd;
  padding: 2px;
  border: 0px;
  border-radius: 8px;
  opacity: 0;
}
</style>
@stop
@section ('content')
@if($materiales_oc == "")
@elseif(count($materiales_oc) > 0)
<div id="venn"></div>
<div class="venntooltip"></div>
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
            <th style="text-align: center;" >Familia</th>
            <th style="text-align: center;" >Descripción</th>
            <th style="text-align: center;" >Unidad </th>
            
            <th style="text-align: center;" >Cantidad Compra </th>
            <th style="text-align: center;" >Precio Unitario Compra </th>
            <th style="text-align: center;" >Moneda Compra</th>
            <th style="text-align: center;" >Precio Unitario Compra({{$moneda_comparativa->nombre}})</th>
            <th style="text-align: center;" >Importe Compra ({{$moneda_comparativa->nombre}})</th>
            
            <th style="text-align: center;" >Cantidad Requerida </th>
            <th style="text-align: center;" >Precio Unitario Requerido </th>
            <th style="text-align: center;" >Moneda Requerida</th>
            <th style="text-align: center;" >Precio Unitario Requerida({{$moneda_comparativa->nombre}})</th>
            <th style="text-align: center;" >Importe Requerido ({{$moneda_comparativa->nombre}})</th>
            <th style="text-align: center;" >Caso</th>
        </tr>
        <tr style="background-color: #C1C1C1">
            <th colspan="4" style="text-align: right; ">Sumatorias:</th>
            <th style="text-align: right">{{ number_format($materiales_oc->sum("cantidad_compra"),2) }}</th>
            <th colspan="3" style="text-align: right; ">&nbsp;</th>
            <th style="text-align: right">{{ number_format($materiales_oc->sum("importe_compra_moneda_comparativa"),2) }}</th>
            <th style="text-align: right">{{ number_format($materiales_oc->sum("cantidad_requerida"),2) }}</th>
            <th colspan="3" style="text-align: right; ">&nbsp;</th>
            <th style="text-align: right">{{ number_format($materiales_oc->sum("importe_requerido_moneda_comparativa"),2) }}</th>
            <th style="text-align: right; ">&nbsp;</th>
        </tr>
        
    </thead>
    <tbody>
        @foreach($materiales_oc as $material_oc)
        <tr>
            <td style=" ">{{ $i ++ }}</td>
            <td >{{ $material_oc->familia }}</td> 
            <td><a href="{{ route("articulos.edit", $material_oc->id_material) }}">{{ $material_oc->material }}</a></td>   
            <td>{{ $material_oc->unidad }}</td>   
            <td style="text-align: right;">{{ $material_oc->cantidad_compra }}</td>   
            <td style="text-align: right;">{{ number_format($material_oc->precio_compra,2) }}</td>
            <td>{{ $material_oc->moneda_compra }}</td>
            <td style="text-align: right;">{{ number_format($material_oc->precio_compra_moneda_comparativa,2) }}</td>
            <td style="text-align: right">{{ number_format($material_oc->importe_compra_moneda_comparativa,2) }}</td>
            
            <td style="text-align: right;">{{ $material_oc->cantidad_requerida }}</td>   
            <td style="text-align: right;">{{ number_format($material_oc->precio_requerido,2) }}</td>
            <td>{{ $material_oc->moneda_compra }}</td>
            <td style="text-align: right;">{{ number_format($material_oc->precio_requerido_moneda_comparativa,2) }}</td>
            <td style="text-align: right">{{ number_format($material_oc->importe_requerido_moneda_comparativa,2) }}</td>
            <td>{{ $material_oc->caso }}</td> 
        </tr>
    @endforeach

</tbody>
<tfoot>
    <tr style="background-color: #C1C1C1">
        <td colspan="3" style="text-align: right; ">Sumatorias:</td>
        <td style="text-align: right">{{ number_format($materiales_oc->sum("cantidad_compra"),2) }}</td>
        <td colspan="3" style="text-align: right; ">&nbsp;</td>
        <td style="text-align: right">{{ number_format($materiales_oc->sum("importe_compra_moneda_comparativa"),2) }}</td>
        <td style="text-align: right">{{ number_format($materiales_oc->sum("cantidad_requerida"),2) }}</td>
        <td colspan="3" style="text-align: right; ">&nbsp;</td>
        <td style="text-align: right">{{ number_format($materiales_oc->sum("importe_requerido_moneda_comparativa"),2) }}</td>
        <td  style="text-align: right; ">&nbsp;</td>
    </tr>
</tfoot>
</table>

<form id="descargaExcel" method="post" action="{{ route("reportes.materiales_oc_vs_materiales_req_xls") }}" >{{ csrf_field() }}</form>

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
    headers: { 0: { sorter: false},8: { sorter: false},9: { sorter: false},10: { sorter: false},11: { sorter: false}
        
        ,14: { sorter: false},15: { sorter: false},16: { sorter: false},17: { sorter: false},18: { sorter: false},19: { sorter: false},20: { sorter: false},21: { sorter: false}
        
    }
}
        );



});
});

$("button.descarga_excel").off().on("click", function(e){
    $("form#descargaExcel").submit();
});

</script>
<script src="{{ asset("js/d3.js") }}"></script>
<script src="{{ asset("js/venn.js") }}"></script>
<script>
var sets = [ {sets: ['COMPRADOS'], size: {{$venn["COMPRADO"]}}  },
             {sets: ['REQUERIDOS'], size: {{$venn["REQUERIDO"]}} },
             {sets: ['COMPRADOS','REQUERIDOS'], size: {{$venn["REQUERIDO Y COMPRADO"]}} }];

var chart = venn.VennDiagram().width(250)
                             .height(200);
d3.select("#venn").datum(sets).call(chart);

d3.selectAll("#venn .venn-circle text")
    .style("font-size", "12px")
    ;

var div = d3.select("#venn")
//var tooltip = d3.select("body").append("div")
//    .attr("class", "venntooltip");
var tooltip = d3.select(".venntooltip");
// add listeners to all the groups to display tooltip on mouseover
div.selectAll("g")
    .on("mouseover", function(d, i) {
        // sort all the areas relative to the current item
        venn.sortAreas(div, d);

        // Display a tooltip with the current size
        tooltip.transition().duration(400).style("opacity", .9);
        tooltip.text(d.size + " materiales");

        // highlight the current path
        var selection = d3.select(this).transition("tooltip").duration(400);
        selection.select("path")
            .style("stroke-width", 3)
            .style("fill-opacity", d.sets.length == 1 ? .4 : .1)
            .style("stroke-opacity", 1);
    })

    .on("mousemove", function() {
        tooltip.style("left", (d3.event.pageX) + "px")
               .style("top", (d3.event.pageY - 28) + "px");
    })

    .on("mouseout", function(d, i) {
        tooltip.transition().duration(400).style("opacity", 0);
        var selection = d3.select(this).transition("tooltip").duration(400);
        selection.select("path")
            .style("stroke-width", 0)
            .style("fill-opacity", d.sets.length == 1 ? .25 : .0)
            .style("stroke-opacity", 0);
    });

</script>
@stop