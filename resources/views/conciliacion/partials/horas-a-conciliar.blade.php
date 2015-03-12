<div class="panel panel-info">
    <div class="panel-heading">
        Propuesta de Horas a Conciliar
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Horas a Conciliar</th>
                <th>Horas Propuestas</th>
                <th>Horas Conciliadas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $periodo->horas_a_conciliar }}</td>
                <td>{{ $periodo->horasPropuestas() }}</td>
                <td><strong class="text-info">{{ $periodo->horas_conciliadas }}</strong></td>
            </tr>
        </tbody>
    </table>

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
            <td>{{ $periodo->horas_efectivas }}</td>
            <td>{{ $periodo->horasReparacionMayorPropuesta() }}</td>
            <td>{{ $periodo->horasOcioPropuesta() }}</td>
        </tr>
        </tbody>
    </table>
</div>