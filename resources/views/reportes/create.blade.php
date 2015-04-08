@extends('app')

@section('nav-sub')
    @include('partials.nav-sub', ['almacen' => $almacen])
@endsection

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen->id_almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li><a href="{{ route('reportes.index', [$almacen->id_almacen]) }}">Reportes de actividad</a></li>
        <li class="active">Inicio de actividades</li>
    </ol>

    <h1 class="page-header">Inicio de Actividades</h1>

    @include('partials.errors')

    {!! Form::open(['route' => ['reportes.store', $almacen->id_almacen]] ) !!}

        <div class="row">
            <div class="col-sm-6">
                <!-- Fecha Form Input -->
                <div class="form-group">
                    {!! Form::label('fecha', 'Fecha:') !!}
                    <div class="input-group date">
                        {!! Form::input('date','fecha', date('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'dd-mm-aaaa', 'required'])!!}
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- Horometro Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('horometro_inicial', 'Horometro Inicial:') !!}
                    {!! Form::text('horometro_inicial', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-sm-6">
                <!-- Kilometraje Inicial Form Input -->
                <div class="form-group">
                    {!! Form::label('kilometraje_inicial', 'Kilometraje Inicial:') !!}
                    {!! Form::text('kilometraje_inicial', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <!-- Operador Form Input -->
        <div class="form-group">
            {!! Form::label('operador', 'Operador:') !!}
            {!! Form::text('operador', null, ['class' => 'form-control', 'placeholder' => 'Nombre del operador']) !!}
        </div>

         <!-- Observaciones Form Input -->
         <div class="form-group">
             {!! Form::label('observaciones', 'Observaciones:') !!}
             {!! Form::textarea('observaciones', null, ['class' => 'form-control', 'rows' => 3]) !!}
         </div>

        <div class="form-group">
            {!! link_to_route('reportes.index', 'Cancelar', [$almacen->id_almacen], ['class' => 'btn btn-danger']) !!}
            {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
        </div>
    {!! Form::close() !!}
@endsection

@section('scripts')
    <script>
    $('.dp').datepicker({
        format: "dd-mm-yyyy",
        language: "es",
        autoclose: true
    });
    </script>
@endsection
