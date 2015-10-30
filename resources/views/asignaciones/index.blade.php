@extends('layout')

@section('content')
  <h1>Asignaciones de Articulos
    <a href="{{ route('asignaciones.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Asignar Artículos</a>
  </h1>
  <hr>
@stop