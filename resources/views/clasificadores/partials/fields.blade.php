<!-- Nombre Form Input -->
<div class="form-group">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Descripcion Form Input -->
<div class="form-group">
    {!! Form::label('descripcion', 'Descripcion:') !!}
    {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<!-- Dentro de que otro tipo se creara? Form Input -->
<div class="form-group">
    {!! Form::label('parent_id', 'Dentro de que otro clasificador estará?') !!}
    {!! Form::select('parent_id', $clasificadores, Request::get('dentro_de'), ['class' => 'form-control']) !!}
</div>