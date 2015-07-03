<table class="table table-striped">
    <thead>
        <tr>
            <th>Inicio de Vigencia</th>
            <th>Horas Contrato</th>
            <th>Horas Operación</th>
            <th>Horas Programa</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($horas as $hora)
            <tr>
                <td>
                    <a href="{{ route('horas-mensuales.edit', [$almacen, $hora]) }}">{{ $hora->present()->inicio_vigencia_local }}</a>
                </td>
                <td>{{ $hora->horas_contrato }}</td>
                <td>{{ $hora->horas_operacion }}</td>
                <td>{{ $hora->horas_programa }}</td>
                <td>{{ $hora->observaciones }}</td>
                <td>
                    {!! Form::open(['route' => ['horas-mensuales.delete', $almacen, $hora], 'method' => 'DELETE']) !!}
                        <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip"
                                data-placement="top" title="Eliminar" aria-hidden="true">
                            <i class="fa fa-times"></i>
                        </button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
