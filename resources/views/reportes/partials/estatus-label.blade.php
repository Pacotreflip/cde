@if ($reporte->aprobado)
    <span class="label label-warning">{{ $reporte->present()->estatusLabel }}</span>
@elseif ($reporte->conciliado)
    <span class="label label-success">{{ $reporte->present()->estatusLabel }}</span>
@else
    <span class="label label-primary">{{ $reporte->present()->estatusLabel }}</span>
@endif