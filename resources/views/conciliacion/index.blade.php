@extends('layouts.default')

@section('content')
    <ol class="breadcrumb">
        <li>{!! link_to_route('conciliacion.equipos', $proveedor->razon_social, [$proveedor->id_empresa]) !!}</li>
        <li class="active">{{ $equipo->descripcion }}</li>
    </ol>

    <p>
        {!! link_to_route('conciliacion.conciliar', 'Conciliar periodo',
            [$proveedor->id_empresa, $equipo->id_almacen],
            ['class' => 'btn btn-primary'])
        !!}
    </p>

    @include('maquinaria.conciliacion.partials.conciliaciones',[
        'periodos' => $periodos,
        'idProveedor' => $proveedor->id_empresa,
        'idEquipo' => $equipo->id_almacen
    ])
@stop