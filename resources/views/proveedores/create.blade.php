@extends('layout')

@section('content')
<h1>Nuevo Proveedor</h1><hr>

{!! Form::open(['route' => 'proveedores.store']) !!}
<!-- Razon Social Form Input -->
<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            {!! Form::label('razon_social', 'Razon Social:') !!}
            {!! Form::text('razon_social', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-xs-2">
        <div class="form-group">
            {!! Form::label('nombre_corto', 'Nombre Corto:') !!}
            {!! Form::text('nombre_corto', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-xs-3">
        <!-- Rfc Form Input -->
        <div class="form-group">
            {!! Form::label('rfc', 'RFC:') !!}
            {!! Form::text('rfc', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-3">
        <!-- Tipo Form Input -->
        <div class="form-group">
            {!! Form::label('tipo_empresa', 'Tipo:') !!}
            {!! Form::select('tipo_empresa', $tipos, null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>



<hr>

<div class="row">
    <div class="col-xs-4">
        <!-- Rfc Form Input -->
        <div class="form-group">
            {!! Form::label('nombre_contacto', 'Nombre de Contacto:') !!}
            {!! Form::text('nombre_contacto', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <!-- Rfc Form Input -->
        <div class="form-group">
            {!! Form::label('correo', 'Correo:') !!}
            {!! Form::text('correo', null, ['class' => 'form-control', 'email']) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <!-- Tipo Form Input -->
        <div class="form-group">
            {!! Form::label('telefono', 'Teléfono:') !!}
            {!! Form::text('telefono', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        
        <div class="form-group">
            {!! Form::label('direccion', 'Dirección:') !!}
            {!! Form::textarea('direccion', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<hr>

<div class="form-group">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

@include('partials.errors')
@stop