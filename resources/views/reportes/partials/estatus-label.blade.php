@if ($reporte->aprobado)
    <span class="label label-warning">Aprobado</span>
@elseif ($reporte->conciliado)
    <span class="label label-success">Conciliado</span>
@else
    <span class="label label-primary">Capturado</span>
@endif