<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Horometros</h4>
    </div>

    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th>Inicial</th>
                <th>Final</th>
                <th>Horas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $conciliacion->present()->horometro_inicial }}</td>
                <td>{{ $conciliacion->present()->horometro_final }}</td>
                <td>{{ $conciliacion->present()->horas_horometro }}</td>
            </tr>
        </tbody>
    </table>
</div>