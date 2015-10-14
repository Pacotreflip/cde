@extends('layout')

@section('content')
  <h1>Transferencias
    <a href="{{ route('transferencias.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nueva Transferencia</a>
  </h1>
  <hr>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>No. Folio</th>
        <th>Fecha</th>
        <th>Origen</th>
        <th>Observaciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($transferencias as $transferencia)
        <tr>
          <td>
            <a href="{{ route('transferencias.show', $transferencia) }}">#{{ $transferencia->numero_folio }}</a>
          </td>
          <td>{{ $transferencia->fecha->format('d-M-Y h:m') }} <small class="text-muted">({{ $transferencia->created_at->diffForHumans() }})</small></td>
          <td>{{ $transferencia->area->nombre }}</td>
          <td>{{ $transferencia->observaciones }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop