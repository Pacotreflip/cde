@extends('layout')

@section('content')
  <ol class="breadcrumb">
    <li><a href="{{ route('datosSecretsConDreams.index') }}">Datos Secrets Con Dreams</a></li>
    <li class="active">Ver</li>
  </ol>

  <h1>Ver</h1>
  <hr>
  <div class="table-responsive col-md-6">
      <table class="table table-bordered">
      <thead>
          <tr>
              <th>Atributo</th>
              <th>Valor</th>
          </tr>
      </thead>
      <tbody>
          @foreach($dato->toArray() as $key => $value)
          <tr>
              <td><strong>{{ $key }}</strong></td>
              <td>{{ $value }}</td>
          </tr>
          @endforeach
      </tbody>
  </table>
@stop
  