<!-- Inicio Vigencia Form Input -->
<div class="form-group">
    {!! Form::label('inicio_vigencia', 'Vigente a Partir de:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        <div class="input-group">
            {!! Form::date('inicio_vigencia', $horas ? $horas->present()->inicio_vigencia : date('Y-m-d'),
                ['class' => 'form-control pad', 'placeholder' => 'dd-mm-aaaa', 'required',
                    'data-value' => $horas ? $horas->present()->inicio_vigencia_local : date('d-m-Y')]) !!}
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
    </div>
</div>

<!-- Horas Contrato Form Input -->
<div class="form-group">
    {!! Form::label('horas_contrato', 'Horas de Contrato:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('horas_contrato', null, ['class' => 'form-control integer', 'placeholder' => '0', 'required']) !!}
    </div>
</div>

<!-- Horas Operacion Form Input -->
<div class="form-group">
    {!! Form::label('horas_operacion', 'Horas de Operacion:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('horas_operacion', null, ['class' => 'form-control integer', 'placeholder' => '0']) !!}
    </div>
</div>

<!-- Horas Programa Form Input -->
<div class="form-group">
    {!! Form::label('horas_programa', 'Horas de Programa:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('horas_programa', null, ['class' => 'form-control integer', 'placeholder' => '0']) !!}
    </div>
</div>

<!-- Horas Programa Form Input -->
<div class="form-group">
    {!! Form::label('observaciones', 'Observaciones:', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::textarea('observaciones', null, ['class' => 'form-control integer', 'rows' => 3]) !!}
    </div>
</div>