@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('tipos.index', Request::has('dentro_de') ? 
        ['tipo' => Request::get('dentro_de')] : []) }}">Áreas Tipo</a>
    </li>
    <li class="active">Nueva Área Tipo</li>
  </ol>

  <div class="row">
      <div class="col-md-6">
          <h1>Nueva Área Tipo</h1>
          <hr>

          {!! Form::open(['route' => ['tipos.store'], 'method' => 'POST']) !!}
              @include('areas-tipo.partials.fields')
              <hr>

              <div class="form-group">
                  {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
              </div>

              @include('partials.errors')

          {!! Form::close() !!}
      </div>
  </div>
@stop