@extends('layout')

@section('content')
  <h1>Asignaciones de Articulos
    <a href="{{ route('asignaciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Asignar Artículos</a>
  </h1>
  <hr>
  
  @include('partials.search-form')
  
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Folio</th>
        <th>Asignado</th>
        <th>Asignó</th>
        <th>Observaciones</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($asignaciones as $asignacion)
        <tr>
          <td><a href="{{ route('asignaciones.show', $asignacion) }}"># {{ $asignacion->numero_folio }}</a></td>
          <td>
            {{ $asignacion->fecha_asignacion->format('d-m-Y H:m') }}
            <small class="text-muted">({{ $asignacion->created_at->diffForHumans() }})</small>
          </td>
          <td>{{ $asignacion->usuario_registro->present()->nombreCompleto }}</td>
          <td>{{ str_limit($asignacion->observaciones, 70) }}</td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {!! $asignaciones->render() !!}
@stop