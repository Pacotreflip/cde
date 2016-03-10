@extends('layout')

@section('content')
  <h1>Cierre de Área
    <a href="{{ route('cierres.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Crear Cierre de Área</a>
  </h1>
  <hr>
  
  @include('partials.search-form')
  
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Cerrado</th>
        <th>Cerró</th>
        <th>Observaciones</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($cierres as $cierre)
        <tr>
          <td><a href="{{ route('cierres.show', $cierre) }}"># {{ $cierre->numero_folio }}</a></td>
          <td>
            {{ $cierre->fecha_cierre->format('d-m-Y H:m') }}
            <small class="text-muted">({{ $cierre->created_at->diffForHumans() }})</small>
          </td>
          <td>{{ $cierre->usuario->present()->nombreCompleto }}</td>
          <td>{{ str_limit($cierre->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {!! $cierres->render() !!}
@stop