@extends('layout')

@section('content')
  @include('areas.partials.breadcrumb', ['ancestros' => $area->getAncestors()])

  <h1>Área</h1>
  <hr>

  {!! Form::model($area, ['route' => ['areas.update', $area], 'method' => 'PATCH']) !!}
    @include('areas.partials.edit-fields')
  {!! Form::close() !!}

  <hr>
  
  <div class="alert alert-danger" role="alert">
    <h4><i class="fa fa-fw fa-exclamation"></i>Atención:</h4>
    <p>
      Al borrar esta area, todas las subareas contenidas también seran borradas.
    </p>
    <p>
      {!! Form::open(['route' => ['areas.delete', $area], 'method' => 'DELETE']) !!}
        {!! Form::submit('Borrar esta área', ['class' => 'btn btn-danger']) !!}
      {!! Form::close() !!}
    </p>
  </div>
@stop