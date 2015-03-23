@extends('app')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ route('almacenes.index') }}">Almacenes</a></li>
        <li class="active">Registro de almacén</li>
    </ol>

    <h1 class="page-header">Registro de Almacén</h1>

    @include('partials.errors')

    {!! Form::open(['route' => ['almacenes.store'], 'method' => 'POST']) !!}

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
            {!! Form::label('id_material', 'Material:') !!}
            {!! Form::select('id_material', $materiales, null, ['class' => 'form-control']) !!}
{{--            {!! Form::text('material', null, ['class' => 'form-control', 'placeholder' => 'Digite nombre del insumo']) !!}--}}
{{--            {!! Form::hidden('id_material', null, ['class' => 'form-control', 'id' => 'id_material']) !!}--}}
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
@endsection