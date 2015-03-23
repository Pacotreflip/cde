@extends('app')

@section('nav-sub')
    @include('partials.nav-sub', ['almacen' => $almacen])
@endsection

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen->id_almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li class="active">Registro de horas mensuales</li>
    </ol>

    <h1>Registro de Horas Mensuales</h1>

    <br/>

    @include('partials.errors')

    {!! Form::open(['route' => ['horas-mensuales.store', $almacen->id_almacen], 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        <!-- Inicio Vigencia Form Input -->
        <div class="form-group">
            {!! Form::label('inicio_vigencia', 'Inicio Vigencia:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::input('date', 'inicio_vigencia', date('Y-m-d'), ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Horas Contrato Form Input -->
        <div class="form-group">
            {!! Form::label('horas_contrato', 'Horas Contrato:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::text('horas_contrato', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Horas Operacion Form Input -->
        <div class="form-group">
            {!! Form::label('horas_operacion', 'Horas Operacion:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::text('horas_operacion', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Horas Programa Form Input -->
        <div class="form-group">
            {!! Form::label('horas_programa', 'Horas Programa:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::text('horas_programa', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Horas Programa Form Input -->
        <div class="form-group">
            {!! Form::label('observaciones', 'Observaciones:', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-4">
                {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
@endsection