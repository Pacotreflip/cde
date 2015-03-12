<div class="panel panel-{{ $periodo->cerrado ? 'success' : 'primary' }}">
    <div class="panel-heading">
        Distribución de Horas
    </div>

    @if($periodo->cerrado)
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
                    <td>{{ $periodo->horas_conciliadas_efectivas }}</td>
                    <td>{{ $periodo->horas_conciliadas_reparacion_mayor }}</td>
                    <td>{{ $periodo->horas_conciliadas_ocio }}</td>
                </tr>
            </tbody>
        </table>
    @else
        {!! Form::open(['route' => ['conciliacion.update', $periodo->id_empresa, $periodo->id_almacen, $periodo->id],
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
                        <td>{!! Form::text('horas_efectivas', $periodo->horas_efectivas, ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::text('horas_reparacion_mayor', $periodo->horasReparacionMayorPropuesta(), ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::text('horas_ocio', $periodo->horasOcioPropuesta(), ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::submit('Cerrar', ['class' => 'btn btn-success']) !!}</td>
                    </tr>
                </tbody>
            </table>

        {!! Form::close() !!}
    @endif
</div>