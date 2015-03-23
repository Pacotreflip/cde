<table class="table table-striped">
    <thead>
    <tr>
        <th>Inicio de Vigencia</th>
        <th>Horas Contrato</th>
        <th>Horas Operación</th>
        <th>Horas Programa</th>
        <th>Observaciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($horas as $hora)
        <tr>
            <td>{{ $hora->inicio_vigencia }}</td>
            <td>{{ $hora->horas_contrato }}</td>
            <td>{{ $hora->horas_operacion }}</td>
            <td>{{ $hora->horas_programa }}</td>
            <td>{{ $hora->observaciones }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
