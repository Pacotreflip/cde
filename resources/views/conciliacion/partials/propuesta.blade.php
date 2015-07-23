<h3 class="page-header"><span class="fa fa-list-alt"></span> Propuesta de Horas a Pagar (costo)</h3>

<div class="row">
    <div class="col-sm-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>A Pagar por Contrato</th>
                    <td class="text-right">{{ $conciliacion->horas_contrato }}</td>
                </tr>
                <tr>
                    <th>A Conciliar del Periodo ({{ $conciliacion->present()->dias_conciliados }})</th>
                    <td class="text-right">{{ $conciliacion->horas_a_conciliar }}</td>
                </tr>
                <tr>
                    <th>Reparación Mayor Con Cargo a Empresa</th>
                    <td class="text-right">{{ $conciliacion->horas_reparacion_mayor_con_cargo }}</td>
                </tr>
                <tr>
                    <th>Reparación Mayor <span class="pull-right text-danger">(-)</span></th>
                    <td class="text-right">{{ $conciliacion->horas_reparacion_mayor }}</td>
                </tr>
                <tr class="active">
                    <th>Diferencia Base Pagable</th>
                    <th class="text-right">{{ $conciliacion->horas_a_conciliar - $conciliacion->horas_reparacion_mayor }}</th>
                </tr>
                <tr>
                    <th>Efectivas <span class="pull-right text-success">(+)</span></th>
                    <td class="text-right">{{ $conciliacion->horas_efectivas_conciliadas }}</td>
                </tr>
                <tr>
                    <th>Ocio <span class="pull-right text-success">(+)</span></th>
                    <td class="text-right">{{ $conciliacion->horas_ocio_conciliadas }}</td>
                </tr>
                <tr class="success">
                    <th class="text-right"><b>Total de Horas a Pagar:</b></th>
                    <th class="text-right">
                        <b>
                            {{
                                $conciliacion->horas_efectivas_conciliadas +
                                $conciliacion->horas_ocio_conciliadas +
                                $conciliacion->horas_reparacion_conciliadas
                            }}
                        </b>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-sm-6">
        {!! Form::model($conciliacion, ['route' => ['conciliacion.update', $empresa, $almacen, $id],
            'method' => 'PATCH']) !!}
            <div class="row">
                <div class="col-xs-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h3 class="panel-title">EFECTIVAS</h3>
                        </div>

                        @unless ($conciliacion->aprobada)
                            <!-- Horas Efectivas Conciliadas Form Input -->
                            {!! Form::text('horas_efectivas_conciliadas', null, ['class' => 'form-control input-lg integer']) !!}
                        @else
                            <div class="panel-body text-center">
                                <h3 class="panel-title">{{ $conciliacion->horas_efectivas_conciliadas }}</h3>
                            </div>
                        @endunless
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h3 class="panel-title">OCIO</h3>
                        </div>

                        @unless ($conciliacion->aprobada)
                            <!-- Horas Ocio Conciliadas Form Input -->
                            {!! Form::text('horas_ocio_conciliadas', null, ['class' => 'form-control input-lg integer']) !!}
                        @else
                            <div class="panel-body text-center">
                                <h3 class="panel-title">{{ $conciliacion->horas_ocio_conciliadas }}</h3>
                            </div>
                        @endunless
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h3 class="panel-title">REPARACIÓN</h3>
                        </div>

                        @unless ($conciliacion->aprobada)
                            <!-- Horas Ocio Conciliadas Form Input -->
                            {!! Form::text('horas_reparacion_conciliadas', null, ['class' => 'form-control input-lg integer']) !!}
                        @else
                            <div class="panel-body text-center">
                                <h3 class="panel-title">{{ $conciliacion->horas_reparacion_conciliadas }}</h3>
                            </div>
                        @endunless
                    </div>
                </div>
            </div>
            <div class="row">
                @unless ($conciliacion->aprobada)
                    <div class="form-group text-center">
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">
                                <span class="fa fa-fw fa-check"></span> Aprobar conciliación
                            </button>
                        </div>
                        {!! Form::hidden('aprobar', true) !!}
                    </div>
                @endunless
            </div>

            @if ($conciliacion->aprobada)
                @unless ($conciliacion->costo_aplicado)
                    <div class="alert alert-warning">
                        <span class="h3"><span class="fa fa-fw fa-refresh fa-spin"></span> Aplicando costo en cadeco.</span>
                    </div>
                @else
                    <div class="alert alert-success">
                        <span class="h3"><span class="fa fa-fw fa-check"></span> El costo fue aplicado.</span>
                    </div>
                @endunless
            @endif
        {!! Form::close() !!}
    </div>
</div>
