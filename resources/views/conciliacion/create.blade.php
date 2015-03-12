@extends('layouts.default')

@section('content')
    <div class="page-header">
        <h2>Conciliar Periodo de Operación</h2>
    </div>

    @include('layouts.partials.errors')

    {!! Form::open() !!}
        <div class="row">
            <div class="col-sm-6">
                <!-- Fecha Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha_inicial', 'Fecha Inicial:') !!}
                    {!! Form::text('fecha_inicial', null, ['class' => 'form-control', 'placeholder' => 'dd-mm-aaaa']) !!}
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Fecha Final Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha_final', 'Fecha Final:') !!}
                    {!! Form::text('fecha_final', null, ['class' => 'form-control', 'placeholder' => 'dd-mm-aaaa']) !!}
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