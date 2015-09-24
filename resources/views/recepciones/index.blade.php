@extends('layout')

@section('content')
  <h1>Recepciones
    <a href="{{ route('recepciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Recibir Articulos</a>
  </h1>
  <hr>

  @include('partials.search-form')

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Folio O/C</th>
        <th>Fecha Entrega</th>
        <th>Proveedor</th>
        <th>Observaciones</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($recepciones as $recepcion)
        <tr>
          <td><a href="{{ route('recepciones.show', $recepcion) }}">{{ $recepcion->numero_folio }}</a></td>
          <td>{{ $recepcion->ordenCompra->numero_folio }}</td>
          <td>{{ $recepcion->fecha_recepcion->format('d-m-Y') }}</td>
          <td>{{ $recepcion->empresa->razon_social }}</td>
          <td>{{ str_limit($recepcion->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $recepciones->render() !!}
@stop