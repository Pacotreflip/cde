@extends('layout')

@section('content')
<h1>Programa de Pagos</h1>
<hr>
{!! Form::open(['route' => 'programa_pagos.index', 'method' => 'get']) !!}
{{ csrf_field() }}
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
        <div class="col-md-4" style="margin-top: 25px">
            <button type="submit" class="btn btn-small btn-info">
                <span class="glyphicon glyphicon-list-alt" style="margin-right: 5px"></span>Consultar
            </button>
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
</div>
{!! Form::close() !!}
<table class="table table-striped table-hover">
    <thead>
      <tr>
          <th style="width: 20px;text-align: center; border: solid 1px #CCC" rowspan="3">#</th>
          <th rowspan="3" style="text-align: center; border: solid 1px #CCC">Orden de Compra</th>
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
    @foreach($compras as $compra)
    <tr>
      <td>{{$i++}}</td>
      <td><a href="{{ route('compras.show', $compra) }}">OC #{{ $compra->folio_oc }}</a></td>
      @foreach($dias as $dia)
        @if(array_key_exists($dia->anio_mes_dia, $compra->anio_mes_dia_pago))
        <th  style="text-align: center; border: solid 1px #CCC;">
            <div class="popover-markup"> 
                    <span class="trigger label label-info" style="cursor: pointer">{{$compra->anio_mes_dia_pago[$dia->anio_mes_dia]["indice_pago"]}} %</span>
                <div class="head hide">
                    {{$compra->anio_mes_dia_pago[$dia->anio_mes_dia]["fecha"]}}
                    OC #{{$compra->anio_mes_dia_pago[$dia->anio_mes_dia]["folio_oc"]}}</div>
                <div class="content hide">
                    <div class="form-group">
                        <label>Monto Total OC:</label>
                        {{number_format($compra->monto, 2, '.', ',')}}
                    </div>  
                    <div class="form-group">
                        <label>Monto Programado:</label>
                        {{number_format($compra->anio_mes_dia_pago[$dia->anio_mes_dia]["monto"], 2, '.', ',')}}
                    </div>
                    <div class="form-group">
                        <label>Monto Total Programado:</label>
                        {{number_format($compra->totalProgramado(), 2, '.', ',')}}
                    </div>
                    <div class="form-group">
                        <label>Faltante:</label>
                        {{number_format(($compra->monto - $compra->totalProgramado()), 2, '.', ',')}}
                    </div>
                    
                </div>
            </div>
        </th>
        @endif
      @endforeach
    @endforeach
    </tbody>
</table>
@stop

@section('scripts')
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
</script>
@stop