<div class="row">
  <div class="col-sm-6">
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
  </div>
  <div class="col-sm-6">
    <!-- Tipo Form Input -->
    <div class="form-group">
        <div class="alert alert-danger" role="alert">
            Si modifica el área tipo los artículos esperados del área serán reemplazados por los artículos esperados de la nueva área tipo
        </div>
      {!! Form::label('tipo_id', 'Área Tipo:') !!}
      {!! Form::select('tipo_id', $tipos, null, ['class' => 'form-control']) !!}
       
    </div>

    <!-- Se encuentra en Form Input -->
    <div class="form-group">
      {!! Form::label('parent_id', 'Se encuentra en:') !!}
      {!! Form::select('parent_id', $areas, Request::get('dentro_de'), ['class' => 'form-control']) !!}
    </div>
  </div>
  <div class="col-sm-12">
    <!-- Descripción Form Input -->
    <div class="form-group">
        {!! Form::label('descripcion', 'Descripción:') !!}
        {!! Form::textarea('descripcion', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>
  </div>
</div>

<hr>

<div class="form-group">
  {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
</div>