@extends('layout')

@section('content')
    <h1>Articulos
        <a href="{{ route('articulos.create') }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Agregar Articulo</a>
    </h1>
    <hr>

    @include('partials.search-form')
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>No. Parte</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Clasificación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materiales as $material)
                <tr>
                    <th>{{ $material->numero_parte }}</th>
                    <td>
                        <a href="{{ route('articulos.edit', [$material]) }}">{{ str_limit($material->descripcion, 70) }}</a>
                    </td>
                    <td>{{ $material->unidad }}</td>
                    <td>
                        @if($material->clasificador)
                            {{ $material->clasificador->nombre }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {!! $materiales->appends(['buscar' => Request::get('buscar')])->render() !!}
@stop