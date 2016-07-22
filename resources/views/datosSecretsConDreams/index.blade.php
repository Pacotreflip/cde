@extends('layout')

@section('content')
<h1>Datos Secrets Con Dreams
  <a href="{{ route('datosSecretsConDreams.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Agregar Datos</a>
</h1>

<hr>

<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>descripcion_producto_oc</th>
        <th>familia</th>
        <th>area_secrets</th>
        <th>consolidado_dolares</th>
        <th>familia_dreams</th>
        <th>area_dreams</th>
        <th>presupuesto</th>
        <th>consolidacion_dolares_dreams</th>
        <th>clasificacion</th>
      </tr>    
    </thead>
    <tbody>
      @foreach($datosSecretsConDreams as $dato)
      <tr>
        <td>{{ $dato->descripcion_producto_oc }}</td>
        <td>{{ $dato->familia }}</td>
        <td>{{ $dato->area_secrets }}</td>
        <td>{{ $dato->consolidado_dolares }}</td>
        <td>{{ $dato->familia_dreams }}</td>
        <td>{{ $dato->area_dreams }}</td>
        <td>{{ $dato->presupuesto }}</td>
        <td>{{ $dato->consolidacion_dolares_dreams }}</td>
        <td>{{ $dato->clasificacion }}</td>
      </tr>  
      @endforeach
    </tbody>
  </table>
  <div class="text-center">
    {!! $datosSecretsConDreams->render() !!}
  </div>
</div>
@stop