@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
    <li class="active">{{ $proveedor->razon_social }}</li>
  </ol>

  <h1>Proveedor</h1>
  <hr>

  {!! Form::model($proveedor, ['route' => ['proveedores.update', $proveedor], 'method' => 'PATCH']) !!}
    <!-- Razon Social Form Input -->
    <div class="form-group">
      {!! Form::label('razon_social', 'Razon Social:') !!}
      {!! Form::text('razon_social', null, ['class' => 'form-control', 'required']) !!}
    </div>
    
    <div class="row">
      <div class="col-xs-6">
        <!-- Rfc Form Input -->
        <div class="form-group">
          {!! Form::label('rfc', 'Rfc:') !!}
          {!! Form::text('rfc', null, ['class' => 'form-control']) !!}
        </div>
      </div>
      <div class="col-xs-6">
        <!-- Tipo Form Input -->
        <div class="form-group">
          {!! Form::label('tipo_empresa', 'Tipo:') !!}
          {!! Form::select('tipo_empresa', $tipos, null, ['class' => 'form-control']) !!}
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