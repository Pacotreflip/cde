<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Turno</th>
            <th><i class="fa fa-clock-o"></i> Inicio</th>
            <th><i class="fa fa-clock-o"></i> Término</th>
            <th>Cantidad</th>
            <th>Actividad</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($reporte->actividades as $actividad)
            <tr>
                <td>
                    {{ $actividad->tipo_hora }}
                    @if($actividad->con_cargo_empresa)
                        <i class="fa fa-fw fa-money" data-toggle="tooltip" data-placement="top"
                           title="Estas horas se pagan al proveedor" aria-hidden="true"></i>
                    @endif
                </td>
                <td class="text-center">{{ $actividad->turno }}</td>
                <td>{{ $actividad->present()->horaInicial }}</td>
                <td>{{ $actividad->present()->horaFinal }}</td>
                <td class="decimal">{{ $actividad->cantidad }}</td>
                <td>
                    @if ($actividad->destino)
                        <span data-toggle="tooltip" data-placement="top"
                              title="{{ $actividad->destino->present()->descripcion }}" aria-hidden="true">
                            {{ str_limit($actividad->destino->present()->descripcion, 40) }}
                        </span>
                    @endif
                </td>
                <td>
                    <span data-toggle="tooltip" data-placement="top"
                          title="{{ $actividad->observaciones }}" aria-hidden="true">
                        {{ str_limit($actividad->observaciones, 20) }}
                    </span>
                </td>
                <td class="text-center">
                    @unless($reporte->aprobado)
                        {!! Form::open(['route' => ['actividades.delete', $almacen, $reporte, $actividad], 'method' => 'DELETE']) !!}
                            <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip"
                                    data-placement="top" title="Eliminar esta actividad" aria-hidden="true">
                                <i class="fa fa-times"></i>
                            </button>
                        {!! Form::close() !!}
                    @endunless
                </td>
            </tr>
        @endforeach
    </tbody>
</table>