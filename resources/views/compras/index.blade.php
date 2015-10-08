@extends('layout')

@section('content')
  <h1>Compras</h1>
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
      @foreach($compras as $compra)
        <tr>
          <td><a href="{{ route('compras.show', $compra) }}"># {{ $compra->numero_folio }}</a></td>
          <td>{{ $compra->fecha->format('d-m-Y') }}</td>
          <td>{{ $compra->empresa->razon_social }}</td>
          <td>{{ str_limit($compra->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $compras->render() !!}
@stop