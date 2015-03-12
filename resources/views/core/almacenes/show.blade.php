@extends ('layouts.default')

@section ('content')
    <h2>{{ $almacen->descripcion }}</h2>

    <div class="row">
        <div class="col-sm-3">Tipo Material:</div>
        <div class="col-sm-9">{{ $almacen->material->descripcion }}</div>
    </div>
    <div class="row">
        <div class="col-sm-3">Economico</div>
        <div class="col-sm-9"></div>
    </div>
    <div class="row">
        <div class="col-sm-3">Propiedad</div>
        <div class="col-sm-9"></div>
    </div>
    <div class="row">
        <div class="col-sm-3">Categoria</div>
        <div class="col-sm-9"></div>
    </div>
    <div class="row">
        <div class="col-sm-3">Tipo Combustible</div>
        <div class="col-sm-9"></div>
    </div>
    <div class="row">
{{--        {!! link_to_route('operacion.index', 'Reporte de Operación', [$almacen->id_almacen], ['class' => 'btn btn-sm btn-primary']) !!}--}}
    </div>
@stop