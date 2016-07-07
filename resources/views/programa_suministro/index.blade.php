@extends('layout')

@section('content')
  <h1>Programa de Suministro
    
  </h1>
  <hr>
  <form method="get"  action="{{ route('programa_suministro.index') }}" style="float: right">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="get">
<div class="row">
    <div class="col-md-2">
                               
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
    <div class="col-md-2">
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
    <td><a href="{{ route('articulos.edit', $material) }}"> {{ $material->descripcion }}</a></td>
    @foreach($dias as $dia)
        @if(in_array($dia->anio_mes_dia,$material->anio_mes_dia_suministro))
            @if($hoy->format("Ymd")>$dia->anio_mes_dia && $material->getIndiceRecepcionAttribute($id_obra)< 100)
            <th  style="text-align: center; border: solid 1px #CCC; background: #ffcccc">
                @if($material->getIndiceRecepcionAttribute($id_obra)>0)
                    {{$material->getIndiceRecepcionAttribute($id_obra)}}
                    @else
                    <span class="alert-danger glyphicon glyphicon-exclamation-sign"></span>
                @endif
            </th>
            @elseif($hoy->format("Ymd")>$dia->anio_mes_dia && $material->getIndiceRecepcionAttribute($id_obra)== 100)
            <th  style="text-align: center; border: solid 1px #CCC;">
                <span class="alert-success glyphicon glyphicon-ok-sign"></span>
            </th>
            @else
                <th  style="text-align: center; border: solid 1px #CCC">
                    @if($material->getIndiceRecepcionAttribute($id_obra)>0)
                    {{$material->getIndiceRecepcionAttribute($id_obra)}}
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