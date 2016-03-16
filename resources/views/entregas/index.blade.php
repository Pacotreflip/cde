@extends('layout')

@section('content')
  <h1>Entrega de Área
    <a href="{{ route('entregas.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Crear Entrega de Área</a>
  </h1>
  <hr>
  
  @include('partials.search-form')
  
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Entregado</th>
        <th>Entregó</th>
        <th>Recibió</th>
        <th>Generó Entrega</th>
        <th>Observaciones</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($entregas as $entrega)
        <tr>
          <td><a href="{{ route('entregas.show', $entrega) }}"># {{ $entrega->numero_folio }}</a></td>
          <td>
            {{ $entrega->fecha_entrega->format('d-m-Y H:m') }}
            <small class="text-muted">({{ $entrega->created_at->diffForHumans() }})</small>
          </td>
          <td>{{ $entrega->entrega }}</td>
          <td>{{ $entrega->recibe }}</td>
          <td>{{ $entrega->usuario->present()->nombreCompleto }}</td>
          <td>{{ str_limit($entrega->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {!! $entregas->render() !!}
@stop