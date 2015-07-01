@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li><a href="{{ route('almacenes.show', [$almacen]) }}">{{ $almacen->descripcion }}</a></li>
        <li class="active">Modificar almacén</li>
    </ol>

    <h1 class="page-header">Modificar Almacén</h1>

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
                <!-- Categoria Form Input -->
                <div class="form-group">
                    {!! Form::label('id_categoria', 'Categoria:') !!}
                    {!! Form::select('id_categoria', $categorias, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <!-- Propiedad Form Input -->
                <div class="form-group">
                    {!! Form::label('id_propiedad', 'Propiedad:') !!}
                    {!! Form::select('id_propiedad', $propiedades, null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
@stop