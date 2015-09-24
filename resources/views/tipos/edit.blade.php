@extends('layout')

@section('content')
  @include('tipos.partials.breadcrumb')

  <h1>Tipo de Área</h1>
  <hr>

  <div class="row">
    <div class="col-md-2">
      @include('tipos.nav')
    </div>

    <div class="col-md-10">
      {!! Form::model($tipo, ['route' => ['tipos.update', $tipo], 'method' => 'PATCH']) !!}
        @include('tipos.partials.fields')
        
        <div class="form-group">
          {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
        </div>
      {!! Form::close() !!}

      @include('partials.errors')

      <hr>
      
      <div class="alert alert-danger" role="alert">
        <h4><i class="fa fa-fw fa-exclamation"></i>Atención:</h4>
        <p>
          Al borrar este tipo de área, todos los subtipos contenidos también seran borrados.
        </p>
        <p>
          {!! Form::open(['route' => ['tipos.delete', $tipo], 'method' => 'DELETE']) !!}
            {!! Form::submit('Borrar este tipo de área', ['class' => 'btn btn-danger']) !!}
          {!! Form::close() !!}
        </p>
      </div>
    </div>
  </div>
@stop