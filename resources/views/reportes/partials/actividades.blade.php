<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Actividad</th>
            <th>Con cargo</th>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actividades as $actividad)
            <tr>
                <td>{{ $actividad->tipoHora->descripcion }}</td>
                <td>{{ $actividad->cantidad }}</td>
                <td>
                    @if ($actividad->concepto)
                        {{ $actividad->concepto->present()->descripcionConClave }}
                    @endif
                </td>
                <td class="text-center">
                    @if ($actividad->con_cargo)
                        <div class="form-group">
                            <span class="glyphicon glyphicon-ok"></span>
                        </div>
                    @else
                        <span class="glyphicon glyphicon-remove"></span>
                    @endif
                </td>
                <td>{{ $actividad->observaciones }}</td>
                {{--<td class="text-center">--}}
                    {{--@if ( ! $actividad->reporte->cerrado)--}}
                        {{--{!! Form::open(['route' =>--}}
                            {{--['horas.delete', $actividad->reporte->equipo->id_almacen, $actividad->reporte->present()->fechaFormato, $actividad->id],--}}
                            {{--'method' => 'DELETE']) !!}--}}

                            {{--{!! Form::submit('borrar',['class' => 'btn btn-xs btn-danger']) !!}--}}
                        {{--{!! Form::close() !!}--}}
                    {{--@endif--}}
                {{--</td>--}}
            </tr>
        @endforeach
    </tbody>
</table>