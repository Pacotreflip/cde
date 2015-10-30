@extends('layout')

@section('content')
  @include('clasificadores.partials.breadcrumb')

  <div class="row">
    <div class="col-md-6">
      <h1>Clasificador de Artículo</h1>
      <hr>

      {!! Form::model($clasificador, ['route' => ['clasificadores.update', $clasificador], 'method' => 'PATCH']) !!}
        @include('clasificadores.partials.fields')
        
        <div class="form-group">
          {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
        </div>
      {!! Form::close() !!}

      @include('partials.errors')

      <hr>

      <div class="alert alert-danger" role="alert">
        <h4><i class="fa fa-fw fa-exclamation"></i>Atención:</h4>
        <p>
          Al borrar este clasificador, todos los subclasificadores contenidos en el también seran borrados.
        </p>
        <p>
          {!! Form::open(['route' => ['clasificadores.delete', $clasificador], 'method' => 'DELETE']) !!}
            {!! Form::submit('Borrar este clasificador', ['class' => 'btn btn-danger']) !!}
          {!! Form::close() !!}
        </p>
      </div>
    </div>
  </div>
@stop

    