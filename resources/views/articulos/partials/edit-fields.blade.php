<!-- Nombre Form Input -->
<div class="form-group">
    {!! Form::label('descripcion', 'Nombre:') !!}
    {!! Form::text('descripcion', null, ['class' => 'form-control']) !!}
</div>

<div class="row">
    <div class="col-xs-6">
        <!-- Numero de Parte Form Input -->
        <div class="form-group">
            {!! Form::label('numero_parte', 'Numero de Parte:') !!}
            {!! Form::text('numero_parte', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <!-- Unidad Form Input -->
        <div class="form-group">
            {!! Form::label('unidad', 'Unidad:') !!}
            {!! Form::select('unidad', $unidades, null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-6">
        <!-- Familia Form Input -->
        <div class="form-group">
            {!! Form::label('id_familia', 'Familia:') !!}
            {!! Form::select('id_familia', $familias, $material->familia() ? $material->familia()->id_material : null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <!-- Clasificador Form Input -->
        <div class="form-group">
            {!! Form::label('id_clasificador', 'Clasificador:') !!}
            {!! Form::select('id_clasificador', $clasificadores, null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<!-- Descripcion Form Input -->
<div class="form-group">
    {!! Form::label('descripcion_larga', 'Descripcion:') !!}
    {!! Form::textarea('descripcion_larga', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<!-- Ficha Tecnica Form Input -->
<div class="form-group">
    {!! Form::label('ficha_tecnica', 'Ficha Tecnica:') !!}
        @if($material->ficha_tecnica_nombre)
            <a href="/{{ $material->ficha_tecnica_path }}" target="_blank">
                <i class="fa fa-fw fa-file"></i> {{ $material->ficha_tecnica_nombre }}
            </a>
        @endif
    {!! Form::file('ficha_tecnica', null, ['class' => 'form-control']) !!}
</div>

<hr>

<div class="form-group">
    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
</div>