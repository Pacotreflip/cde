<h3 class="page-header">
    <i class="fa fa-fw fa-truck"></i> Equipos Ingresados
    <small><span class="glyphicon glyphicon-question-sign" data-toggle="tooltip"
                 data-placement="right" title="Equipos que han entrado a este almacén."
                 aria-hidden="true"></span></small>
</h3>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Tipo de Material</th>
            <th>Numero de Serie</th>
            <th>Empresa</th>
            <th>Fecha Entrada</th>
            <th>Fecha Salida</th>
        </tr>
    </thead>
    <tbody>
        @foreach($almacen->equipos as $equipo)
            <tr>
                <td>{{ $equipo->material->descripcion}}</td>
                <td>{{ $equipo->referencia }}</td>
                <td>{{ $equipo->item->transaccion->empresa->razon_social }}</td>
                <td>{{ $equipo->present()->fechaEntrada }}</td>
                <td>{{ $equipo->present()->fechaSalida }}</td>
            </tr>
        @endforeach
    </tbody>
</table>