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
                <td>{{ $periodo->present()->horasEfectivas }}</td>
                <td>{{ $periodo->present()->horasReparacionMayor }}</td>
                <td>{{ $periodo->present()->horasReparacionMenor }}</td>
                <td>{{ $periodo->present()->horasMantenimiento }}</td>
                <td>{{ $periodo->present()->horasOcio }}</td>
                <td>{{ $periodo->present()->totalHoras }}
                </td>
            </tr>
        </tbody>
    </table>
</div>