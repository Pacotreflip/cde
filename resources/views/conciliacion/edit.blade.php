@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li>{!! link_to_route('conciliacion.almacenes', $empresa->razon_social, [$empresa]) !!}</li>
        <li>{!! link_to_route('conciliacion.index', $almacen->descripcion, [$empresa, $almacen]) !!}</li>
        <li class="active">{{ $conciliacion->present()->periodo }}</li>
    </ol>

    <div class="col-sm-12">
        <h1 class="page-header"><i class="fa fa-calculator"></i> Conciliación</h1>

        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="text-center"><b>{{ $empresa->razon_social }}</b></h3>
                            <h4 class="text-center"><b>{{ $almacen->descripcion }}</b></h4>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4><b>Periodo:</b> {{ $conciliacion->present()->periodo }}</h4>
                        </div>
                        <div class="col-sm-3">
                            <h4><b>Dias del periodo:</b> {{ $conciliacion->present()->dias_conciliados }}</h4>
                        </div>
                        <div class="col-sm-3">
                            <h4><b>Dias con actividad:</b> {{ $conciliacion->present()->dias_con_operacion }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @include('conciliacion.partials.operacion-reportada')
        </div>

        <div class="row">
            @include('partials.errors')
            @include('conciliacion.partials.horas-a-conciliar')

            @unless ($conciliacion->cerrada)
                <hr>
                {!! Form::open(['route' => ['conciliacion.delete', $empresa, $almacen, $conciliacion], 'method' => 'DELETE']) !!}
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            <i class="fa fa-fw fa-times"></i>Eliminar esta conciliación
                        </button>
                    </div>
                {!! Form::close() !!}
            @endunless
        </div>
    </div>
@stop
