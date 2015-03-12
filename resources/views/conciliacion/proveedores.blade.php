@extends('layouts.default')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Proveedores</h3>
        </div>

        <ul class="list-group">
            @forelse($proveedores as $proveedor)
                {!! link_to_route('conciliacion.equipos', $proveedor->razon_social, [$proveedor->id_empresa], ['class' => 'list-group-item']) !!}
            @empty
                <li class="list-group-item text-danger">No se encontraron proveedores de maquinaria.</li>
            @endforelse
        </ul>
    </div>
@stop