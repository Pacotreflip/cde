<div class="panel panel-default" id="horas-mensuales">
    <div class="panel-heading">
        <a href="{{ route('horas-mensuales.create', [$almacen]) }}" class="btn btn-sm btn-success pull-right">
            <i class="fa fa-fw fa-plus"></i> Nuevo Registro
        </a>
        <h4>Horas Mensuales
            <small><span class="glyphicon glyphicon-question-sign" data-toggle="tooltip"
                         data-placement="right" title="Horas mensuales de los contratos"
                         aria-hidden="true"></span></small>
        </h4>
    </div>
    @if(count($almacen->horasMensuales))
        @include('horas-mensuales.partials.horas-table')
    @else
        <div class="panel-body">
            <p class="alert alert-warning">
                <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                Este almacén no tiene horas mensuales registradas.
            </p>
        </div>
    @endif
</div>