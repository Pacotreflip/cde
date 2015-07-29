<div class="row">
    <div class="col-xs-4">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <h3 class="panel-title">EFECTIVAS</h3>
            </div>
            <h4 class="text-center">{{ $conciliacion->horas_efectivas }}</h4>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <h3 class="panel-title">R.MAYOR</h3>
            </div>
            <h4 class="text-center">{{ $conciliacion->horas_reparacion_mayor }}</h4>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <h3 class="panel-title">R.MENOR</h3>
            </div>
            <h4 class="text-center">{{ $conciliacion->horas_reparacion_menor }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-4">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <h3 class="panel-title">MANTTO.</h3>
            </div>
            <h4 class="text-center">{{ $conciliacion->horas_mantenimiento }}</h4>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <h3 class="panel-title">OCIO</h3>
            </div>
            <h4 class="text-center">{{ $conciliacion->horas_ocio }}</h4>
        </div>
    </div>

    <div class="col-xs-4">
        <div class="panel panel-info">
            <div class="panel-heading text-center">
                <h3 class="panel-title">TRASLADO</h3>
            </div>
            <h4 class="text-center">{{ $conciliacion->horas_traslado }}</h4>
        </div>
    </div>
</div>