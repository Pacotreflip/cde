@if($reporte->cerrado)
    <span class="label label-success">{{ $reporte->present()->estatusLabel }}</span>
@else
    <span class="label label-warning">{{ $reporte->present()->estatusLabel }}</span>
@endif