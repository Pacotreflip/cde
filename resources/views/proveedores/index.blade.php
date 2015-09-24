@extends('layout')

@section('content')
  <h1>Proveedores
    <a href="{{ route('proveedores.create') }}" class="btn btn-success pull-right">
      <i class="fa fa-plus"></i> Nuevo Proveedor</a>
  </h1>
  <hr>
  
  @include('partials.search-form')
  <table class="table table-striped table">
    <thead>
      <tr>
        <th>Razon Social</th>
        <th>RFC</th>
        <th>Tipo</th>
      </tr>
    </thead>
    <tbody>
      @foreach($proveedores as $proveedor)
        <tr>
          <td>
              <a href="{{ route('proveedores.edit', [$proveedor]) }}">{{ $proveedor->razon_social }}</a>
          </td>
          <td>{{ $proveedor->rfc }}</td>
          <td>{{ $proveedor->tipo_empresa->getDescripcion() }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {!! $proveedores->appends('buscar', Request::get('buscar'))->render() !!}
@stop