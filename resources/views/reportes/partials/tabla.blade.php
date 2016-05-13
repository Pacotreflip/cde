@if($articulos_esperados == "")
@elseif(count($articulos_esperados) > 0)
<table class="tablesorter" id="table_sort" >
<!--    <caption><span class="glyphicon glyphicon-filter" style="margin-right: 5px"></span>Todos los Artículos</caption>-->
    <thead>
<!--               <tr>
<th colspan="20" scope="col">OS&amp;E</th>
</tr>-->
<tr>
            <td scope="col" colspan="6" style="text-align: center; border:1px #FFF solid; background-color: #fff">&nbsp;</td>
            <th colspan="5" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Este Proyecto</th>
            <th colspan="5" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Proyecto Comparativo</th>
            <th colspan="4" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Variaciones</th>
            <th colspan="2" scope="col" style="text-align: center; border:3px #C1C1C1 solid">Control</th>
        </tr>
        
        <tr>
            <th style="text-align: center;  border-top:3px #C1C1C1 solid;  border-left:3px #C1C1C1 solid">#</th>
            <th style="text-align: center;  border-top:3px #C1C1C1 solid;  border-top:3px #C1C1C1 solid"># Areas</th>
            <td style="text-align: center;border-top:3px #C1C1C1 solid">Clasificador</td>
            <td style="text-align: center;border-top:3px #C1C1C1 solid">Familia</td>
            <td style="text-align: center; border-top:3px #C1C1C1 solid">Descripción</td>
            <td style="text-align: center; border-top:3px #C1C1C1 solid">Unidad</td>
            <td style="text-align: center; border-left:3px #C1C1C1 solid">Cantidad </td>
            <td style="text-align: center; ">Precio Unitario  </td>
            <td style="text-align: center; ">Moneda</td>
            <td style="text-align: center; ">Precio Unitario ({{$moneda_comparativa->nombre}})</td>
            <td style="text-align: center;  border-right:3px #C1C1C1 solid">Importe ({{$moneda_comparativa->nombre}})</td>
            <td style="text-align: center;  ">Cantidad </td>
            <td style="text-align: center; ">Precio Unitario </td>
            <td style="text-align: center; ">Moneda </td>
            <td style="text-align: center; ">Precio Unitario ({{$moneda_comparativa->nombre}}) </td>
            <td style="text-align: center;  border-right:3px #C1C1C1 solid">Importe ({{$moneda_comparativa->nombre}})</td>
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
            <td>{{ $articulo_esperado->veces_requerida }}</td>   
            <td>{{ $articulo_esperado->clasificador }}</td>   
            <td>{{ $articulo_esperado->familia }}</td>   
            <td><a href="{{ route("articulos.edit", $articulo_esperado->id_material) }}">{{ $articulo_esperado->material }}</a></td>
            <td>{{ $articulo_esperado->unidad }}</td>
            <td style="text-align: right;border-left:3px #C1C1C1 solid">{{ $articulo_esperado->cantidad_requerida }}</td>
            <td style="text-align: right">{{ $articulo_esperado->precio_estimado_f }}</td>
            <td>{{ $articulo_esperado->moneda_requerida }}</td>
            <td style="text-align: right;">{{ $articulo_esperado->precio_requerido_moneda_comparativa_f }}</td>
            <td style="text-align: right;border-right:3px #C1C1C1 solid">{{ $articulo_esperado->precio_requerido_moneda_comparativa_f }}</td>
            
            <td style="text-align: right">{{ $articulo_esperado->cantidad_comparativa }}</td>
            <td style="text-align: right">{{ $articulo_esperado->precio_proyecto_comparativo_f }}</td>
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
        <td colspan="10" style="text-align: right; border-left:3px #C1C1C1 solid; border-bottom: 3px #C1C1C1 solid">Sumatorias:</td>
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