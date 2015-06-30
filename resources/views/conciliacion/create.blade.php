@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('conciliacion.proveedores') }}">Proveedores</a></li>
        <li><a href="{{ route('conciliacion.almacenes', [$empresa->id_empresa]) }}">{{ $empresa->razon_social }}</a></li>
        <li class="active">{{ $almacen->descripcion }}</li>
    </ol>

    <div class="page-header">
        <h2>Nueva Conciliación</h2>
    </div>

    @include('partials.errors')

    {!! Form::open() !!}
        <div class="row">
            <div class="col-sm-6">
                <!-- Fecha Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha_inicial', 'Fecha Inicial:') !!}
                    {!! Form::input('date', 'fecha_inicial', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'dd-mm-aaaa']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Fecha Final Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha_final', 'Fecha Final:') !!}
                    {!! Form::input('date', 'fecha_final', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'dd-mm-aaaa']) !!}
                </div>
            </div>
        </div>

        <!-- Observaciones Form Input -->
        <div class="form-group">
            {!! Form::label('observaciones', 'Observaciones:') !!}
            {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
        
        <div class="form-group">
            {!! Form::submit('Conciliar', ['class' => 'btn btn-success']) !!}
        </div>
    {!! Form::close() !!}
@stop