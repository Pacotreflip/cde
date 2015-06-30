<div class="panel panel-default">
    <div class="panel-heading">
        Operación Reportada
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Efectivas</th>
                <th>Rep. Mayor</th>
                <th>Rep. Menor</th>
                <th>Mantto.</th>
                <th>Ocio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $conciliacion->present()->horas_efectivas }}</td>
                <td>{{ $conciliacion->present()->horas_reparacion_mayor }}</td>
                <td>{{ $conciliacion->present()->horas_reparacion_menor }}</td>
                <td>{{ $conciliacion->present()->horas_mantenimiento }}</td>
                <td>{{ $conciliacion->present()->horas_ocio }}</td>
                <td>{{ $conciliacion->present()->suma_horas }}
                </td>
            </tr>
        </tbody>
    </table>
</div>