@extends('layout')

@section('content')
  <h1>Adquisiciones</h1>
  <hr>

  @include('partials.search-form')

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Observaciones</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($ordenes as $orden)
        <tr>
          <td><a href="{{ route('adquisiciones.show', $orden) }}">{{ $orden->numero_folio }}</a></td>
          <td>{{ $orden->fecha->format('d-m-Y') }}</td>
          <td>{{ $orden->empresa->razon_social }}</td>
          <td>{{ str_limit($orden->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $ordenes->appends(Request::only('buscar'))->render() !!}
@stop