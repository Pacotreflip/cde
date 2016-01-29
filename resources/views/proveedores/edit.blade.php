@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
    <li class="active">{{ $proveedor->razon_social }}</li>
  </ol>

  <h1>Proveedor</h1>
  <hr>

  {!! Form::model($proveedor, ['route' => ['proveedores.update', $proveedor], 'method' => 'PATCH']) !!}
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
    
    <hr>
    
    <div class="form-group">
      {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
    </div>
  {!! Form::close() !!}

  @include('partials.errors')
@stop