@extends('app')

@section('content')

    <h1 class="page-header">Proveedores</h1>

    <div class="panel panel-success">
        <ul class="list-group">
            @foreach($proveedores as $proveedor)
                {!! link_to_route('conciliacion.almacenes', $proveedor->razon_social, [$proveedor->id_empresa], ['class' => 'list-group-item']) !!}
            @endforeach
        </ul>
    </div>
@endsection
