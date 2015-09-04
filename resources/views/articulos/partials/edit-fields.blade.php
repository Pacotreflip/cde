<!-- Nombre Form Input -->
<div class="form-group">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control']) !!}
</div>

<!-- Numero de Parte Form Input -->
<div class="form-group">
    {!! Form::label('numero_parte', 'Numero de Parte:') !!}
    {!! Form::text('numero_parte', null, ['class' => 'form-control']) !!}
</div>

<div class="row">
    <div class="col-xs-6">
        <!-- Unidad Form Input -->
        <div class="form-group">
            {!! Form::label('unidad', 'Unidad:') !!}
            {!! Form::select('unidad', $unidades, null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <!-- Clasificador Form Input -->
        <div class="form-group">
            {!! Form::label('clasificador_id', 'Clasificador:') !!}
            {!! Form::select('clasificador_id', $clasificadores, null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<!-- Descripcion Form Input -->
<div class="form-group">
    {!! Form::label('descripcion', 'Descripcion:') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<!-- Ficha Tecnica Form Input -->
<div class="form-group">
    {!! Form::label('ficha_tecnica', 'Ficha Tecnica:') !!}
        @if($articulo->ficha_tecnica_nombre)
            <a href="{{ $articulo->ficha_tecnica_path }}" target="_blank">
                <i class="fa fa-fw fa-file"></i> {{ $articulo->ficha_tecnica_nombre }}
            </a>
        @endif
    {!! Form::file('ficha_tecnica', null, ['class' => 'form-control']) !!}
</div>

<hr>

<div class="form-group">
    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
</div>