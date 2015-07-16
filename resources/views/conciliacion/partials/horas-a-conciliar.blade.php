<h3 class="page-header"><i class="fa fa-list-alt"></i> Propuesta de Horas a Pagar (costo)</h3>

<div class="row">
    <div class="col-sm-6">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Horas A Pagar por Contrato</th>
                    <td class="text-right">{{ $conciliacion->horas_contrato }}</td>
                </tr>
                <tr>
                    <th>Horas A Conciliar del Periodo ({{ $conciliacion->present()->dias_conciliados }})</th>
                    <td class="text-right">{{ $conciliacion->horas_a_conciliar }}</td>
                </tr>
                <tr>
                    <th>Horas Reparación Mayor</th>
                    <td class="text-right">{{ $conciliacion->horas_reparacion_mayor }}</td>
                </tr>
                <tr>
                    <th>Horas Reparación Mayor Con Cargo a Empresa</th>
                    <td class="text-right">0</td>
                </tr>
                <tr>
                    <th>Horas Pagables</th>
                    <th class="text-right">{{ $conciliacion->horas_pagables }}</th>
                </tr>
                <tr class="active">
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
        {!! Form::model($conciliacion, ['route' => ['conciliacion.update', $empresa, $almacen, $conciliacion],
            'method' => 'PATCH']) !!}
            <div class="row">
                <div class="col-xs-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h3 class="panel-title">EFECTIVAS</h3>
                        </div>

                        @unless ($conciliacion->cerrada)
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

                        @unless ($conciliacion->cerrada)
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

                        @unless ($conciliacion->cerrada)
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
                @unless ($conciliacion->cerrada)
                    <div class="form-group text-center">
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">
                                <i class="fa fa-fw fa-check"></i>Cerrar conciliación
                            </button>
                        </div>
                        {!! Form::hidden('cerrar', true) !!}
                    </div>
                @endunless
            </div>
        {!! Form::close() !!}
    </div>
</div>
