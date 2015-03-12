@extends('layouts.default')

@section('content')
    <ol class="breadcrumb">
        <li>{!! link_to_route('conciliacion.equipos', $periodo->proveedor->razon_social, [$periodo->id_empresa]) !!}</li>
        <li>{!! link_to_route('conciliacion.index', $periodo->equipo->descripcion, [$periodo->id_empresa, $periodo->id_almacen]) !!}</li>
        <li class="active">{{ $periodo->present()->periodo }}</li>
    </ol>

    <div class="col-sm-12">

        <div class="page-header">
            <h2>Periodo de Conciliación {!! $periodo->present()->statusLabel !!}</h2>
        </div>

        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-5">
                            <p>Proveedor: <strong>{{ $periodo->proveedor->razon_social }}</strong></p>
                        </div>
                        <div class="col-sm-7">
                            Periodo:
                            <strong>{{ $periodo->present()->periodo }}</strong>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>Dias del periodo: {{ $periodo->present()->diasConciliacion }}</h5>
                                </div>
                                <div class="col-sm-6">
                                    <h5>Dias con operación: {{ $periodo->present()->diasConOperacion }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <p>Equipo en conciliación: <strong>{{ $periodo->equipo->descripcion }}</strong></p>
                    <p>Horas del contrato vigente en periodo: <strong>{{ $periodo->present()->horasContrato }}</strong></p>
                    @if ( ! $periodo->operacionEstaCompleta())
                        <div class="alert alert-warning">
                            <span class="glyphicon glyphicon-warning-sign"></span>
                            La operacion no esta completa para este periodo. Las partes de uso no seran generadas.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @include('maquinaria.conciliacion.partials.operacion-reportada', ['periodo' => $periodo])
        </div>

        <div class="row">
            @include('maquinaria.conciliacion.partials.horometros', ['periodo' => $periodo])
        </div>

        <div class="row">
            @include('maquinaria.conciliacion.partials.horas-a-conciliar', ['periodo' => $periodo])

            @include('maquinaria.conciliacion.partials.distribucion-horas', ['periodo' => $periodo])
        </div>
    </div>
@stop