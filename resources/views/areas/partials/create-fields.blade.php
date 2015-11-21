<div class="row">
  <div class="col-sm-6">
    <!-- Nombre Form Input -->
    <div class="form-group">
      {!! Form::label('nombre', 'Nombre:') !!}
      {!! Form::text('nombre', null, ['class' => 'form-control', 'autofocus']) !!}
    </div>

    <!-- Clave Form Input -->
    <div class="form-group">
      {!! Form::label('clave', 'Clave:') !!}
      {!! Form::text('clave', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Descripción Form Input -->
    <div class="form-group">
      {!! Form::label('descripcion', 'Descripción:') !!}
      {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>

    <!-- Tipo Form Input -->
    <div class="form-group">
      {!! Form::label('tipo_id', 'Tipo:') !!}
      {!! Form::select('tipo_id', $tipos, null, ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-sm-6">
    <div class="row">
      <div class="col-sm-6">
        <!-- Cantidad Form Input -->
        <div class="form-group">
          {!! Form::label('cantidad', 'Cuantas va a crear?') !!}
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

    <!-- Dentro de que area se van a generar? Form Input -->
    <div class="form-group">
      {!! Form::label('parent_id', 'Dentro de que área se van a generar?') !!}
      {!! Form::select('parent_id', $areas, Request::get('dentro_de'), ['class' => 'form-control']) !!}
    </div>
  </div>
</div>

<hr>

<div class="form-group">
  {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
</div>