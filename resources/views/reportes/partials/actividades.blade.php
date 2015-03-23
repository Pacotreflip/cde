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
                        <div class="form-group">
                            <span class="glyphicon glyphicon-ok"></span>
                        </div>
                    @else
                        <span class="glyphicon glyphicon-remove"></span>
                    @endif
                </td>
                <td>{{ $actividad->present()->horaInicial }}</td>
                <td>{{ $actividad->present()->horaFinal }}</td>
                <td>{{ $actividad->cantidad }}</td>
                <td>
                    @if ($actividad->destino)
                        {{ $actividad->destino->present()->descripcion }}
                    @endif
                </td>
                <td>{{ $actividad->observaciones }}</td>
                <td class="text-center">
                    @unless($reporte->cerrado)
                        {!! Form::open(['route' => ['actividades.delete', $reporte->almacen->id_almacen, $reporte->id, $actividad->id],
                            'method' => 'DELETE']) !!}
                            {!! Form::submit('borrar',['class' => 'btn btn-xs btn-danger']) !!}
                        {!! Form::close() !!}
                    @endunless
                </td>
            </tr>
        @endforeach
    </tbody>
</table>