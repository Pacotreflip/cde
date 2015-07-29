@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li class="active">Modificar almacén</li>
    </ol>

    <h1 class="page-header"><span class="fa fa-pencil"></span> Modificar Almacén</h1>

    @include('partials.errors')

    {!! Form::model($almacen, ['route' => ['almacenes.update', $almacen], 'method' => 'PATCH']) !!}

        <div class="row">
            <div class="col-md-3">
                <!-- Numero Economico Form Input -->
                <div class="form-group">
                    {!! Form::label('numero_economico', 'Numero Economico:') !!}
                    {!! Form::text('numero_economico', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-9">
                <!-- Descripcion Form Input -->
                <div class="form-group">
                    {!! Form::label('descripcion', 'Descripción:') !!}
                    {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <!-- Material Form Input -->
        <div class="form-group">
            {!! Form::label('material', 'Material:') !!}
            {!! Form::select('id_material', $materiales, null, ['class' => 'form-control']) !!}
            {{--{!! Form::text('material', $almacen->material->descripcion, ['class' => 'form-control', 'placeholder' => 'Escriba el nombre del insumo']) !!}--}}
            {{--{!! Form::hidden('id_material', null, ['class' => 'form-control', 'id' => 'id_material']) !!}--}}
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Clasificación Form Input -->
                <div class="form-group">
                    {!! Form::label('clasificacion', 'Clasificación:') !!}
                    {!! Form::select('clasificacion', $clasificaciones, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <!-- Propiedad Form Input -->
                <div class="form-group">
                    {!! Form::label('propiedad', 'Propiedad:') !!}
                    {!! Form::select('propiedad', $propiedades, null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@stop