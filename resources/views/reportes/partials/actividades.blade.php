<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Con Cargo</th>
            <th>Hora Inicio</th>
            <th>Hora Término</th>
            <th>Cantidad</th>
            <th>Actividad</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporte->actividades as $actividad)
            <tr>
                <td>{{ $actividad->tipoHora->descripcion }}</td>
                <td class="text-center">
                    @if($actividad->con_cargo)
                        <span class="glyphicon glyphicon-ok"></span>
                    @else
                        <span class="glyphicon glyphicon-remove"></span>
                    @endif
                </td>
                <td>{{ $actividad->present()->horaInicial }}</td>
                <td>{{ $actividad->present()->horaFinal }}</td>
                <td class="decimal">{{ $actividad->cantidad }}</td>
                <td>
                    @if ($actividad->destino)
                        <span data-toggle="tooltip" data-placement="top" title="{{ $actividad->destino->present()->descripcion }}" aria-hidden="true">
                            {{ str_limit($actividad->destino->present()->descripcion, 40) }}
                        </span>
                    @endif
                </td>
                <td>
                    <span data-toggle="tooltip" data-placement="top" title="{{ $actividad->observaciones }}" aria-hidden="true">
                        {{ str_limit($actividad->observaciones, 20) }}
                    </span>
                </td>
                <td class="text-center">
                @unless($reporte->cerrado)
                    {!! Form::open(['route' => ['actividades.delete', $almacen, $reporte, $actividad], 'method' => 'DELETE']) !!}

                        {!! Form::submit('Eliminar', ['class' => 'btn btn-xs btn-danger']) !!}

                    {!! Form::close() !!}
                @endunless
                </td>
            </tr>
        @endforeach
    </tbody>
</table>