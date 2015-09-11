@extends('layout')

@section('content')
    @include('tipos.partials.breadcrumb')

    <h1>Asignación de Requerimientos</h1>
    <hr>

    <div class="row">
        <div class="col-md-2">
            @include('tipos.nav')
        </div>

        <div class="col-md-10">
            <h3>Articulos Requeridos
                <a href="{{ route('requerimientos.edit', [$tipo]) }}" class="btn btn-default pull-right"><i class="fa fa-pencil"></i> Modificar</a>
            </h3>
            <hr>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Articulo</th>
                        <th>Unidad</th>
                        <th>Cantidad</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipo->materiales as $material)
                        <tr>
                            <td>{{ $material->descripcion }}</td>
                            <td>{{ $material->unidad }}</td>
                            <td>{{ $material->pivot->cantidad }}</td>
                            <td>
                                {!! Form::open(['route' => ['requerimientos.delete', $tipo, $material->id_material], 'method' => 'DELETE']) !!}
                                    <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip"
                                            data-placement="top" title="Borrar este articulo" aria-hidden="true">
                                        <i class="fa fa-times"></i>
                                    </button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @unless($tipo->materiales)
                <p class="alert alert-info">
                    Ningun articulo se ha definido para este tipo de area.
                    Para definirlos, haz una <a href="{{ route('requerimientos.seleccion', [$tipo]) }}" class="alert-link"><strong>selección</strong></a>.
                </p>
            @endunless
        </div>
    </div>    
@stop