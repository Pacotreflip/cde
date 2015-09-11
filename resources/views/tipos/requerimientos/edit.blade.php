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
                <a href="{{ route('requerimientos.seleccion', [$tipo]) }}" class="btn btn-success pull-right">
                    <i class="fa fa-plus"></i> Agregar Articulos
                </a>
            </h3>
            <hr>

            {!! Form::open(['route' => ['requerimientos.update', $tipo], 'method' => 'PATCH']) !!}

                <div class="form-inline">
                    <div class="form-group">
                        <select name="action" id="action" class="form-control">
                            <option value="" selected="selected">--------</option>
                            <option value="delete_selected">Borrar seleccionados</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">Aplicar</button>
                </div>
                <br>

                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr class="active">
                            <td>
                                <input type="checkbox" name="select_all" id="select_all" value="1" title="Seleccionar todos"/>
                            </td>
                            <th>Articulo</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tipo->materiales as $material)
                            <tr>
                                <td>{!! Form::checkbox('selected[' . $material->id_material . ']', 1) !!}</td>
                                <td>
                                    <a href="{{ route('articulos.edit', [$material]) }}">
                                        {{ $material->descripcion }}
                                    </a>
                                </td>
                                <td>{{ $material->unidad }}</td>
                                <td>{!! Form::text('articulos[' . $material->id_material . '][cantidad]', $material->pivot->cantidad) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Guardar Cambios">
                </div>
            {!! Form::close() !!}
        </div>
    </div>    
@stop

@section('scripts')
    <script>
        $('#select_all').on('change', function() {
            var checked = $(this).prop('checked');
            $(this).parents('thead').next('tbody').find('input[type=checkbox]').prop('checked', checked);
        });
    </script>
@stop