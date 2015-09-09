<div class="row">
    <div class="col-md-6">
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
    </div>
    <div class="col-md-6">
        <!-- Tipo Form Input -->
        <div class="form-group">
            {!! Form::label('tipo_id', 'Tipo:') !!}
            {!! Form::select('tipo_id', $tipos, null, ['class' => 'form-control']) !!}
        </div>

        <!-- Dentro de que otra area se crearan? Form Input -->
        <div class="form-group">
            {!! Form::label('parent_id', 'Se encuentra en:') !!}
            {!! Form::select('parent_id', $areas, Request::get('dentro_de'), ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<hr>

<div class="form-group">
    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
</div>