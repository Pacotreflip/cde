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
@if($area->cierre_partida)
<div class="alert alert-success" role="alert">
    <span class="glyphicon glyphicon-lock" style="padding-right: 5px"></span>
    Área cerrada: <strong>Cierre # {{$area->cierre_partida->cierre->numero_folio}}</strong>
    {{ $area->cierre_partida->cierre->usuario->present()->nombreCompleto }}
    [{{ $area->cierre_partida->cierre->fecha_cierre->format('d-m-Y H:m') }}
            <small >({{ $area->cierre_partida->cierre->created_at->diffForHumans() }})]</small>
</div>
@if ($area->entrega_partida())
<div class="alert alert-success" role="alert">
    <span class="glyphicon glyphicon-ok-circle" style="padding-right: 5px"></span>
    Área entregada: <strong>Entrega # {{$area->cierre_partida->entrega_partida->entrega->numero_folio}}</strong>
    {{ $area->cierre_partida->entrega_partida->entrega->usuario->present()->nombreCompleto }}
    
    [{{ $area->cierre_partida->entrega_partida->entrega->fecha_entrega->format('d-m-Y H:m') }}
            <small >({{ $area->cierre_partida->entrega_partida->entrega->created_at->diffForHumans() }})]</small>
</div>
@endif
@else
<div class="form-group">
  {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
</div>
@endif