<div class="panel panel-default">
    <div class="panel-heading">
        Horometros
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Inicial</th>
                <th>Final</th>
                <th>Horas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $periodo->present()->horometroInicial }}</td>
                <td>{{ $periodo->present()->horometroFinal }}</td>
                <td>{{ $periodo->present()->horasHorometro }}</td>
            </tr>
        </tbody>
    </table>
</div>