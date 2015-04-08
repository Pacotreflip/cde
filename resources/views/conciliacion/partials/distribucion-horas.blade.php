<div class="panel panel-{{ $conciliacion->cerrado ? 'success' : 'primary' }}">
    <div class="panel-heading">
        Distribución de Horas
    </div>

    @if($conciliacion->cerrado)
        <table class="table">
            <thead>
                <tr>
                    <th>Efectivas</th>
                    <th>Reparación Mayor</th>
                    <th>Ocio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $conciliacion->horas_conciliadas_efectivas }}</td>
                    <td>{{ $conciliacion->horas_conciliadas_reparacion_mayor }}</td>
                    <td>{{ $conciliacion->horas_conciliadas_ocio }}</td>
                </tr>
            </tbody>
        </table>
    @else
        {!! Form::open(['route' => ['conciliacion.update', $conciliacion->id_empresa, $conciliacion->id_almacen, $conciliacion->id],
                                    'method' => 'PUT', 'class' => 'form-inline']) !!}
            <table class="table">
                <thead>
                    <tr>
                        <th>Efectivas</th>
                        <th>Reparación Mayor</th>
                        <th>Ocio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{!! Form::text('horas_efectivas', $conciliacion->horas_efectivas, ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::text('horas_reparacion_mayor', $conciliacion->horasReparacionMayorPropuesta(), ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::text('horas_ocio', $conciliacion->horasOcioPropuesta(), ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::submit('Cerrar', ['class' => 'btn btn-success']) !!}</td>
                    </tr>
                </tbody>
            </table>

        {!! Form::close() !!}
    @endif
</div>