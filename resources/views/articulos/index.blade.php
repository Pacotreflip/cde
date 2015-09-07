@extends('layout')

@section('content')
    <h1>Articulos
        <a href="{{ route('articulos.create') }}" class="btn btn-success pull-right">Agregar Articulo</a>
    </h1>
    <hr>

    <div class="search-bar">
        {!! Form::model(Request::only('buscar'), ['route' => 'articulos.index', 'method' => 'GET', 'class' => 'navbar-form navbar-right']) !!}
            <div class="form-group">
                {!! Form::text('buscar', null, ['class' => 'form-control input-sm', 'placeholder' => 'escriba el texto a buscar...']) !!}
            </div>
            {!! Form::submit('Buscar', ['class' => 'btn btn-sm btn-primary']) !!}
        {!! Form::close() !!}
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No. Parte</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Clasificación</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiales as $material)
                <tr>
                    <th>{{ $material->numero_parte }}</th>
                    <td>{{ str_limit($material->descripcion, 80) }}</td>
                    <td>{{ $material->unidad }}</td>
                    <td></td>
                    <td>
                        <p data-placement="top" data-toggle="tooltip" title="Modificar">
                            <a href="{{ route('articulos.edit', [$material]) }}" class="btn btn-primary btn-xs">
                                <span class="fa fa-pencil"></span>
                            </a>
                        </p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $materiales->appends(['buscar' => Request::get('buscar')])->render() !!}
@stop