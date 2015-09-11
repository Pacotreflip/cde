@extends('layout')

@section('content')
    @include('tipos.partials.breadcrumb')

    <h1>Selección de Articulos Requeridos</h1>
    <hr>
    
    @include('partials.search-form')
    <br>
    {!! Form::open(['route' => ['requerimientos.store', $tipo]]) !!}
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Articulo</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulos as $articulo)
                    <tr>
                        <td>
                            <input type="checkbox" name="articulos[]" value="{{ $articulo->id_material }}">
                        </td>
                        <td>
                            <a href="{{ route('articulos.edit', [$articulo]) }}">
                                {{ $articulo->descripcion }}
                            </a>
                        </td>
                        <td>{{ $articulo->unidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="form-group">
            {!! Form::submit('Agregar', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}

    {!! $articulos->render() !!}
@stop