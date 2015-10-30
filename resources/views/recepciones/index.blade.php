@extends('layout')

@section('content')
  <h1>Recepciones
    <a href="{{ route('recepciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Recibir Artículos</a>
  </h1>
  <hr>

  @include('partials.search-form')

  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Folio O/C</th>
        <th>Recibido</th>
        <th>Proveedor</th>
        <th>Recibió</th>
        <th>Observaciones</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($recepciones as $recepcion)
        <tr>
          <td><a href="{{ route('recepciones.show', $recepcion) }}"># {{ $recepcion->numero_folio }}</a></td>
          <td><a href="{{ route('compras.show', $recepcion->compra) }}"># {{ $recepcion->compra->numero_folio }}</a></td>
          <td>
            {{ $recepcion->fecha_recepcion->format('d-m-Y H:m') }}
            <small class="text-muted">({{ $recepcion->created_at->diffForHumans() }})</small>
          </td>
          <td>
            <a href="{{ route('proveedores.edit', $recepcion->empresa ) }}">{{ $recepcion->empresa->razon_social }}</a>
          </td>
          <td>{{ $recepcion->persona_recibe }}</td>
          <td>{{ str_limit($recepcion->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $recepciones->render() !!}
@stop