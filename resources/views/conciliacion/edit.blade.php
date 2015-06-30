@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li>{!! link_to_route('conciliacion.almacenes', $conciliacion->empresa->razon_social, [$conciliacion->id_empresa]) !!}</li>
        <li>{!! link_to_route('conciliacion.index', $conciliacion->almacen->descripcion, [$conciliacion->id_empresa, $conciliacion->id_almacen]) !!}</li>
        <li class="active">{{ $conciliacion->present()->periodo }}</li>
    </ol>

    <div class="col-sm-12">

        <div class="page-header">
            <h1>Conciliación {!! $conciliacion->present()->statusLabel !!}</h1>
        </div>

        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-6">
                            Proveedor: <strong>{{ $conciliacion->empresa->razon_social }}</strong>
                        </div>
                        <div class="col-sm-3">
                            Dias del periodo: <strong>{{ $conciliacion->present()->dias_conciliados }}</strong>
                        </div>
                        <div class="col-sm-3">
                            Dias con operación: <strong>{{ $conciliacion->present()->dias_con_operacion }}</strong>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-sm-6">
                            Periodo: <strong>{{ $conciliacion->present()->periodo }}</strong>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <p>Almacén en conciliación: <strong>{{ $conciliacion->almacen->descripcion }}</strong></p>
                    <p>Horas del contrato vigente en periodo: <strong>{{ $conciliacion->present()->horas_contrato }}</strong></p>
                    @if ( ! $conciliacion->operacionEstaCompleta())
                        <div class="alert alert-warning">
                            <span class="glyphicon glyphicon-warning-sign"></span>
                            La operacion no esta completa para este periodo. Las partes de uso no seran generadas.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @include('conciliacion.partials.operacion-reportada')
        </div>

        <div class="row">
            @include('conciliacion.partials.horometros')
        </div>

        <div class="row">
            @include('conciliacion.partials.horas-a-conciliar')

            @include('conciliacion.partials.distribucion-horas')
        </div>
    </div>
@stop