@extends('layout')

@section('content')
  <h1>Nuevo Proveedor</h1><hr>

  {!! Form::open(['route' => 'proveedores.store']) !!}
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
      {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    </div>
  {!! Form::close() !!}

  @include('partials.errors')
@stop