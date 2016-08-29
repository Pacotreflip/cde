@extends('layout')

@section('content')
  <h1>Programa de Suministro
    
  </h1>
  <hr>
  <form id="form" method="get"  action="{{ route('programa_suministro.index') }}" style="float: right">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="get">
    <input type='hidden' name="xls" value="0"/>
<div class="row">
    <div class="col-md-6">
        <div class="col-md-4">

            <div class="form-group">
            <label for="cantidad" >Fecha Inicial:</label>
            <div class='input-group date' >
                <input type='date' name="fecha_inicial" class="form-control" value="{{$fecha_inicial}}"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
            <label for="cantidad" >Fecha Final:</label>
            <div class='input-group date'>
                <input type='date' name="fecha_final" class="form-control" value="{{$fecha_final}}"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            </div>
        </div>
        <div class="col-md-2" style="margin-top: 25px">
            <button type="submit" class="btn btn-small btn-info consultar">
                <span class="glyphicon glyphicon-list-alt" style="margin-right: 5px"></span>Consultar
            </button>
        </div>
        <div class="col-md-2" style="margin-top: 25px">
            <a class="btn btn-small btn-success descargar_excel">
                <span class="fa fa-table" style="margin-right: 5px"></span>Descargar Excel
            </a>
        </div>
        <div class="col-md-8">
            <div class="form-group">
            <label for="proveedor" >Proveedor:</label>
            <select name="proveedor" id="proveedor" style="width: 100%">
                <option value selected>-- SELECCIONAR PROVEEDOR --</option>
                <!--<option value="">VER TODOS</option>-->
                @foreach($proveedores as $p)
                <option {{$proveedor == $p->razon_social ? "selected" : ""}} value="{{$p->razon_social}}">{{ $p->razon_social }}</option>
                @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6" >
        <table class="table">
            <caption><strong>Simbología</strong></caption>
            <tr>
                <th  style="text-align: center; border: solid 1px #CCC; width: 16px;">
                   <span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>
                </th>
                <td style="border: solid 1px #CCC;">
                    No se ha recibido ningún artículo y la fecha esperada de entrega ha sido rebasada
                </td>
                <th  style="text-align: center; border: solid 1px #CCC; width: 16px;">
                  <span class="label label-danger">%</span>
                </th>
                <td style="border: solid 1px #CCC;">
                     Se han recibido algunos artículos y la fecha esperada de entrega ha sido rebasada
                </td>
            </tr>
            <tr>
                <th  style="text-align: center; border: solid 1px #CCC; width: 16px;">
                   <span class="alert-info glyphicon glyphicon-certificate"></span>
                </th>
                <td style="border: solid 1px #CCC;">
                    No se ha recibido ningún artículo y la fecha esperada de entrega no ha sido rebasada
                </td>
                <th  style="text-align: center; border: solid 1px #CCC; width: 16px;">
                   <span class="label label-info ">%</span>
                </th>
                <td style="border: solid 1px #CCC;">
                    Se han recibido algunos artículos y la fecha esperada de entrega no ha sido rebasada
                </td>
            </tr>
            <tr>
                <th  style="text-align: center; border: solid 1px #CCC; width: 16px">
                    <span class="alert-success glyphicon glyphicon-ok-sign"></span>
                </th>
                <td style="border: solid 1px #CCC;">
                    Suministrado Completamente (100%)
                </td>
            </tr>
        </table>
    </div>
