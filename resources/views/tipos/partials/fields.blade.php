<!-- Nombre Form Input -->
<div class="form-group">
  {!! Form::label('nombre', 'Nombre:') !!}
  {!! Form::text('nombre', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Descripción Form Input -->
<div class="form-group">
  {!! Form::label('descripcion', 'Descripción:') !!}
  {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<!-- Dentro de que otro tipo estará? Form Input -->
<div class="form-group">
  {!! Form::label('parent_id', 'Dentro de que otro tipo estará?') !!}
  {!! Form::select('parent_id', $tipos, Request::get('dentro_de'), ['class' => 'form-control']) !!}
</div>