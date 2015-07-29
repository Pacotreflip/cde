@if ($conciliacion->aprobada)
    <span class="label label-success">Aprobada</span>

    @if ($conciliacion->costo_aplicado)
        <span class="label label-success" data-toggle="tooltip" data-placement="top"
              title="El costo fue aplicado en cadeco"
              aria-hidden="true"><i class="fa fa-usd"></i> Costo</span>
    @else
        <span class="label label-warning" data-toggle="tooltip" data-placement="top"
              title="El costo aun no se aplica en cadeco"
              aria-hidden="true"><i class="fa fa-usd"></i> Costo</span>
    @endif

@else
    <span class="label label-warning">Por Aprobar</span>
@endif