</div>
</form>
  <table class="table table-striped table-hover">
    <thead>
      <tr>
          <th style="width: 20px;text-align: center; border: solid 1px #CCC" rowspan="3">#</th>
          <th rowspan="3" style="text-align: center; border: solid 1px #CCC">Material</th>
        @foreach($anios as $anio)
        <th colspan="{{$anio->cantidad_dias}}" style="text-align: center; border: solid 1px #CCC">{{$anio->anio}}</th>
        @endforeach
        </tr>
        <tr>
            @foreach($meses as $mes)
            @if($mes->cantidad_dias>1)
            <th colspan="{{$mes->cantidad_dias}}" style="text-align: center; border: solid 1px #CCC">{{$mes->mesdes}}</th>
            @else
            <th colspan="{{$mes->cantidad_dias}}" style="text-align: center; border: solid 1px #CCC">{{$mes->mesdescor}}</th>
            @endif
            @endforeach
        </tr>
        <tr>
            @foreach($dias as $dia)
            <th  style="text-align: center; border: solid 1px #CCC;width: 15px">{{$dia->dia}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
  @foreach($materiales as $material)
  <tr>
    <td>{{$i++}}</td>
    <td><a href="{{ route('articulos.edit', $material) }}"> {{ $material->descripcion.' [OC #'.$material->folio_oc.']' }}</a></td>
    @foreach($dias as $dia)
        @if(array_key_exists($dia->anio_mes_dia, $material->anio_mes_dia_suministro($material->folio_oc)) )
            @if($hoy->format("Ymd")>=$dia->anio_mes_dia && $material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]< 100)
            <th  style="text-align: center; border: solid 1px #CCC;">
                @if($material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]>0)
                <div class="popover-markup"> 
                    <span class="trigger label label-danger" style="cursor: pointer">{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]}}</span>
                    <div class="head hide">
                        {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["fecha_entrega"]}}
                        OC #{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["folio_oc"]}}</div>
                    <div class="content hide">
                        <div class="form-group">
                            <label>Cantidad:</label>
                            {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["cantidad"]}}
                        </div>
                         <div class="form-group">
                            <label>Cantidad Rec.:</label>
                            {{$material->cantidad_recibida}}
                        </div>
                        <form action="{{route("recepciones.create")}}/{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["id_oc"]}}" method="get">
                            <button type="submit" class="btn btn-default btn-block recibir">
                                Recibir
                            </button>
                        </form>
                        
                    </div>
                </div>
                @else
                <div class="popover-markup"> 
                    <span class="alert-danger glyphicon glyphicon-exclamation-sign trigger"  style="cursor: pointer"></span>
                    <div class="head hide">
                        {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["fecha_entrega"]}}
                        OC #{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["folio_oc"]}}</div>
                    <div class="content hide">
                        <div class="form-group">
                            <label>Cantidad:</label>
                            {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["cantidad"]}}
                        </div>
                        <form action="{{route("recepciones.create")}}/{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["id_oc"]}}" method="get">
                            <button type="submit" class="btn btn-default btn-block recibir">
                                Recibir
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </th>
            @elseif($material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]== 100)
            <th  style="text-align: center; border: solid 1px #CCC;">
                
                <div class="popover-markup"> 
                    <span class="alert-success glyphicon glyphicon-ok-sign trigger" style="cursor: pointer"></span>
                    <div class="head hide">
                        {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["fecha_entrega"]}}
                        OC #{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["folio_oc"]}}</div>
                    <div class="content hide">
                        <div class="form-group">
                            <label>Cantidad:</label>
                            {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["cantidad"]}}
                        </div>
                    </div>
                </div>
            </th>
            @elseif($hoy->format("Ymd")<$dia->anio_mes_dia)
                <th  style="text-align: center; border: solid 1px #CCC">
                    @if($material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]>0)
                    
                    
                    <div class="popover-markup"> 
                        <span class="trigger label label-info " style="cursor: pointer">{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["indice_suministro"]}}</span>
                        <div class="head hide">
                            {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["fecha_entrega"]}}
                            OC #{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["folio_oc"]}}</div>
                        <div class="content hide">
                            <div class="form-group">
                                <label>Cantidad:</label>
                                {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["cantidad"]}}
                            </div>
                            <div class="form-group">
                                <label>Cantidad Rec.:</label>
                                {{$material->cantidad_recibida}}
                            </div>
                            <form action="{{route("recepciones.create")}}/{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["id_oc"]}}" method="get">
                            <button type="submit" class="btn btn-default btn-block recibir">
                                Recibir
                            </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="popover-markup"> 
                        <span class="alert-info glyphicon glyphicon-certificate trigger" style="cursor: pointer"></span>
                        <div class="head hide">
                            {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["fecha_entrega"]}}
                            OC #{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["folio_oc"]}}</div>
                        <div class="content hide">
                            <div class="form-group">
                                <label>Cantidad:</label>
                                {{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["cantidad"]}}
                            </div>
                            <form action="{{route("recepciones.create")}}/{{$material->anio_mes_dia_suministro($material->folio_oc)[$dia->anio_mes_dia]["id_oc"]}}" method="get">
                                <button type="submit" class="btn btn-default btn-block recibir">
                                    Recibir
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </th>
            @endif
        @else
        <th  style="text-align: center; border: solid 1px #CCC">
            
        </th>
        @endif
    @endforeach
  </tr>
  @endforeach
  </tbody>
  </table>
  
@stop
@section("scripts")
<script>
$('#proveedor').select2();
$('.popover-markup>.trigger').popover({
    html: true,
    placement: "left",
    title: function () {
        return $(this).parent().find('.head').html();
    },
    content: function () {
        return $(this).parent().find('.content').html();
    }
});
$('.descargar_excel').off().on('click', function(e){
    $('input[name=xls]').val(1);
    $('#form').submit();
});

$('.consultar').off().on('click', function(e){
    $('input[name=xls]').val(0);
    $('#form').submit();
});
</script>
@stop