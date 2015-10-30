@extends('layout')

@section('content')
  @include('areas.partials.breadcrumb', ['ancestros' => $area->getAncestors()])

  <h1>Área</h1>
  <hr>

  {!! Form::model($area, ['route' => ['areas.update', $area], 'method' => 'PATCH']) !!}
    @include('areas.partials.edit-fields')
  {!! Form::close() !!}

  <hr>
  
  @if($area->tipo)
    <table class="table table-condensed table-striped">
      <thead>
        <tr>
          <th>Descripción</th>
          <th>Unidad</th>
          <th>Cantidad Requerida</th>
        </tr>
      </thead>
      <tbody>
        @foreach($area->tipo->materiales as $material)
          <tr>
            <td>
              <span data-toggle="tooltip" data-placement="top" title="{{ $material->descripcion }}">
                {{ str_limit($material->descripcion, 60) }}
              </span>
            </td>
            <td>{{ $material->unidad }}</td>
            <td>{{ $material->pivot->cantidad_requerida }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

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

@section('scripts')
  <script>
    $('[data-toggle="tooltip"]').tooltip();
  </script>
@stop