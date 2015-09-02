<!-- Nombre Form Input -->
<div class="form-group">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
</div>

<!-- Clave Form Input -->
<div class="form-group">
    {!! Form::label('clave', 'Clave:') !!}
    {!! Form::text('clave', null, ['class' => 'form-control']) !!}
</div>

<!-- Descripcion Form Input -->
<div class="form-group">
    {!! Form::label('descripcion', 'Descripcion:') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<!-- Tipo Form Input -->
<div class="form-group">
    {!! Form::label('subtipo_id', 'Tipo:') !!}
    {!! Form::select('subtipo_id', $subtipos, null, ['class' => 'form-control']) !!}
</div>

<div class="row">
    <div class="col-sm-6">
        <!-- Cantidad Form Input -->
        <div class="form-group">
            {!! Form::label('cantidad', 'Cuantas areas va a crear?') !!}
            {!! Form::text('cantidad', 1, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-sm-6">
        <!-- Rango Inicial Form Input -->
        <div class="form-group">
            {!! Form::label('rango_inicial', 'Rango Inicial:') !!}
            {!! Form::text('rango_inicial', 1, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<!-- Dentro de que otra area se crearan? Form Input -->
<div class="form-group">
    {!! Form::label('area_id', 'Dentro de que otra area estaran?') !!}
    {!! Form::select('area_id', $areas, Request::get('dentro_de'), ['class' => 'form-control']) !!}
</div>
<hr>

<div class="form-group">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
</div>